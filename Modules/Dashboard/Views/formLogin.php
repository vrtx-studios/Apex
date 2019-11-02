<?php

namespace Modules\Dashboard\Views;

use Core\Render\clView as view;

class formLogin extends view {
    
    public function render() {
        
        $sOutput = '<div id="wrapper" class="login">'
                . '<main id="login">'
                    . '<div id="overlay">hi</div>'
                    . '<div id="loginPanel">'
                    . '<img src="/images/templates/dashboard/logo_filled.png" />'
                    . '</div>'
                . '</main>'
                . '</div>';
        
        
        $this->setContent($sOutput);
        return parent::render();
    }
    
}
