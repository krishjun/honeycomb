<?php
class Default_Model_DbRow_User extends Zend_Db_Table_Row
{
	
	
	public function getRoles()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
	    return $this->findManyToManyRowset(new Default_Model_DbTable_Roles(),new Default_Model_DbTable_UsersRoles(),$db->select()->order('users_roles.sort_order'));
	}
	
	
	public function getStdClass()
	{
	    
	  
	    $user = $this->getStdClass();
	    $stdRoles = array();
	    $roles = $this->getRoles();
	    
	    foreach($roles as $role)
	    {
	        $stdRoles[]  = $role->getStdClass();
	    }
	    
	    $user->roles = $stdRoles;
	    return $user;
	}
}