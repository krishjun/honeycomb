<?php

class Default_Model_DbTable_Privileges extends Zend_Db_Table_Abstract
{

    protected $_name = 'privileges';
    protected $_primary = 'privilege_id';
    
    protected $_referenceMap = array('resources'=>array('columns'=>'resource_id',
                                                          'refColumns'=>'resource_id',
                                                          'refTableClass'=>'Default_Model_DbTable_Resources'));
    

}

