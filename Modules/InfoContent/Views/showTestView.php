<?php

namespace Modules\InfoContent\Views;

use Core\Render\clView as view;

class showTestView extends view {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function render() {
        $sOutput = '<h1>Hello</h1>';
        $this->setContent($sOutput);
        return parent::render();
    }
    
}
