<?php

namespace Core;

/**
 * Description of bootstrap
 *
 * @author Dronki
 */
class bootstrap {
    
    private $oRegistry;
    private $oRouter;
    private $oLocale;
    
    function __construct() {
        global $oRegistry;
//        $this->oRegistry = $oRegistry;

        $this->oLocale = new Locale\clLocale();
        $this->oRouter = new Router\clRouter();
        
        $oRegistry::set( 'clRouter', $this->oRegistry );
        $oRegistry::set( 'clLocale', $this->oLocale );
    }
    
    public function execute() {
        print_r($this->oRouter->getRouteByKey("guestInfoTest"));
    }
}
