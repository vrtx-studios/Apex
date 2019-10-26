<?php

namespace Core;

/**
 * Description of bootstrap
 *
 * @author Dronki
 */
class bootstrap {
    
    private $oRegistry;
    private $oTemplate;
    private $oRouter;
    private $oLocale;
    
    function __construct() {
        global $oRegistry;
//        $this->oRegistry = $oRegistry;

        $this->oLocale = new Locale\clLocale();
        $this->oLocale->install();

        $this->oRouter = new Router\clRouter();
        $this->oTemplate = new Render\clTemplate();


        $oRegistry::set( 'clRouter', $this->oRegistry );
        $oRegistry::set( 'clLocale', $this->oLocale );
        $oRegistry::set( 'clTemplate', $this->oTemplate );

        //This needs to happen last, as this will list all modules and initialize the languages for each active module.
        /**
         * Pseudo:
         * foreach( $aModules as $aEntry ) {
         *  $this->oLocale->addLocale( $aEntry->getTextDomain(), $aEntry->getLocalePath );
         * }
         */
        $this->oLocale->addLocale( 'comment', PATH_MODULE . 'Comment/Locale' );
        $this->oLocale->init();
    }
    
    public function execute() {
        echo __( "Create comment", "comment" );
        echo '<br />';
        echo _( 'Create comment' );
    }
}
