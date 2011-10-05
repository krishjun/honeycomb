<?php

class Default_Model_DbTable_UsersRoles extends Zend_Db_Table_Abstract
{

    protected $_name = 'users_roles';
    protected $_primary = array('user_id','role_id');
    
    protected $_refernceMap = array('users'=>array('columns'=>'user_id',
                                                     'refColumns'=>'user_id',
                                                     'refTableClass'=>'Default_Model_DbTable_Users'),
                                      'roles'=>array('columns'=>'rule_id',
                                                      'refColumns'=>'role_id',
                                                      'refTableClass'=>'Default_Model_DbTable_Roles'));
   


}

