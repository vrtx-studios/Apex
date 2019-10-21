<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Core\Locale;

/**
 * Description of clLocale
 *
 * @author Dronki
 */
class clLocale {
    
    public $sEncoding;
    
    public function __construct() {
        $this->setLocale( $_SESSION['locale'] );
    }
    
   public function setLocale( $sUserLang ) {
        $codeset = "UTF8";
        putenv('LANG='.$sUserLang.'.'.$codeset);
        putenv('LANGUAGE='.$sUserLang.'.'.$codeset);
        bind_textdomain_codeset('default', $codeset);

        // set locale
        bindtextdomain('default', PATH_LOCALE . '/' );
        
        // Detect if we're running on windows, as the locale-support for windows is kind of fucky.
        if( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ) {
            setlocale(LC_ALL, $sUserLang.'.'.$codeset);
        } else {
            setlocale(LC_MESSAGES, $sUserLang.'.'.$codeset);
        }
        textdomain('default'); 
    }
    
}
