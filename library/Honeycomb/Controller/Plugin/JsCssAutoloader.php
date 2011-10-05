<?php
class Honeycomb_Controller_Plugin_JsCssAutoloader extends  Zend_Controller_Plugin_Abstract
{
	
	protected $_path;

	
	/**
	 * 
	 * Loads all your css and js files in headScript , headLinks view helpers
	 * @param unknown_type $path
	 */
	
	public function __construct($dir = '')
	{
		$this->_path = $dir;
	}
	
	public function preDispatch($request)
	{
	
		$baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
/*		
		if($this->_path == null) 
		        $this->_path = $baseUrl;
		    else 
		       $this->_path = $baseUrl . '/' . $this->_path;*/
		                 
		require_once 'My/JsCssAutoloader.php';
		$loader = new JsCssAutoloader($this->_path,$baseUrl,true); //third parameter is weather referesh or not
		
	}
}