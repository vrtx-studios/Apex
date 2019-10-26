<?php
namespace Core\Locale;

use Core\Database as db;
/**
 * Description of clLocaleDao
 *
 * @author Dronki
 */
class clLocaleDao extends db\clDaoBase {
    
    public function __construct() {
        parent::__construct();
        
        $this->aDataDict = array(
            'entLocale' => array(
                'localeId' => array(
                    'type' => 'integer',
                    'title' => _( 'ID' )
                ),
                'localeDomain' => array(
                    'type' => 'string',
                    'title' => _( 'Domain' )
                ),
                'localePath' => array(
                    'type' => 'string',
                    'title' => _( 'Path' )
                ),
                'localeCreated' => array(
                    'type' => 'datetime',
                    'title' => _( 'Created' )
                ),
                'localeUpdated' => array(
                    'type' => 'datetime',
                    'title' => _( 'Updated' )
                )
            )
        );
        
        $this->sPrimaryEntity = 'entLocale';
        $this->sPrimaryField = '*';
        
        $this->init();
        
    }
    
}
