<?php



class Default_Model_AclLoader
{
	protected $_db;
	protected $_moduleTb;
	protected $_controllerTb;
	protected $_privilegesTb;
	protected $_front;
	const REFRESH_CONTROLLERS = "controllers";
	const REFRESH_MODULES = "modules";
	const REFRESH_ACTIONS = "actions";
	const REFRESH_ALL = "all";
	
	protected $_modulesDir;
   public static function fff($classname)
	{
	   if(class_exists($classname,false))return;
	    $file = str_replace('_','/',$classname);
	   $file =  $file . '.php';
	   $realPath = realpath($file);
	  // if(strstr($realPath,$file)) return;
	   $incFiles = get_included_files();
	   //print_r($incFiles);exit;
	   if(!in_array($file, $incFiles)) require_once $file;
	}
	public function __construct()
	{
		//set_include_path();
		set_include_path(implode(PATH_SEPARATOR, array(
    realpath('C:\wamp\www\appform2\library'),
    get_include_path(),
)));

spl_autoload_register('self::fff',false,true);
	    $this->_db = Zend_Db_Table::getDefaultAdapter();
	    $this->_moduleTb = new Zend_Db_Table('modules');
	    $this->_controllerTb = new Zend_Db_Table('resources');
	    $this->_privilegesTb = new Zend_Db_Table('privileges');
	    $this->_front = Zend_Controller_Front::getInstance();
	   // $this->_modulesDir = APPLICATION_PATH . '/modules';
	   $this->_modulesDir = "C:/wamp/www/appform2/application/modules";
	    
	}
	
	public function init()
	{
		$this->syncModules($this->_modulesDir);
	    //$this->syncControllers('admin');
		//$this->syncActions('admin', 'role');
	      
	}
	
/*	
	public function referesh($itemName,$mode = self::REFRESH_ALL)
	{
	    switch ($mode)
	    {
	        case self::REFRESH_ACTIONS:
	            $this->refreshActions($itemName);
	             break;
	        case self::REFRESH_CONTROLLERS:
	            $this->refreshControllers($itemName);
	            break;
	        case self::REFRESH_MODULES:
	            $this->refreshModules($itemName);
	            break; 
	                         
	    }
	}*/
	
	public function refresh()
	{
	    $this->init(); //first update all new modules and everthing inside it
	    $this->refreshControllers(); //will upate new controllers created inside old modules
	    $this->refreshActions(); //will update new actions created inside old modules inside old controllers
	}
	
	
	public function refreshControllers()
	{
	    $allModules = $this->getFromDisk($this->_modulesDir); //will add newly created controllers in db
	    foreach($allModules as $module)
	    {
	        	$this->syncControllers($module);
	    }
	}
	
	public function refreshActions()
	{
	    $allModules = $this->getFromDisk($this->_modulesDir); 
	    
	    foreach($allModules as $moduleName)
	    {
	        $controllerDir = $this->_modulesDir . '/' . $moduleName . '/' . 'controllers';
	        $allControllers = $this->getFromDisk($controllerDir,FALSE);
	        
	        foreach($allControllers as $controller)
	        {
	            $this->syncActions($moduleName, $controller);
	        }
	    }
	}
	


	
	private function syncModules($modulesDir)
	{
		$allModules = $this->getFromDisk($modulesDir);
		$dbModules = $this->getFromDb($this->_moduleTb);
		
		$modsToAdd = array_diff($allModules, $dbModules);
		
		foreach ($modsToAdd as $newModule)
		{
			$row = $this->_moduleTb->createRow();
			$row->identifier = $newModule;
			$row->name = $newModule;
			$row->save();
			$this->syncControllers($newModule);
		}
	}
	
	private function syncControllers($moduleName)
	{
		$controllerDir = $this->_modulesDir . '/' . $moduleName . '/' . 'controllers';

		$moduleRow = $this->_moduleTb->fetchRow($this->_moduleTb->select()->where('identifier = ?',$moduleName));
		$moduleRow = $moduleRow->toArray();
		
		//$controllersDb = $this->getControllers($moduleName); 
		$allControllers = $this->getFromDisk($controllerDir,FALSE);
		$dbControllers = $this->getFromDb($this->_controllerTb,"module_id = {$moduleRow['module_id']}");
	    $controllersToAdd = array_diff($allControllers, $dbControllers);
	    
	    foreach ($controllersToAdd as $newController)
	    {
	    	$controller = $this->_controllerTb->createRow();
	    	$controller->module_id = $moduleRow['module_id'];
	    	$controller->identifier = $newController;
	    	$controller->save();
	    	$this->syncActions($moduleName, $newController);
	    }
		
	}
	
	
	private function syncActions($moduleName,$controllerName)
	{
		if($controllerName == "") return;
	    $resouseId = $this->_db->select()->from('modules',array())
		                       ->join('resources','resources.module_id = modules.module_id','resources.resource_id')
		                       ->where('modules.identifier = ?',$moduleName)
		                       ->where('resources.identifier = ?',$controllerName)
		                       ->limit(1)
		                       ->query()
		                       ->fetchColumn();
		                    
		               
		$controllerPhp = $this->_modulesDir . '/' . $moduleName . '/' . 'controllers' . '/' . ucfirst($controllerName) . 'Controller.php';
		$controllerClassName = ucfirst(strtolower($moduleName)) . '_' . ucfirst(strtolower($controllerName)) . 'Controller';
		
		if(class_exists($controllerClassName,false))return;
		//if(!in_array($controllerPhp, get_included_files()))
		require_once $controllerPhp;
		$fileReflection = new Zend_Reflection_File($controllerPhp);
		
		$classes = $fileReflection->getClasses();
		if(count($classes) == 0) return;
		$controllerClass = $classes[0];
		$methods = $controllerClass->getMethods();
		
		$actions = array();
		$docBlock = array(); 
		foreach($methods as $method)
		{
			/* @var $method Zend_Reflection_Method */
		   
		if(substr($method->getName(),-6) == 'Action')
		{
		    $actionName = substr($method->getName(),0,-6);
		    $actions[] = $actionName;
		    
		    try {
		    $docBlock[$actionName] = $method->getDocblock();
		    }catch (Zend_Reflection_Exception $e)
		    {
		        $docBlock[$actionName] = null;
		    }
		  
		  
		}
		
		}
	
		
		$dbActions = $this->_db->select()->from('modules',array())
		                                 ->join('resources','modules.module_id = resources.module_id',array())
		                                 ->join('privileges','privileges.resource_id = resources.resource_id','privileges.identifier')
		                                 ->where('modules.identifier = ?',$moduleName)
		                                 ->where('resources.identifier = ?',$controllerName)
		                                 ->query()
		                                 ->fetchAll(Zend_Db::FETCH_COLUMN);
		   
		 /*   $dbActions = array();
		    foreach($dbMethods as $dbMethod)
		   {
		   	$dbActions[] = $dbMethod['identifier'];
		   }    */                          
		   
		  // Zend_Debug::dump($dbActions,'Db actions');
		   $actionsToAdd = array_diff($actions, $dbActions);
		   
		   foreach ($actionsToAdd as $action)
		   {
		   	 $doc = $docBlock[$action];
		   	 /* @var $doc Zend_Reflection_Docblock */
		   	 
		     $new = $this->_privilegesTb->createRow();
		   	 $new->resource_id = $resouseId;
		   	 $new->identifier = $action;
		   	 
		   	 if(null !== $doc) {
		   	  if($doc->hasTag('acl'))   
		   	 $new->description = $doc->getTag('acl')->getDescription();
		   if($doc->hasTag('name'))
		   	 $new->name = $doc->getTag('name')->getDescription();   
		   	 }

		   	 $new->save();
		   }
	
		/*$classes = $fileReflection->getClasses();
		$controllerClass = $classes->next();*/
		
		
		
	}

	private function getControllers($moduleName)
	{
		$ret = array();
		$controllers = $this->_db->select()->from('resources','identifier')
		                              ->join('modules','resources.module_id = modules.module_id')
		                              ->query()
		                              ->fetchAll();
		if(!$controllers) return $ret;
		foreach ($controllers as $controller)
		{
			$ret[] = $controller->identifier;
		}   
		return $ret;                           
	}
	
	protected function getControllerDir($moduleName)
	{
		return APPLICATION_PATH . '/modules/' . $moduleName . '/controllers';
	}
	
	protected  function getFromDisk($dir,$isModule = true)
	{
		$ret = array();
		$dirItr = new DirectoryIterator($dir);
		foreach($dirItr as $module)
		{
			/* @var $module SplFileInfo */
			if($module->isDir() && !$module->isDot() && $this->isValid($module) && $isModule)
			  $ret[] = $module->getFilename();
			 elseif ($module->isFile())
			 $ret[] = $this->formatController($module->getFilename()); 
		}
		
		return $ret;
	}
	
	protected function isValid(SplFileInfo $dir)
	{
	    //check if dir starts with . e.g .svn
	
	    if(substr($dir->getFilename(),0,1) == '.')return false;
	    return true;
	}
	
	protected function formatController($controllerPhp)
	{
		$stop = strpos($controllerPhp,'Controller.php');
		return strtolower(substr($controllerPhp, 0,$stop));
	}
	
	protected function getFromDb($dbTable,$where = null)
	{
		$ret = array();
		$dbModules = $dbTable->fetchAll($where);
		if(!$dbModules) return $ret;
		
		foreach ($dbModules as $module)
		 $ret[] = $module->identifier;
		 
		 return $ret;
	}
	
	
}