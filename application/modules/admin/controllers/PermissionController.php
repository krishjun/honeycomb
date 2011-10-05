<?php
class Admin_PermissionController extends Admin_Library_Controller_Action
{
   
    const COMMAND_ALLOW = 'allow';
    const COMMAND_DENY = 'deny';
    const COMMAND_REMOVE_ALLOW = 'removeAllow';
    const COMMAND_REMOVE_DENY = 'removeDeny';
    
    public function init()
    {
       
        
        
    }
    
    public function changeAction()
    {
        
        
        $roleId = (int) $this->_getParam('role');
        $resourceId = (int) $this->_getParam('resource');
        $privilege = $this->_getParam('privilege');
        $command = $this->_getParam('command','alow');
        
        //security check to make sure current user has permission for making these permission change  
        if(!$this->acl->isOk($resourceId,$privilege)) throw new Honeycomb_Exception('You are not allowd to make such changes !!');
        
        switch($command)
        {
            case self::COMMAND_ALLOW:
               $this->acl->allow($roleId,$resourceId,$privilege);
               break;
                
        }
            
      
        //check
        
    }
}