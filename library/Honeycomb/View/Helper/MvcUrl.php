<?php
/**
 *
 * @author root
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * {1} helper
 *
 * @uses viewHelper {0}
 */
class Honeycomb_View_Helper_MvcUrl 
{

	/**
	 * @var Zend_View_Interface 
	 */
	public $view;

	/**
	 *  
	 */
	public function mvcUrl($actionControllerModule,$params = null,$route = 'default',$buildFromScratch = false) {
		// TODO Auto-generated {0}_{1}::{2}() helper
		$mvcParts = explode(':',$actionControllerModule);
		if(empty($mvcParts)) throw new Honeycomb_View_Helper_Exception("atleast action is required ");
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

class Honeycomb_View_Helper_Exception extends Zend_Exception
{
	
}
