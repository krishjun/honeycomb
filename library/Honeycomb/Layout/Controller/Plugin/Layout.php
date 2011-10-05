<?php
class Honeycomb_Layout_Controller_Plugin_Layout extends Zend_Layout_Controller_Plugin_Layout
{
     protected $_config;
     
     
	
	public function preDispatch(Zend_Controller_Request_Http $request)
	{
	
	 
		$this->_config = Zend_Registry::get('config');	
	
		$this->initJquery($request);
		
		
		/*$navConfig = new Zend_Config_Xml(Zend_Controller_Front::getInstance()->getModuleDirectory($request->getModuleName()) .'/configs/nav.xml','nav' );
		$this->getLayout()->getView()->nav = new Zend_Navigation($navConfig);*/
		
	/*	
	 * Idea for layout per module 
	 * 
	 * if($request->isXmlHttpRequest())
		{
		     
			$this->getLayout()->setLayoutPath(Zend_Controller_Front::getInstance()->getModuleDirectory($request->getModuleName()) . '/layouts');
			$this->getLayout()->setLayout("layout");
			//$this->setLayout("layout.phtml",TRUE);
		}
		*/
		$jsDir = $this->_config->settings->lib->javascript->dir;
		$jsScripts = $this->_config->settings->lib->javascript->scripts;
		
		$view = $this->_layout->getView();
		if(count($jsScripts) > 0)
		{
		   foreach($jsScripts as $script)
		  {
		   $view->headScript()->appendFile($view->baseUrl($jsDir . '/' . $script));
 		  }
		}
 		
		
 		$cssDir = $this->_config->settings->lib->styles->dir;
 		$cssStyles = $this->_config->settings->lib->styles->css;
 		
 		if(count($cssStyles) > 0)
 		{
 		
 		  foreach($cssStyles as $style)
 		 {
 			$view->headLink()->appendStylesheet($view->baseUrl($cssDir . '/' . $style));
 			
 		  }
 		}
		
	}
	

	
	private function initJquery(Zend_Controller_Request_Http $request)
	{
		
	    $baseUrl = $request->getBaseUrl() . '/';
		$view = $this->getLayout()->getView();
	    ZendX_JQuery::enableView($view);
	    $view->jQuery()
	         ->addStylesheet($baseUrl . $this->_config->settings->jQuery->css)
	         ->setLocalPath($baseUrl .  $this->_config->settings->jQuery->core)
	         ->setUiLocalPath($baseUrl . $this->_config->settings->jQuery->ui)
	         ->uiEnable();
	         
	
	        
	}
	

	
}