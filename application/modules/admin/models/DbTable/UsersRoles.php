<?php
class Admin_Model_DbTable_UsersRoles extends Zend_Db_Table
{
	protected $_primary = array('user_id','role_id');
	protected $_name = 'users_roles';
	
	
	
}