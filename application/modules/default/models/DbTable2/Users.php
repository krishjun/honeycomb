<?php
class Default_Model_DbTable_Users extends  Zend_Db_Table
{
	protected $_name = "users";
	protected $_primary = "user_id";
	protected $_rowClass = 'Default_Model_DbRow_User';
	
	protected $_dependentTables = array('Default_Model_DbTable_UsersRoles');
	
	
}