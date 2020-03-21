<?php

namespace Core\Render;

use Core\Module\clModuleBase as moduleBase;

class clLayout extends moduleBase {
    
    public function __construct() {
        $this->sModuleName = "Layout";
        $this->sModulePrefix = "layout";
        
        $this->oDb = new clLayoutDao();
        
        $this->initBase();
    }
    
    public function readLayoutByKey( $sLayoutKey = '' ) {
        return parent::read( array(
            'layoutKey' => $this->oDb->escapeStr($sLayoutKey)
        ) );
    }
    
    public function readViewsByLayout( $iLayoutId = 0 ) {
        return $this->oDb->readViewsByLayout( $iLayoutId );
    }
    
}
