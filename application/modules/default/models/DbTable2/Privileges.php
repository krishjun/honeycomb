<?php
class Default_Model_DbTable_Privileges extends Zend_Db_Table
{
	protected $_name = 'privileges';
	protected $_primary = 'privilege_id';
	
	protected $_referenceMap = array('roles'=> array('columns'=>'role_id',
	                                                   'refColumns'=>'role_id',
	                                                    'refTableClass'=>'Default_Model_DbTable_Roles'));
	
}