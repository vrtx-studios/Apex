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
