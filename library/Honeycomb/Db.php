<?php
class Honeycomb_Db
{
	/**
	 * Maps table name to the Zend_Db_Table instance and returns it
	 * 
	 * @param String $tableName name of the table
	 * @return Zend_Db_Table 
	 */
	
	
	
	public static function getTable($tableName)
	{
		$mapping = self::getMapping();
		
		if(!array_key_exists($tableName, $mapping)) 
		   throw new Honeycomb_Exception("Mapping for table name \" $tableName \" does not exist in \"tableMapper.xml\"");
		
		$className =  $mapping[$tableName];
		return new $className;
		
		
	}
	
	private static function getMapping()
	{
		$cache = Zend_Registry::get('cacheManager')->getCache('database');
		
		if(($mapping = $cache->load('Honeycomb_Db_mapping')) === false)
		{
		$pathToConfig = APPLICATION_PATH . '/configs/tableMapper.xml';
		$config  =  new Zend_Config_Xml($pathToConfig,'database');
		$mapping = $config->tables->toArray();
		}
		
		return $mapping;
	}
}