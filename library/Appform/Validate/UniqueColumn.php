<?php
class Appform_Validate_UniqueColumn extends Zend_Validate_Abstract
{
	const VALUE_EXIST = "valueExistInDataseReuqestedColumn";
	
	protected $_messageTemplates;
	protected $_dbTable;
	protected $_columnName;
	
	
	public function __construct(Zend_Db_Table_Abstract $dbTable,$columnName)
	{
		$this->_messageTemplates = array(self::VALUE_EXIST => "Type with '%value%' already exist ");
		
		/*if(!($this->_dbTable instanceof Zend_Db_Table_Abstract)) {
		 throw new Exception("Please provide instance of DbTable to UnqiueColumn Validator Constructor ");	
		}*/
		
		
		$this->_dbTable = $dbTable;
		$this->_columnName = $columnName;
	}
	
	public function isValid($value)
	{
		
		$this->_setValue($value);
	    $this->_error(self::VALUE_EXIST); //important to call
		return count($this->_dbTable->select()->from($this->_dbTable)->where("{$this->_columnName} = ?",$value)->query()->fetchAll()) == 0;
		
		
		
	}
	

	
}