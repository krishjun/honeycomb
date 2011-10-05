<?php
class AjaxController extends  Zend_Controller_Action
{
	
	private static $_data = array('foo','bar','uhaish','manko','chanko','umanko','anil','rajesh');
	private $_dbMembersTb;
	private $_config;
	
	public function init()
	{
	    //ZendX_JQuery::enableView($this->view);
	    $this->_dbMembersTb = new Default_Model_DbTable_DjUsers();
	    $this->_config = Zend_Registry::get('config');
	}
	
	
	public function autocompleteAction()
	{
		
		
	  $ac = new ZendX_JQuery_Form_Element_AutoComplete('ac');
      $ac->setJQueryParam('source',$this->_helper->url('lucene'));
      $this->view->acElement = $ac;
		
	}
	
	/**
	 * 
	 * This action helps autocomple jquery ui widget to query for data
	 */
	
	public function namesAction()
	{
		
		$term = $this->_getParam('term','uhaish');
		
	$filter = 	function ($value) use ($term)
		     {
			if(stristr($value,$term)) return true;
			return false;
		    };
		
		$data = array_filter(self::$_data,$filter);
		$this->_helper->json(array_values($data));
		
	}
	
	public function databaseAction()
	{
		
      $data = $this->_dbMembersTb->getSimilarTo($this->_getParam('term','a'));
      $this->_helper->json($data);
	
	}
	
	
	public function luceneAction()
	{
		$storage = Zend_Search_Lucene::open($this->_config->settings->lucene->index);
		Zend_Search_Lucene::setDefaultSearchField('name');
		$term = $this->_getParam('term','uhaish');
		$query = $term . '*';
		$query = Zend_Search_Lucene_Search_QueryParser::parse($query);
		$hits = $storage->find($query);
		die($term); 
		
		
	   $data = array();
	   foreach($hits as $hit)
	   {
	   	$data[] = $hit->name;
	   }
	   
	   $this->_helper->json($data);
	}
	

}