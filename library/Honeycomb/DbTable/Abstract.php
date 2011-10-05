<?php
class Honeycomb_DbTable_Abstract extends  Zend_Db_Table_Abstract
{
 
	
	public function listData($data,$page,$rowCount,$sortBy,$sortType) {
        
		
		return $this->select()->from($this,$data)
                               ->limitPage($page, $rowCount)
                               ->order($sortBy . ' ' . $sortType)
                               ->query()->fetchAll();
    }
    
    public function getTableName()
    {
    	return $this->_name;
    }
    
    public function addRefCol($tableClass,$columnName)
    {
    	$className = get_class($tableClass);
    	$rule = substr($className,0,-3);
    	$this->_referenceMap[$rule] = array('columns'=>$columnName,
    	                                     'refColumns'=>$columnName,
    	                                     'refTableClass'=>$className);
    	return $this;
    }
}