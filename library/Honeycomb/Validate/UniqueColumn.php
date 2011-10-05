<?php
class Honeycomb_Validate_UniqueColumn extends Zend_Validate_Abstract
{
	const VALUE_EXIST = "valueExistInDataseReuqestedColumn";
	
	protected $_messageTemplates;
	protected $_select;
	protected $_columnName;
	
	/**
	 * 
	 * Enter description here ...
	 * @param String | Zend_Db_Table_Abstract $table
	 * @param String $columnName
	 */
	public function __construct($table,$columnName)
	{
		if($table instanceof Zend_Db_Table_Abstract)
		   $table = $table->getTableName();
		$this->_messageTemplates = array(self::VALUE_EXIST => "Type with '%value%' already exist ");
		//$this->_db = Zend_Db_Table::getDefaultAdapter();
		$this->_select = Zend_Db_Table::getDefaultAdapter()->select()->from($table,$columnName);
		/*if(!($this->_dbTable instanceof Zend_Db_Table_Abstract)) {
		 throw new Exception("Please provide instance of DbTable to UnqiueColumn Validator Constructor ");	
		}*/
		
		$this->_columnName = $columnName;
	}
	
	public function isValid($value)
	{
		
		$this->_setValue($value);
	    $this->_error(self::VALUE_EXIST); //important to call
		return count($this->_select->where("{$this->_columnName} = ?",$value)->query()->fetchAll()) === 0;
		
		
		
	}
	

	
}