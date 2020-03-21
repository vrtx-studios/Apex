<?php

namespace Core\Render;

class clFormRender {
    /*
     * Implementation of outputting raw datadict to html5-form, as well as implementing
     * the JSON-form from https://formbuilder.online/docs/ 
     */

    public $aDataDict = array();
    public $iFormId = 1;
    public $aParams = array();
    private $oRouter = null;

    public function __construct($aParams = array()) {
        global $oRegistry;
        $this->oRouter = $oRegistry::get('clRouter');

        $aParams += array(
            'method' => 'post',
            'enctype' => '',
            'action' => $this->oRouter->getRoutePath(),
            'autocomplete' => 'on',
            'class' => 'form',
            'labels' => true,
            'token' => false,
            'tokenValue' => (!empty($_SESSION['token']) ? $_SESSION['token'] : '' )
        );
        $this->aParams = $aParams;
    }

    public function setDataDict($aDataDict = array()) {
        if (is_string($aDataDict)) {
            // JSON-formatted
            throw new Exception("This is not implemented yet..");
        }
        $this->aDataDict = $aDataDict;
    }

    public function render() {
        $sElements = '';
        $sTokenField = '';
        $sOutput = '';
        $bTokenFieldGenerated = false;
        
        $this->iFormId++;
        foreach( $this->aDataDict as $sKey => $aEntry ) {
            if( $this->aParams['labels'] == true ) {
                $sLabel = '<label for="' . $aEntry['title'] . '">' . $aEntry['title'] . '</label>';
            } else {
                $sLabel = '';
            }
            
            if( $this->aParams['token'] == true ) {
                if( !$bTokenFieldGenerated ) {
                    $sTokenField = '<input type="hidden" name="token" value="' . $this->aParams['tokenValue'] . '" />';
                    $bTokenFieldGenerated = true;
                }
            }
            
            switch( $aEntry['type'] ) {
                case 'text':
                case 'textarea':
                    $sInput = $this->generateTextarea( $sKey, $aEntry );
                    break;
                case 'button':
                    $sLabel = '';
                    $sInput = $this->generateSubmit( $sKey, $aEntry );
                    break;
                case 'array':
                case 'select':
                    $sInput = $this->generateSelect( $sKey, $aEntry );
                    break;
                case 'file':
                    $sInput = $this->generateFileUpload( $sKey, $aEntry );
                    break;
                case 'hidden':
                    $sLabel = '';
                    $sInput = '<input type="hidden" name="' . $sKey . '" value="' . $aEntry['value'] . '" />';
                    break;
                case 'password':
                    $sInput = $this->generatePassword( $sKey, $aEntry );
                    break;
                case 'text':
                default:
                    $sInput = $this->generateText( $sKey, $aEntry );
                    break;
            }
            $sElements .= $sLabel . '<p>' . $sInput . '</p>';
        }
        
        $sEncType = ( !empty($this->aParams['enctype']) ? 'enctype="' . $this->aParams['enctype'] . '"' : '' );
        $sOutput = '<form action="' . $this->aParams['action'] . '" method="' . $this->aParams['method'] . '" class="' . $this->aParams['class'] . '" ' . $sEncType . '>'
                    . '<input type="hidden" name="action" value="submit_' . $this->iFormId . '" />'
                    . $sElements
                    . $sTokenField
                    . '</form>';
        return $sOutput;
    }

    private function generateTitle($sContent = '', $sTitleTag = 'h2') {
        return "<$sTitleTag>$sContent</$sTitleTag>";
    }

    private function generateFileUpload($sTitle = '', $aParams = array()) {
        $sName = $sTitle;
        $bMultiple = false;

        $sAttributes = '';
        if (!empty($aParams['attributes'])) {
            foreach ($aParams['attributes'] as $sKey => $sValue) {
                $sAttributes .= $sKey . '="' . $sValue . '"';
                if ($sKey == 'multiple')
                    $bMultiple = true;
                $sName .= '[]';
            }
        }
        return '<input type="file" name="' . $sName . '" ' . $sAttributes . '>';
    }

    private function generateSelect($sTitle = '', $aParams = array()) {
        $sClass = '';
        if (!empty($aParams['attributes'])) {
            $sClass = !empty($aParams['attributes']['class']) ? $aParams['attributes']['class'] : '';
        }
        $sOptions = '';
        foreach ($aParams['values'] as $sKey => $sValue) {
            $sOptions .= '<option value="' . $sKey . '">' . $sValue . '</option>';
        }
        return '<select name="' . $sTitle . '"class="' . $sClass . '">' . $sOptions . '</select>';
    }

    private function generateSubmit($sTitle = '', $aParams = array()) {
        $sClass = '';
        if (!empty($aParams['attributes'])) {
            $sClass = !empty($aParams['attributes']['class']) ? $aParams['attributes']['class'] : '';
        }
        // return '<input type="submit" name="' . $aParams['title'] . '" value="' . $aParams['title'] . '" class="' . $sClass . '">';
        return '<button name="' . $sTitle . '" value="' . $aParams['title'] . '" class="' . $sClass . '">' . $aParams['title'] . '</button>';
    }

    private function generateText($sTitle = '', $aParams = array()) {
        $sClass = '';
        $sPlaceholder = '';
        $sAttributes = '';
        if (!empty($aParams['attributes'])) {
            $sClass = !empty($aParams['attributes']['class']) ? $aParams['attributes']['class'] : '';
            $sPlaceholder = !empty($aParams['attributes']['placeholder']) ? $aParams['attributes']['placeholder'] : '';
            if (!empty($aParams['attributes']['required'])) {
                $sAttributes .= ' required ';
            }
        }
        $sValue = !empty($aParams['value']) ? $aParams['value'] : '';
        return '<input type="text" name="' . $sTitle . '" class="' . $sClass . '" placeholder="' . $sPlaceholder . '" value="' . $sValue . '" ' . $sAttributes . '>';
    }

    private function generatePassword($sTitle = '', $aParams = array()) {
        $sClass = '';
        $sPlaceholder = '';
        $sAttributes = '';
        if (!empty($aParams['attributes'])) {
            $sClass = !empty($aParams['attributes']['class']) ? $aParams['attributes']['class'] : '';
            $sPlaceholder = !empty($aParams['attributes']['placeholder']) ? $aParams['attributes']['placeholder'] : '';
            if (!empty($aParams['attributes']['required'])) {
                $sAttributes .= ' required ';
            }
        }
        $sValue = !empty($aParams['value']) ? $aParams['value'] : '';
        return '<input type="password" name="' . $sTitle . '" class="' . $sClass . '" placeholder="' . $sPlaceholder . '" value="' . $sValue . '" ' . $sAttributes . '>';
    }

    private function generateTextarea($sTitle = '', $aParams = array()) {
        $sClass = '';
        if (!empty($aParams['attributes'])) {
            $sClass = !empty($aParams['attributes']['class']) ? $aParams['attributes']['class'] : '';
        }
        $sValue = !empty($aParams['value']) ? $aParams['value'] : '';
        return '<textarea name="' . $sTitle . '" class="' . $sClass . '">' . $sValue . '</textarea>';
    }

}
