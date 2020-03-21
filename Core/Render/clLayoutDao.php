<?php

namespace Core\Render;

use Core\Database as db;

class clLayoutDao extends db\clDaoBase {
    public function __construct() {
        parent::__construct();
        
        $this->aDataDict = array(
            'entLayout' => array(
                'layoutId' => array(
                    'type' => 'integer',
                    'autoincrement' => true
                ),
                'layoutKey' => array(
                    'type' => 'string',
                    'required' => 'true',
                    'title' => _( 'Key' )
                ),
                'layoutTitle' => array(
                    'type' => 'string',
                    'required' => true,
                    'title' => _( 'Title' )
                ),
                // If the layout uses a template from a module
                'layoutModuleTemplate' => array(
                    'type' => 'string',
                    'title' => _( 'Template from module' )
                ),
                // Or if the layout uses a template from the base-installation
                'layoutTemplate' => array(
                    'type' => 'string',
                    'title' => _( 'Template' )
                ),
                'layoutCreated' => array(
                    'type' => 'datetime',
                    'title' => _( 'Created' )
                ),
                'layoutUpdated' => array(
                    'type' => 'datetime',
                    'title' => _( 'Updated' )
                )
            ),
            'entViewToLayout' => array(
                'layoutId' => array(
                    'type' => 'integer',
                    'required' => true
                ),
                'viewModule' => array(
                    'type' => 'string',
                    'required' => true,
                    'title' => _( 'Module' )
                ),
                'viewCallback' => array(
                    'type' => 'array',
                    'values' => array(
                        'no' => _( 'No' ),
                        'yes' => _( 'Yes' )
                    ),
                    'title' => _( 'Callback' )
                ),
                'viewFile' => array(
                    'type' => 'string',
                    'title' => _( 'View-file' ),
                    'required' => true
                ),
                'viewOrder' => array(
                    'type' => 'integer',
                    'title' => _( 'Order' )
                ),
                'relationCreated' => array(
                    'type' => 'datetime'
                ),
                'relationUpdated' => array(
                    'type' => 'datetime'
                )
            )
        );
        
        $this->sPrimaryEntity = 'entLayout';
        $this->sPrimaryField = 'layoutKey';
        
        $this->init();   
    }
    
    public function readViewsByLayout( $iLayoutId = 0 ) {
        if( empty($iLayoutId) || !ctype_digit( $iLayoutId ) ) return;
        $sQuery = "SELECT * FROM `entViewToLayout` WHERE layoutId=$iLayoutId ORDER BY `viewOrder` DESC";
        $oQuery = $this->oDao->prepare( $sQuery );
        $oQuery->execute();
        $aResult = $oQuery->fetchAll(\PDO::FETCH_ASSOC);
        return $aResult;
    }
    
}
