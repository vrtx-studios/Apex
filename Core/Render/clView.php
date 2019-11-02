<?php

namespace Core\Render;

/**
 * Description of clView
 *
 * @author dronki
 */
abstract class clView {
    
    public $oTemplate;
    public $oRouter;
    
    public $sContent = '';
    public $bPostReceived = false;
    public $aPostData = array();
    
    public function __construct( $oTemplate = null, $oRouter = null ) {
	global $oRegistry;
	if( $oTemplate != null ) $this->oTemplate = $oTemplate;
	else $this->oTemplate = $oRegistry::get( 'clTemplate' );
	
	if( $oRouter != null ) $this->oRouter = $oRouter;
	else $this->$oRouter = $oRegistry::get( 'clRouter' );
    }
    
    public function setContent($sContent) {
	$this->sContent = $sContent;
    }
    
    public function getContent() {
	return $this->sContent;
    }

    public function postdataReceived( $aPostData = array() ) {
	$this->bPostReceived = true;
	$this->aPostData = $aPostData;
    }
    
    public function render() {
	ob_start();
	echo $this->sContent;
	
	$sRender = ob_get_clean();
	
	return $sRender;
    }
    
}
