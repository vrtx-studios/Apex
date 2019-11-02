<?php
namespace Core\Router;

use Core\Module\clModuleBase as moduleBase;

/**
 * Description of clRouter
 *
 * @author Dronki
 */
class clRouter extends moduleBase {
    
    public $sPath;
    
    public function __construct() {
        $this->oDb = new clRouterDao();
    }
 
    public function redirect( $sPath ) {    
        header( 'location: ' . $sPath );
        exit;
    }
    
    public function getRouteByKey( $sLayoutKey ) {
        $aData = parent::read( array(
            'routeLayoutKey' => $this->oDb->escapeStr( $sLayoutKey ),
            'routeLangId' => $_SESSION['langId']
        ) );
        if( !empty($aData) ) {
            return current($aData);
        } else {
            return false;
        }
    }
    
    public function readRoute( $sRoute = '' ) {
        return parent::read( array(
            'routePath' => $this->oDb->escapeStr($sRoute),
            'routeLangId' => $_SESSION['langId']
        ) );
    }
    
    public function getRoutePath() {
        if( isset($_SERVER['PATH_INFO']) ) {
            $sPath = $_SERVER['PATH_INFO'];

        } else {
            $sQuery = strpos( $_SERVER['REQUEST_URI'], '?' );

            if( $sQuery === false ) {
                $sPath = $_SERVER['REQUEST_URI'];

            } else {
                $sPath = substr( $_SERVER['REQUEST_URI'], 0, $sQuery );

            }

            $sPath = rawurldecode( $sPath );
            //$sPath = rawurldecode( str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $sPath) );
        }

        return '/' . trim( $sPath, '/' );
    }
    
}
