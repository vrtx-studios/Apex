<?php

namespace Core\Navigation;

use Core\Module\clModuleBase as moduleBase;
use Core\Database as db;

class clNavigation {
    
    private $oDb;
    
    public function __construct() {
        $this->oDb = new clNavigationDao();
    }
    
    public function buildMenu( $iParentId, $iLevel, $sGroup = 'guest' ) {
        $sQuery = "SELECT
                    a.navigationId, a.navigationParentId, a.navigationName, a.navigationHref, a.navigationBehavior, a.navigationPrefixContent, Deriv1.Count
                FROM
                    `entNavigation` a
                LEFT OUTER JOIN (SELECT navigationParentId, COUNT(*) as COUNT FROM 
                    `entNavigation`
                GROUP BY navigationParentId)
                    Deriv1 ON a.navigationId = Deriv1.navigationParentId
                WHERE
                    a.navigationParentId=$iParentId
                AND
                    `navigationGroup`='$sGroup'";
        $mQuery = $this->oDb->oDao->prepare( $sQuery );
        $mQuery->execute();
        $aResult = $mQuery->fetchAll(\PDO::FETCH_ASSOC);
//        echo '<pre>';
//        var_dump( $aResult );
//        echo '</pre>';
//        return;
        if( !empty($aResult) ) {
            $sClass = '';
            if( $iLevel <= 1 ) $sClass = 'navMain';
            elseif( $iLevel >= 2 ) $sClass = 'subMenu';
            
            echo '<ul class="' . $sClass . '">';
            foreach( $aResult as $aEntry ) {
                if( $aEntry['Count'] > 0 ) {
                    echo 
                    '<li>'
                        . '<a href="' . $aEntry['navigationHref'] . '" target="' . $aEntry['navigationBehavior'] . '">'
                            . $aEntry['navigationPrefixContent']
                            . '<span class="nav-text">'
                                . $aEntry['navigationName']
                            . '</span>'
                        . '</a>';
                        $this->buildMenu($aEntry['navigationId'], $iLevel + 1);
                    echo '</li>';
                } else if( $aEntry['Count'] == 0 || empty($aEntry['Count']) ) {
                    echo 
                    '<li>'
                        . '<a href="' . $aEntry['navigationHref'] . '" target="' . $aEntry['navigationBehavior'] . '">'
                            . $aEntry['navigationPrefixContent']
                            . '<span class="nav-text">'
                                . $aEntry['navigationName']
                            . '</span>'
                        . '</a>'
                    . '</li>';
                }
            }
            echo '</ul>';
        }
    }
    
}
