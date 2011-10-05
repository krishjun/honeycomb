<?php
class TestController extends Honeycomb_Controller_Action
{
    protected $_cache;
    public function init()
    {
        $this->_cache = Zend_Registry::get('cacheManager')->getCache('database');
    }
    
    
	public function indexAction()
	{

         if(!isset($this->getSession()->count))
         {
             echo 'session count getting set for the first time';
             $this->getSession()->count = 0;
         }
         
         $this->getSession()->count = $this->getSession()->count + 1;
         echo 'current count ' . $this->getSession()->count;
	    
	}
	

}