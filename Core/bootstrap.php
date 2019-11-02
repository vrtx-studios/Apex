<?php

namespace Core;

use Modules as modules;

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
    
    private $aViews = array();
    
    private $sOutputBuffer = '';
    
    function __construct() {
        global $oRegistry;
//        $this->oRegistry = $oRegistry;

        $this->oLocale = new Locale\clLocale();

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
        // Just for testing
        $this->oTemplate->setTemplatePath(PATH_MODULE . 'Dashboard/Templates/');
        $this->oTemplate->setTemplate('dashboardLogin.php');
//        
        $sView = 'Modules/Dashboard/Views/formLogin';
        $sView = str_replace('/', "\\", $sView);
        $oView = new $sView( $this->oTemplate, $this->oRouter );
        $this->aViews[] = $oView;
        
        $this->render();
    }
    
    public function render() {
        ob_start();
        
        $sViewBuffer = '';
        foreach( $this->aViews as $oView ) {
            try {
                $sViewBuffer .= $oView->render();
            } catch (Exception $ex) {
                $sViewBuffer .= 'Uh oh, view failed to render: ' . $ex->getMessage();
            } catch (Throwable $th ) {
                $sViewBuffer .= 'Uh oh, view failed to render: ' . $th->getMessage();
            }
        }
        $this->oTemplate->setContent( $sViewBuffer );
        $this->sOutputBuffer = $this->oTemplate->render();
        
        ob_end_flush();
        
        echo $this->sOutputBuffer;
    }
    
}
