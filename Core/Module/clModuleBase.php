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
    
    public $sLocaleDomain = "",
            $sLocalePath = "";
    
    public function install() {
        $bTableExists = false;
        $sQuery = "SELECT * 
        FROM information_schema.tables
        WHERE table_schema = '" . DB_NAME . "' 
            AND table_name = '" . $this->oDb->sPrimaryEntity ."'
        LIMIT 1;";
        $mQuery = $this->oDb->oDao->prepare( $sQuery );
        $mQuery->execute();
        if( empty($mQuery->fetch()) ) {
            // Install the table into the database.
            require_once(PATH_FUNCTIONS . 'fDevelopment.php');
            $sTableSql = datadict2sql($this->oDb->aDataDict);
            $mInstall = $this->oDb->oDao->prepare( $sTableSql );
            $mInstall->execute();
        }
    }
    
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
