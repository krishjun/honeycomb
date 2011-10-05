<?php
class Admin_RoleController extends Zend_Controller_Action
{
	private $_rolesTb ;
	
	
	public function init()
	{
		$this->_rolesTb = new Default_Model_DbTable_Roles();
		if($this->_request->isXMLHttpRequest()) l("xml request send","dd");
	}
	
	public function manageAction()
	{
		$allRoles = new Default_Model_DbTable_Roles();
		$rows = $allRoles->select()
		         ->from($allRoles,array(Default_Model_DbTable_Roles::C_NAME,Default_Model_DbTable_Roles::C_ROLE_ID))
		         ->where(Default_Model_DbTable_Roles::C_ROLE_ID . " = ?",1)
		         ->order(Default_Model_DbTable_Roles::C_ROLE_ID . " DESC")
		         ->query()->fetchAll();
		    
		                     
		
	}
	
	public function addAction()
	{
		
	}
	
	public function listAction()
	{
		$data =  Zend_Json::encode($this->_rolesTb->fetchAll()->toArray());
		$this->_helper->json($data);
		//exit;
	}
	
	public function deleteAction()
	{
		
	}
	
	public function infoAction()
	{
		$roleID = $this->_getParam('id',1);
		$privilArray = $this->_rolesTb->find($roleID)->current()->findDependentRowset(new Default_Model_DbTable_Privileges())->toArray();
		$this->_helper->json($privilArray);
	}
	
	public function modulesAction()
	{
	  $modules = array();
	   $modules[] = "%";
	  $moduleDir = APPLICATION_PATH . '/modules';
	  $dirItr = new DirectoryIterator($moduleDir);
	  foreach ($dirItr as $dir)
	  {
	  	/* @var $dir SplFileInfo */
	  	if($dir->isDir() && !$dir->isDot()) $modules[] = $dir->getFilename();
	  }
	 
	  $this->_helper->json($modules);
	}
	
	public function controllersAction()
	{
		$controllers[] = "%";
		$controllers = array();
		$moduleName = $this->_getParam('module_name','default');
		$controllerDir = APPLICATION_PATH . '/modules/' . $moduleName . '/controllers';
		$dirItr = new DirectoryIterator($controllerDir);
		foreach ($dirItr as $dir)
		{
			/*@var $dir as splFilInfo */
		  
			if($dir->isFile()) $controllers[] = substr($dir->getFilename(),0,-4);
		} 
		
		
		$this->_helper->json($controllers);		
		
	}
	
	public function actionsAction()
	{
		
		//$this->_helper->viewRenderer->setNoRender();
		$actions = array();
		$module = $this->_getParam('module_name','default');
		$controller = $this->_getParam('controller_name','IndexController');
		
		$classFile = APPLICATION_PATH . "/modules/" . $module . "/controllers/{$controller}.php";
		$prefix = '';
		if(strtolower($module) !== 'default')
		{
			$prefix = ucfirst($module) . '_';
		}
		
		$className = $prefix . ucfirst($controller);
	
		require_once $classFile;
		$class = new ReflectionClass($className);
		$functions = $class->getMethods(ReflectionMethod::IS_PUBLIC);
		
		foreach($functions as $function)
		{
			if(substr($function->getName(),-6) == 'Action') $actions[] = $function->getName();
		}
		
		$this->_helper->json($actions);
		
		
		
		

		
	}
	
	
	

	
}