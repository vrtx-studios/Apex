<?php

namespace Core\Database;

/**
 * Description of clDaoBase
 *
 * @author Dronki
 */
abstract class clDaoBase {
    
    const ASSOC = \PDO::FETCH_ASSOC;
    const BOTH = \PDO::FETCH_BOTH;
    const BOUND = \PDO::FETCH_BOUND;
    const LAZY = \PDO::FETCH_LAZY;
    const NAMED = \PDO::FETCH_NAMED;
    const NUM = \PDO::FETCH_NUM;
    const OBJ = \PDO::FETCH_OBJ;
    const COLUMN = \PDO::FETCH_COLUMN;
    
    public $oDao = null;
    private $bConnected = false;
    private $sType, $sConnection, $sQuery;
    private $mFetchType = self::ASSOC;
    
    public $sPrimaryEntity, $sPrimaryField;
    public $aDataDict = array();
    
    public function __construct() {
        $this->getConnectionDetails();
    }
    
    public function init() {
        if( empty($this->sPrimaryEntity ) ) $this->sPrimaryEntity = key( $this->aDataDict );
        $this->connect();
    }
    
    /**
     * Switches the current entity to a different entity
     * 
     * @param string $sEntity The entity to switch to.
     * 
     * @return array Returns an array containing the old entity and the new entity.
     */
    public function setEntity( $sEntity = '' ) {
        $aData = array(
            'old_entity' => $this->sPrimaryEntity,
            'new_entity' => $sEntity
        );
        $this->sPrimaryEntity = $sEntity;
        return $aData;
    }
    
    public function getEntity() {
        return $this->sPrimaryEntity;
    }
    
    public function connect() {
        if( $this->bConnected === false ) {
            try {
                $aOptions = array(
                  \PDO::ATTR_PERSISTENT => true,
                  \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                );
                
                $this->oDao = new \PDO($this->sConnection,
                                        DB_USER,
                                        DB_PASS,
                                        $aOptions);
                $this->bConnected = true;
                return $this->bConnected;
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
        return true;
    }
    
    protected function getConnectionDetails() {
        switch(DB_ENGINE) {
            case 'postgresql':
                $this->sConnection = "pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME;
                break;
            case 'sqlite':
                $this->sConnection = "sqlite:" . DB_PATH;
                break;
            default:
                $this->sConnection = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
                break;
        }
        
    }
    
    public function disconnect() {
        if( $this->bConnected ) {
            unset( $this->oDao );
            $this->bConnected = false;
            return true;
        }
        return false;
    }
    
    public function beginTransaction() {
        $this->sQuery( "START TRANSACTION;" );
        $this->sQuery->execute();
    }
    public function endTransaction() {
        $this->sQuery( "COMMIT;" );
        $this->sQuery->execute();
    }
    public function cancelTransaction() {
        $this->sQuery( "ROLLBACK;" );
        $this->sQuery->execute();
    }
    
    public function setFetchType( $mType ) {
        $this->mFetchType = $mType;
    }
    
    public function select( $aParams = null, $mEntity = '*' ) {
        $sQuery = $this->prepareNamedParams( $aParams );
        if( $sQuery ) {
            $sSql = ( 'SELECT ' . $mEntity . ' FROM ' . $this->sPrimaryEntity . ' WHERE ' . $sQuery );
            $this->sQuery = $this->oDao->prepare( $sSql );
            foreach( $aParams as $sName => $mValue ) {
                $this->prepareBind( $sName, $mValue );
            }
        } else {
            $sSql = ( 'SELECT ' . $mEntity . ' FROM ' . $this->sPrimaryEntity );
            $this->sQuery = $this->oDao->prepare( $sSql );
        }
        

        $this->sQuery->execute();
    }
    
    public function insert($aColumns = array(), $aValues = array() ) {
        $sColumns = '(' . implode(',', $aColumns) . ')';
        $aParams = $this->prepareValues($aValues);
        
        $sSql = ('INSERT INTO ' . $this->sPrimaryEntity . '' . $sColumns . ' VALUES ' . $aParams );
        $this->sQuery = $this->oDao->prepare( $sSql );
        
        foreach( $aValues as $sParam => $mValue ) {
            var_dump( $sParam, $mValue );
            $this->prepareBind($sParam, $mValue);
        }
        $this->sQuery->execute();
        return $this->sQuery->rowCount();
    }
    
    public function update( $aValues = array(), $aParams = array() ) {
        $sParams = $this->prepareNamedParams($aValues);
        $sWhere = $this->prepareNamedParams($aParams);
        
        $sSql = ('UPDATE ' . $this->sPrimaryEntity . ' SET ' . $sParams . ' WHERE ' . $sWhere);
        $sSql = str_replace( "AND" , ",", $sSql );
        $this->sQuery = $this->oDao->prepare( $sSql );
        
        foreach( $aParams as $sName => $mValue ) {
            $this->prepareBind($sName, $mValue);
        }
        
        foreach( $aValues as $sName => $mValue ) {
            $this->prepareBind($sName, $mValue);
        }
        
        $this->sQuery->execute();
        return $this->sQuery->rowCount();
    }
    
    public function delete( $aParams = array() ) {
        $sWhere = $this->prepareNamedParams($aParams);
        
        $sSql = ('DELETE FROM ' . $this->sPrimaryEntity . ' WHERE ' . $sWhere);
        $this->sQuery = $this->oDao->prepare( $sSql );
        
        foreach( $aParams as $sName => $mValue ) {
            $this->prepareBind($sName, $mValue);
        }
        $this->sQuery->execute();
        return $this->sQuery->rowCount();
    }
    
    public function fetchAll() {
        $mResult = $this->sQuery->fetchAll($this->mFetchType);
        return $mResult;
    }
    
    public function fetchRow() {
        $mResult = $this->sQuery->fetch($this->mFetchType);
        return $mResult;
    }
    
    public function fetchAssoc() {
        $aData = array();
        while( $aRow = $this->sQuery->fetch(self::ASSOC) ) {
            $tmp = array_values(array_slice($aRow, 0, 1));
            $aData[$tmp[0]] = $aRow;
        }
        return $aData;
    }
    
    public function fetchCol( $iIndex ) {
        $mResult = $this->sQuery->fetchAll(self::COLUMN, $iIndex);
        return $mResult;
    }
    
    public function fetchPairs() {
        $aData = array();
        while( $aRow = $this->sQuery->fetch(self::NUM) ) {
            $aData[$aRow[0]] = $aRow[1];
        }
        return $aData;
    }
    
    public function fetchOne() {
        $mResult = $this->sQuery->fetchColumn(0);
        return $mResult;
    }
    
    public function escapeStr( $sStr ) {
        return $this->oDao->quote( $sStr );
    }
    
    protected function prepareNamedParams( $aParams ) {
        if( empty($aParams) ) return false;
        
        $_aParams = array();
        foreach( $aParams as $sName => $mValue ) {
            $_aParams[] = $sName . ' = ' . $mValue;
        }
        return implode( ' AND ', $_aParams );
    }
    
    protected function prepareBind( $sName, $mValue, $mType = null ) {
        if(is_null($mType) ) {
            switch(true) {
                case is_int($mValue):
                    $mType = \PDO::PARAM_INT;
                    break;
                case is_bool($mValue):
                    $mType = \PDO::PARAM_BOOL;
                    break;
                case is_null($mValue):
                    $mType = \PDO::PARAM_NULL;
                    break;
                default:
                    $mType = \PDO::PARAM_STR;
            }
        }
        $this->sQuery->bindValue( $sName, $mValue, $mType );
    }
    
    protected function prepareValues( $aValues ) {
        $aParams = array();
        foreach( $aValues as $sKey => $mValue ) {
            $aParams[] = ':' . $sKey;
        }
        return '(' . implode( ',', $aParams ) . ')';
    }
    
}
