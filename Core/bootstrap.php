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
    private $oLayout;
    
    private $oUser;
    
    private $aViews = array();
    
    private $sOutputBuffer = '';
    
    function __construct() {
        global $oRegistry;
//        $this->oRegistry = $oRegistry;

        $this->oLocale = new Locale\clLocale();
        
        $this->oRouter = new Router\clRouter();
        $this->oTemplate = new Render\clTemplate();
        $this->oLayout = new Render\clLayout();
        
        $this->oUser = new User\clUser();

        $oRegistry::set( 'clRouter', $this->oRegistry );
        $oRegistry::set( 'clLocale', $this->oLocale );
        $oRegistry::set( 'clTemplate', $this->oTemplate );
        $oRegistry::set( 'clLayout', $this->oLayout );
        $oRegistry::set( 'oUser', $this->oUser );
        
        $this->oLocale->generateAutoloader();
        if( file_exists( PATH_CACHE . 'autoload/cacheLocaleLoader.php') ) {
            require_once( PATH_CACHE . 'autoload/cacheLocaleLoader.php');
        }
        $this->oLocale->init();
    }
    
    public function execute() {
        try {
            $aRouteData = $this->oRouter->readRoute( $this->oRouter->getRoutePath() );
            if( !empty( $aRouteData) ) {
                $aRouteData = current($aRouteData);
                $sLayoutKey = $aRouteData['routeLayoutKey'];
                $aLayoutData = $this->oLayout->readLayoutByKey($sLayoutKey);
                if( !empty( $aLayoutData) ) {
                    $aLayoutData = current( $aLayoutData );
                    $aLayoutViews = $this->oLayout->readViewsByLayout($aLayoutData['layoutId']);
                    foreach( $aLayoutViews as $aView ) {
                        if( $aView['viewCallback'] == 'yes' ) {
                            $sView = "Modules\\" . $aView['viewModule'] . "\\Callback\\" . $aView['viewFile'];
                        } else {
                            $sView = "Modules\\" . $aView['viewModule'] . "\\Views\\" . $aView['viewFile'];
                        }
                        $oView = new $sView( $this->oTemplate, $this->oRouter );
                        $this->aViews[] = $oView;
                    }
                    if( isset($aLayoutData['layoutModuleTemplate']) 
                        && !empty($aLayoutData['layoutModuleTemplate']) ) {
                        $this->oTemplate->setTemplatePath( PATH_MODULE . $aLayoutData['layoutModuleTemplate'] . '/Templates/' );
                        $this->oTemplate->setTemplate( $aLayoutData['layoutTemplate'] . '.php' );
                    } else {
                        $this->oTemplate->setTemplatePath( PATH_TEMPLATE );
                        $this->oTemplate->setTemplate( $aLayoutData['layoutTemplate'] );
                        $this->oTemplate->setTitle( $aLayoutData['layoutTitle'] );
                    }
                }
            } else {
                dd( "Didn't find anything on that route :(" );
            }
        } catch (Exception $ex) {
            dd( $ex );
        }
        
        
        
        
        // Just for testing
//        $this->oTemplate->setTemplatePath(PATH_MODULE . 'Dashboard/Templates/');
//        $this->oTemplate->setTemplate('dashboard.php');
//        $sView = 'Modules/Dashboard/Views/formLogin';
//        $sView = str_replace('/', "\\", $sView);
//        $oView = new $sView( $this->oTemplate, $this->oRouter );
//        $this->aViews[] = $oView;
//        
        if( DEBUG === true ) {
            $sDebugView = "Modules\\Debug\\Views\\showDebug";
            $oDebugView = new $sDebugView( $this->oTemplate, $this->oRouter );
            $this->aViews[] = $oDebugView;
        }
        
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
