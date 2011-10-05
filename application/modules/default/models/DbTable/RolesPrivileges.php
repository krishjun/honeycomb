<?php

class Default_Model_DbTable_RolesPrivileges extends Zend_Db_Table_Abstract
{

    protected $_name = 'roles_privileges';
    protected $_primary = array('role_id','privilege_id');
    
    protected $_referenceMap = array('roles'=> array('columns'=>'role_id',
                                                      'refTableClass'=>'Default_Model_DbTable_Roles',
                                                      'refColumns'=>'role_id'),
                                         
                                      'privileges'=>array('columns'=>'privilege_id',
                                                          'refTableClass'=>'Default_Model_DbTable_Privileges',
                                                          'refColumns'=>'privilege_id')
                                          );
                                          
                                          

                                          

}

