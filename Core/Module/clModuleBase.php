<?php

namespace Core\Module;

/**
 * Description of clModuleBase
 *
 * @author Dronki
 */
abstract class clModuleBase {
    
    public $oDb;
    public $sModuleName;
    public $sModulePrefix;
    
    protected function initBase() {
        if( empty($this->sModuleName) ) $this->sModuleName = ucfirst( $this->sModulePrefix );
    }
    
    public function create( $aColumns = array(), $aValues = array() ) {
        $this->oDb->insert( $aColumns, $aValues );
        return $this->oDb->fetchRow();
    }
    public function read( $aParams = array() ) {
        $this->oDb->select( $aParams );
        return $this->oDb->fetchAll();
    }
    public function update( $aValues = array(), $aParams = array() ) {
        $this->oDb->update( $aValues, $aParams );
        return $this->oDb->fetchRow();
    }
    public function delete( $aParams = array() ) {
        $this->oDb->delete( $aParams );
        return $this->oDb->fetchRow();
    }
}
