<?php
namespace Core\Locale;

require_once( PATH_FUNCTIONS . 'fLocale.php' );

use Core\Module\clModuleBase as moduleBase;

/**
 * Description of clLocale
 *
 * @author Dronki
 */
class clLocale extends moduleBase {
    
    public $sEncoding;

    public $aLocales = array();

    public function __construct() {
        $this->oDb = new clLocaleDao();
        $this->addLocale( 'default', PATH_LOCALE . '/' );
    }
    
    public function init() {
        $this->setLocale( $_SESSION['locale'] );
    }
    
    public function installLocale( $sDomain, $sLocalePath, $bAutoload = 'yes' ) {
        if( strlen($sDomain) <= 0 || strlen($sLocalePath) <= 0 ) return false;
        return parent::create( array(
            'localeDomain',
            'localePath',
            'localeAutoload',
            'localeCreated'
        ), array(
            'localeDomain' => $this->oDb->escapeStr( $sDomain ),
            'localePath' => $this->oDb->escapeStr( $sLocalePath ),
            'localeAutoload' => ( $bAutoload == 'yes' ? $this->oDb->escapeStr('yes') : $this->oDb->escapeStr('no') ) ,
            'localeCreated' => date( 'Y-m-d H:i:s' )
        ) );
    }
    
    public function generateAutoloader() {
        $sSavePath = PATH_CACHE . 'autoload/cacheLocaleLoader.php';
        $aLines = array();
        $aLines[] = '<?php';
        $aLines[] = '// File generated @ ' . date( 'Y-m-d H:i:s' );
        $aLocales = parent::read();
        foreach( $aLocales as $aEntry ) {
            if( $aEntry['localeAutoload'] == 'yes' ) {
                $aLines[] = '$this->oLocale->addLocale( "' . $aEntry['localeDomain'] . '", "' . $aEntry['localePath'] . '" );';
            }
        }
        file_put_contents($sSavePath, implode("\n", $aLines) );
    }
    
    public function addLocale( $sDomain, $sLocalePath ) {
        if( strlen($sDomain) <= 0 || strlen($sLocalePath) <= 0 ) return false;
        $this->aLocales[$sDomain] = $sLocalePath;
        return true;
    }

    public function setLocale( $sUserLang ) {
        $codeset = "UTF8";
        putenv('LANG='.$sUserLang.'.'.$codeset);
        putenv('LANGUAGE='.$sUserLang.'.'.$codeset);
        bind_textdomain_codeset('default', $codeset);

        // set locale
        foreach( $this->aLocales as $sDomain => $sLocalePath ) {
            bindtextdomain( $sDomain, $sLocalePath );
        }
        // bindtextdomain('default', PATH_LOCALE . '/' );
        
        
        // Detect if we're running on windows, as the locale-support for windows is kind of fucky.
        if( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ) {
            setlocale(LC_ALL, $sUserLang.'.'.$codeset);
        } else {
            setlocale(LC_MESSAGES, $sUserLang.'.'.$codeset);
        }
        textdomain('default'); 
    }
    
}
