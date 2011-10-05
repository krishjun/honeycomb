<?php

/**
 * 
 * Roles id would be the primary key of roles table since roles are not typed coz they can be loaded
 * automatically on the bases of user id .
 * @author root
 *
 */

class Honeycomb_Acl_Role extends Zend_Acl_Role
{
	
	
	protected $_name;
	protected $_isUserBased;
	protected $_isGlobal;
	
	public function __construct($role)
	{
		if($role instanceof Zend_Db_Table_Row)
		{
		    $this->_roleId = $role->role_id;
		    $this->_name = $role->name;
		    $this->_isUserBased = $role->is_user_based;
		    $this->_isGlobal = $role->is_global;
		}else {
		    $this->_roleId = $role;
		}
	    parent::__construct($this->_roleId);
	}
	
	public function getName()
	{
	    return $this->_name;
	}
}