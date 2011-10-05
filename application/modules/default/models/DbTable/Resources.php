<?php

class Default_Model_DbTable_Resources extends Zend_Db_Table_Abstract
{

    protected $_name = 'resources';
    protected $_primary = 'resource_id';
    
    protected $_referenceMap = array('modules'=>array('columns'=>'module_id',
                                                        'refColumns'=>'module_id',
                                                         'refTableClass'=>'Default_Model_DbTable_Modules'));


}

