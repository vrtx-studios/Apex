<?php

namespace Core\User;

use Core\Database as db;

class clUserDao extends db\clDaoBase {
    
    public function __construct() {
        parent::__construct();
        
        $this->aDataDict = array(
            'entUser' => array(
                'userId' => array(
                    'type' => 'integer',
                    'autoincrement' => true,
                    'primary' => true,
                    'title' => _( 'ID' )
                ),
                'userName' => array(
                    'type' => 'string',
                    'required' => true,
                    'title' => _( 'Username' )
                ),
                'userPass' => array(
                    'type' => 'string',
                    'required' => true,
                    'title' => _( 'Password' )
                ),
                'userEmail' => array(
                    'type' => 'string',
                    'required' => 'true',
                    'title' => _( 'E-mail' )
                ),
                'userOrigin' => array(
                    'type' => 'array',
                    'values' => array(
                        'onSite' => _( 'From this site' ),
                        'offSite' => _( 'Originated from another source' ),
                        'google' => _( 'Created using Google' )
                    ),
                    'title' => _( 'Origin of the account' )
                ),
                'userLastLogin' => array(
                    'type' => 'datetime',
                    'title' => _( 'Last login' )
                ),
                'userLastLoginIP' => array(
                    'type' => 'string',
                    'title' => _( 'Last IP seen for this user' )
                ),
                'userCreated' => array(
                    'type' => 'datetime',
                    'title' => _( 'Created' )
                ),
                'userUpdated' => array(
                    'type' => 'datetime',
                    'title' => _( 'Updated' )
                )
            ),
            'entUserGroups' => array(
                'groupId' => array(
                    'type' => 'integer',
                    'autoincrement' => true,
                    'required' => true,
                    'title' => _( 'Group-ID' )
                ),
                'groupName' => array(
                    'type' => 'string',
                    'title' => _( 'Group-name' )
                ),
                'groupDescription' => array(
                    'type' => 'text',
                    'title' => _( 'Group-description' )
                ),
                'groupCreated' => array(
                    'type' => 'datetime',
                    'title' => _( 'Created' )
                ),
                'groupUpdated' => array(
                    'type' => 'datetime',
                    'title' => _( 'Updated' )
                )
            ),
            'entUserToGroup' => array(
                'userId' => array(
                    'type' => 'integer'
                ),
                'groupId' => array(
                    'type' => 'integer'
                )
            )
        );
        $this->sPrimaryEntity = 'entUser';
        $this->sPrimaryField = 'userId';
        
        $this->init();
    }
    
    public function readGroupByUser( $iUserId = 0, $bIncludegroupName = true ) {
        $this->setEntity( 'entUserToGroup' );
        $this->select(array(
            'userId' => $iUserId
        ), 'groupId' );
        $aData = $this->fetchRow();
        if( !$bIncludegroupName ) {
            return $aData;
        } else {
            $aReturnData = array(
                'userGroup' => $aData['groupId']
            );
            $this->setEntity( 'entUserGroups' );
            $this->select( array(
                'groupId' => $aData['groupId']
            ), 'groupName' );
            $aData = $this->fetchRow();
            $aReturnData['groupName'] = $aData['groupName'];
            return $aReturnData;
        }
        
    }
    
}
