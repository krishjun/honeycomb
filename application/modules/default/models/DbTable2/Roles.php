<?php

class Default_Model_DbTable_Roles extends Zend_Db_Table
{
	protected $_name = 'roles';
	protected $_primary = 'role_id';
	
	protected $_dependentTables = array('Default_Model_DbTable_Privileges','Default_Model_DbTable_UsersRoles');
	
	const C_ROLE_ID = "role_id";
	const C_NAME = "name";
	const C_CREATED_BY_USER_ID = "created_by_user_id";
	const C_TIME_CREATED_ON = "time_created_on";
	const C_IS_USER_BASED = "is_user_based";
	
	
}