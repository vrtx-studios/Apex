<?php

namespace Core\Router;

use Core\Database as db;

/**
 * Description of clRouterDao
 *
 * @author Dronki
 */
class clRouterDao extends db\clDaoBase {
    
    public function __construct() {
        parent::__construct();
        
        $this->aDataDict = array(
            'entRoute' => array(
                'routeId' => array(
                    'type' => 'integer',
                    'autoincrement' => true,
                    'primary' => true,
                    'title' => _( 'ID' )
                ),
                'routePath' => array(
                    'type' => 'string',
                    'title' => _( 'Path' )
                ),
                'routeLangId' => array(
                    'type' => 'integer',
                    'title' => _( 'Language-ID' )
                ),
                'routeLayoutKey' => array(
                    'type' => 'string',
                    'title' => _( 'Layout-key' )
                ),
                'routeCreated' => array(
                    'type' => 'datetime',
                    'title' => _( 'Created' )
                ),
                'routeUpdated' => array(
                    'type' => 'datetime',
                    'title' => _( 'Updated' )
                )
            ),
            'entRouteToObject' => array(
                'routeId' => array(
                    'type' => 'integer',
                    'title' => _( 'Route-ID' )
                ),
                'objectParent' => array(
                    'type' => 'string',
                    'title' => _( 'Parent-object')
                ),
                'objectId' => array(
                    'type' => 'integer',
                    'title' => _( 'Object-ID' )
                )
            )
        );
        
        $this->sPrimaryEntity = 'entRoute';
        $this->sPrimaryField = '*';
        
        $this->init();
    }
    
    public function readObjectByRoute( $sRoute = "", $sParent = "" ) {
        $sQuery = "SELECT objectId FROM `entRouteToObject` o LEFT JOIN `entRoute` r ON o.routeId = r.routeId WHERE r.routePath=" . $this->oDao->escapeStr( $sRoute ) . " AND o.objectParent=" . $this->oDao->escapeStr( $sParent );
        $oQuery = $this->oDao->prepare( $sQuery );
        $oQuery->execute();
        $aResult = $oQuery->fetchAll(\PDO::FETCH_ASSOC);
        return $aResult;
    }
    
}
