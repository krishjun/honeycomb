<?php
/**
 *
 *This class is wrapper around Zend built in view helper it provide
 *short and simple intput for building application url.
 *
 *
 * @author Uhaish Gupta
 * @version 1.0
 */
require_once 'Zend/View/Interface.php';


class Appform_View_Helper_MvcUrl 
{

	/**
	 * @var Zend_View_Interface 
	 */
	public $view;

	/**
	 * 
	 * It generates url for given input of action name , controller name and module name 
	 * support parameter and route as well 
	 * 
	 * @param String $actionControllerModule e.g 'action:controller:module'  order is important if only action is provided it takes the module and controller name from prev request object
	 * @param array $params GET parameters appended to the end
	 * @param String $route name of route to use to build this url
	 * @param boolean $buildFromScratch weather to use or not previous request object for building url 
	 * @throws Appform_View_Helper_Exception
	 */
	public function mvcUrl($actionControllerModule,$params = null,$route = 'default',$buildFromScratch = false) {
		// TODO Auto-generated {0}_{1}::{2}() helper
		$mvcParts = explode(':',$actionControllerModule);
		if(empty($mvcParts)) throw new Appform_View_Helper_Exception("atleast action is required ");
		$mvcArray = array();
		
		$mvcArray['action'] = $mvcParts[0];
		if(array_key_exists(1,$mvcParts)) $mvcArray['controller'] = $mvcParts[1];
		if(array_key_exists(2, $mvcParts))$mvcArray['module'] = $mvcParts[2];
		
		if($params) $mvcArray = array_merge($params,$mvcArray);
		
		$mvcUrl = $this->view->url($mvcArray,$route,$buildFromScratch);
		
		return $mvcUrl;
	}

	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}

/**
 * 
 *Name of exception the obve classe use
 * @author Uhaish Gupta
 *
 */

class Appform_View_Helper_Exception extends Zend_Exception
{
	
}
