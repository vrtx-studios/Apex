<?php

namespace Modules\Debug\Models;

use Core\Module\clModuleBase as moduleBase;

class clDebug extends moduleBase {
    
    public $oBenchmark = null;
    
    public function __construct() {
        if( DEBUG === false ) return;
        $this->sModuleName = 'Debug';
        $this->sModulePrefix = 'debug';
        
        global $oRegistry;
        $this->oBenchmark = $oRegistry::get( 'clBenchmark' );
        
        $this->oBenchmark->timer("stop", "bootstrap");
        
        $this->initBase();
    }
    
    public function getRenderTime() {
        return $this->oBenchmark->timer("show", "bootstrap");
    }
    
}
