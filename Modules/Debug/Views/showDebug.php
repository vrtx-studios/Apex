<?php

namespace Modules\Debug\Views;

use Core\Render\clView as view;
use Modules\Debug\Models as dbgModel;

class showDebug extends view {
    
    private $oModel = null;
    
    public function __construct() {
        parent::__construct();
        $this->oTemplate->addTop( array(
            'key' => 'cssDebug',
            'content' => '<link rel="stylesheet" href="/css/?include=libraries/debug">'
        ) );
        $this->oModel = new dbgModel\clDebug();
    }
    
    public function render() {
        $sOutput = '<div id="debugBar">'
                    . $this->oModel->getRenderTime()
                    . ' sec | '
                    . $this->oModel->oBenchmark->memoryUsage()
                    . ' | '
                    . $this->oModel->oBenchmark->averageSystemLoad()
                . '</div>';
        $this->setContent($sOutput);
        return parent::render();
    }
    
}
