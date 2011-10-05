<?php
class Admin_SearchController extends Zend_Controller_Action
{
	protected $_config;
	protected $_storage;
	protected $_dbUsersTb;
	
	public function init()
	{
		$this->_config = Zend_Registry::get('config');
		$this->_dbUsersTb = new Default_Model_DbTable_DjUsers();
	}
	
	public function indexAction()
	{
	  $this->_storage = Zend_Search_Lucene::create($this->_config->settings->lucene->index);
	  
	  $members = $this->_dbUsersTb->select()->limit(1000)->query()->fetchAll();
	  
	  foreach($members as $member)
	  {
	  
	  $row = new Zend_Search_Lucene_Document();
	  $row->addField(Zend_Search_Lucene_Field::keyword('name', $member['name']));
	  $row->addField(Zend_Search_Lucene_Field::keyword('email',$member['email']));
	  $this->_storage->addDocument($row);
	  }
	  
	  die('index completed');
	}
	
	public function configAction()
	{
		$file = APPLICATION_PATH . '/configs/internal.ini';
		$config = new Zend_Config_Ini($file,null,array('skipExtends'=>true,'allowModifications'=>true));
		$config->main->lucene->lastRun = time();
		
		$configWriter = new Zend_Config_Writer_Ini();
		$configWriter->setFilename($file)
		            ->setConfig($config)
		            ->write();
		            
		die('config write completed');            
		
	}
	
	
	
	
	
	
}