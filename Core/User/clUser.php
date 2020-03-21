<?php

namespace Core\User;

use Core\Module\clModuleBase as moduleBase;

class clUser extends moduleBase {
    
    public function __construct() {
        $this->sModuleName = "User";
        $this->sModulePrefix = "user";
        
        $this->oDb = new clUserDao();
        
        $this->initBase();
    }
    
    public function login( $sUsername, $sUserPass ) {
        $sUsername = $this->oDb->escapeStr( $sUsername );
        $aUserData = parent::read( array(
            'userName' => $sUsername
        ) );
        if( !empty( $aUserData) ) $aUserData = current( $aUserData );
        $sHash = $aUserData['userPass'];
        if( password_verify($sUserPass, $sHash) ) {
            return $this->loginUser( $aUserData );
        } else {
            return false;
        }
    }
    
    public function loginUser( $aUserData = array() ) {
        if( empty($aUserData) ) return false;
        
        $aUserGroupData = $this->oDb->readGroupByUser( $aUserData['userId'] );
        $_SESSION['user'] = array(
            'userId' => $aUserData['userId'],
            'groupId' => $aUserGroupData['userGroup'],
            'groupName' => $aUserGroupData['groupName'],
            'username' => $aUserData['userName'],
            'authenticated' => true
        );
        
        $this->oDb->setEntity( 'entUser' );
        parent::update(array(
            'userLastLogin' => $this->oDb->escapeStr( date( 'Y-m-d H:i:s' ) ),
            'userLastLoginIP' => ip2long($_SERVER['REMOTE_ADDR'])
        ), array(
            'userName' => $this->oDb->escapeStr($aUserData['userName'])
        ) );
        return true;
    }
    
    public function createUser( $aUserData = array(), $sOrigin = "onSite" ) {
        if( empty($aUserData) ) return false;
        
        if( $sOrigin == "onSite" ) {
            $sPasswordHash = password_hash( $aUserData['password'], USER_ALGORITHM );
            $this->oDb->setEntity( 'entUser' );
            parent::create( array(
                // Columns
                'userName',
                'userPass',
                'userEmail',
                'userOrigin',
                'userCreated'
            ), array(
                // Values
                'userName' => $aUserData['userName'],
                'userPass' => $sPasswordHash,
                'userEmail' => $aUserData['userEmail'],
                'userOrigin' => 'onSite',
                'userCreated'=> date('Y-m-d H:i:s')
            ) );
            return true;
        }
        
    }
    
}
