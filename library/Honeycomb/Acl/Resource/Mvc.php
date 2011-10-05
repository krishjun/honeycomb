<?php
class Honeycomb_Acl_Resource_Mvc extends Zend_Acl_Resource
{
	protected $_resourceId = NULL;
	protected $_db;
	protected $_resource;
	protected static  $_cache;
	protected $_controller;
	protected $_module;
	
	public static $allResources = NULL;
	const CATCH_KEY = "resources_mvc";
	
	
	
	/**
	 * 
	 * This resource will 
	 * @param unknown_type $action
	 * @param unknown_type $controller
	 * @param unknown_type $module
	 */
	
	public function __construct($controller,$module,$resourceId = null)
	{
	                   
         $this->_resource = trim(strtolower($module . '#' . $controller));
         /*echo self::$allResources['admin#index'];
         print_r(self::$allResources);
         echo $this->_resource . ' </br>';*/
         $this->_controller = $controller;
         $this->_module = $module;
         if($resourceId != null)
         $resourceId = (int) $resourceId;
         
         if(self::$_cache == NULL)
            self::$_cache = Zend_Registry::get('cacheManager')->getCache('database');
         
	     if(self::$allResources == NULL)
	        { 
	          self::$allResources = self::$_cache->load(self::CATCH_KEY);
	        } 
          l("allResources",self::$allResources);
           
         if($resourceId)
         {
           
             $this->_resourceId = $resourceId;
             self::$allResources[$this->_resource] = $this->_resourceId;
             self::$_cache->save(self::$allResources,self::CATCH_KEY);
             
         }
         	  
	}
	
	public function getModule()
	{
	    return $this->_module;
	}
	
	public function getController()
	{
	    return $this->_controller;
	}
	
	public function delete()
	{
	    unset(self::$allResources[$this->_resource]);
	    self::$_cache->save(self::$allResources,self::CATCH_KEY);
	}
	
	public static function deleteByMVC($action,$module,$controller)
	{
	    $resource = $module . '#' . $controller ;
	    self::deleteByResource($resource);
	
	}
	
	public static function deleteById($resourceID)
	{
	  $resource = array_search($resourceID,self::$allResources);
	  self::deleteByResource($resource);
	}
	
	public static function deleteByResource($resource)
	{
	    if(!array_key_exists($resource, self::$allResources)) return false;
	    unset(self::$allResources[$resource]);
	    self::$_cache->save(self::$allResources,self::CATCH_KEY);
	    return true;
	}
	
	public static function deleteAll()
	{
	   Zend_Registry::get('cacheManager')->getCache('database')->remove(self::CATCH_KEY);
	   self::$allResources = null;
	   
	}
	
	
	public function getResourceId()
	{
	    	
	    
	    if(!$this->_resourceId)
	    {
	        
	         $this->_resourceId = self::$allResources[$this->_resource];
	    }
        
	    return $this->_resourceId;
	}
	
	
	

	
}