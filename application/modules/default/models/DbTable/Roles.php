<?php

class Default_Model_DbTable_Roles extends Zend_Db_Table_Abstract
{

    protected $_name = 'roles';
    protected $_primary = 'role_id';

    
    public function count()
    {
       return $this->select()->from($this,'COUNT(*)')->query()->fetchColumn();
       
    }
    
    public function listData($page,$rowCount,$sortBy,$sortType)
    {
        return $this->select()->from($this,array('role_id','name','is_default'))
                               ->where('is_global = true')
                               ->limitPage($page, $rowCount)
                               ->order($sortBy . ' ' . $sortType)->query()->fetchAll();
    }
    
    

}

