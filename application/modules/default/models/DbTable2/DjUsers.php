<?php
class Default_Model_DbTable_DjUsers extends  Zend_Db_Table_Abstract
{
	protected $_name = 'ibf_members';
	protected $_primary = 'id';
	
	public function getSimilarTo($term)
	{
		$select = $this->select()->from($this,'name')
		                         ->where('name LIKE ? ' ,  $term . '%')
		                         ->order('name');
  //die($select->__toString());		                         
	 $rows = $this->fetchAll($select)->toArray();
	 $ret = array();
	 foreach ($rows as $row)
	 {
	 	$ret[] = $row['name'];
	 }
	 return  $ret;
	 	                         
	}
}