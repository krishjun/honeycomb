<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected $_config;
	
public function _initHoneycombConfig()
{
	//Zend_Session::start();
    $this->_config = new Zend_Config($this->getOptions());
	Zend_Registry::set('config',$this->_config);
	return $this->_config;
}	
	
	
public function _initHoneycombCache()
{
	$cacheManager = $this->bootstrap('cachemanager')->getResource('cachemanager');
	Zend_Registry::set('cacheManager', $cacheManager);
    return $cacheManager;
}

public function _initHoneycombLayout()
{
	$layout = $this->bootstrap('layout')
	               ->getResource('layout');

   $layout->getView()->headTitle("Honeycomb");	               
	
}

public function _initFrontPlugins()
{
	$this->bootstrap('FrontController');
	//Zend_Debug::dump($_SERVER);
	//$jsCssPlugin = new Honeycomb_Controller_Plugin_JsCssAutoloader('libs');
	//Zend_Controller_Front::getInstance()->registerPlugin($jsCssPlugin);

 Zend_Controller_Action_HelperBroker::addHelper(new Honeycomb_Controller_Action_Helper_Acl());
}





	
}

