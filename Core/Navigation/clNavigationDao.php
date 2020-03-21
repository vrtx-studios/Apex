<?php

namespace Core\Navigation;

use Core\Database as db;

class clNavigationDao extends db\clDaoBase{
    
    public function __construct() {
        parent::__construct();
        
        $this->aDataDict = array();
        $this->sPrimaryEntity = 'entNavigation';
        $this->sPrimaryField = '';
        $this->init();
    }
    
}
