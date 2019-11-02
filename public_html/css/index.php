<?php

require_once '../../Config/cfBase.php';
require_once '../../Core/SCSS/scss.inc.php';
require_once '../../Core/SCSS/clSCSSServer.php';

use Core\SCSS\clSCSSServer;

$aFiles	= array();
$sCurrentDirectory = dirname(__FILE__);
$sCurrentDirectoryLength = strlen($sCurrentDirectory);

date_default_timezone_set( DEFAULT_TIMEZONE );

header( 'Content-Type: text/css; charset=UTF-8' );
header( 'Cache-Control: must-revalidate' );
header( 'Expires: ' . gmdate('D, d M Y H:i:s', time() + CACHE_CSS_TIME) . ' GMT' );

if( defined('COMPRESS_CSS') && COMPRESS_CSS === true && extension_loaded('zlib') && ini_get('zlib.output_compression') == 0 ) {
    ob_start('ob_gzhandler');
} else {
    ob_start();
}

$oServer = new clSCSSServer( sCurrentDirectory, PATH_CACHE );

try {
    if( isset( $_GET['include'] ) ) {
        $aIncludePaths = explode( ';', $_GET['include'] );
        foreach( $aIncludePaths as &$sIncludePath ) {
            
            $sIncludePath = trim( $sIncludePath, '/' );
            if( $sIncludePath[0] == '.' ) {
                throw new Exception( 'Path cannot begin with a dot' );
            }
            
            if( !is_dir( $sIncludePath ) ) {
                $aFiles[] = $sIncludePath;
                continue;
            }
            
            
            if( strpos($sIncludePath, '.') !== false ) {
                throw new Exception( 'Directory path contained dot' );
            }
            $aFiles += scandir( $sCurrentDirectory . '/' . $sIncludePath );
            $oServer->scss->addImportPath( $sCurrentDirectory . '/' . $sIncludePath . '/' );
            
            foreach( $aFiles as &$sFile ) {
                $sFile = $sIncludePath . '/' . $sFile;
            }
        }
    } else {
        $aFiles = scandir( $sCurrentDirectory );
    }
} catch (Exception $ex) {
    throw new Exception( 'Invalid path: ' . $e->getMessage() );
}

foreach( $aFiles as &$sFile ) {
    if( $sFile[0] == '.' || is_dir( $sFile ) ) {
        continue;
    }

    $sBuffer = null;
    $sExtension = pathinfo( $sFile, PATHINFO_EXTENSION );
    $sFile = $sCurrentDirectory . '/' . $sFile;

    try {
        if( isset($oServer) ) {
            $sBuffer = $oServer->compileFile($sFile);
        } else {
            if( $sExtension ) {
                if( $sExtension !== 'css' ) {
                    throw new Exception( 'Invalid file extension (' . $sExtension . ') in ' . $sFile );
                }
            } else {
                $sFile .= '.css';  
            }
            $sRealpath = realpath( $sFile );

            if( !$sRealpath ) {
                throw new Exception( 'File not found: ' . $sFile );
            }

            // May not be outside of current directory
            if( strrpos($sRealpath, $sCurrentDirectory, -strlen( $sRealpath ) ) === FALSE ) {
                throw new Exception( 'Access denied: ' . $sFile );
            }

            $sBuffer = file_get_contents( $sFile );
        }
    } catch (Exception $ex) {
        echo "\n\n/* Exception: " . $ex->getMessage() . " */\n\n";
    }

   // Compress buffer
    if( defined('COMPRESS_CSS') && COMPRESS_CSS === true && $sBuffer ) {
            $sBuffer = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $sBuffer );
            $sBuffer = str_replace( array("\r\n", "\r", "\n", "\t"), "", $sBuffer );
            $sBuffer = str_replace( array("; ", " {"), array(";", "{"), $sBuffer );
            $sBuffer = str_replace( array( "     ", "    ", "   ", "  " ), array(" "), $sBuffer ); // Remove "all" double spaces
    }

    echo $sBuffer;
}
