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
            )
        );
        
        $this->sPrimaryEntity = 'entRoute';
        $this->sPrimaryField = '*';
        
        $this->init();
        
    }
    
}
