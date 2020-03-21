<?php

namespace Core\Render;

class clTable {

    private $aAttributes = array();
    private $aTableHeaders = array();
    private $aTableContent = array();
    
    public function __construct( $aAttributes = array() ) {
        $this->aAttributes = $aAttributes;
    }
    
    public function setHeaders( $aTableHeaders = array() ) {
        $this->aTableHeaders = $aTableHeaders;
    }
    
    public function addRow( $aRow = array() ) {
        $this->aTableContent[] = $aRow;
    }
    
    public function render() {
        $sClass = ( !empty($this->aAttributes['class']) ? $this->aAttributes['class'] : '' );
        $sTableRows = '';
        
        $sTable = '<table class="' . $sClass . '">';
        $sTableHeaders = '<thead><tr>';
        foreach( $this->aTableHeaders as $aEntry ) {
            $sTableHeaders .= '<th>' . $aEntry . '</th>';
        }
        $sTableHeaders .= '</tr></thead>';
        
        $sTableBody = '<tbody>';
        foreach( $this->aTableContent as $aEntry ) {
            $sTableRows .= '<tr>';
            foreach( $aEntry as $sEntry ) {
                $sTableRows .= '<td>' . $sEntry . '</td>';
            }
            $sTableRows .= '</tr>';
        }
        $sTableBody .= $sTableRows . '</tbody>';
        
        $sTable .= $sTableHeaders . $sTableBody . '</table>';
        return $sTable;
    }
    
}
