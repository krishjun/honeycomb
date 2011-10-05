<?php
/**
 * Populate tree of current user with the given roles.
 * The tree is created on the basis of permission of current user 
 * and gets populated with roles.
 * Enter description here ...
 * @author root
 *
 */
class Admin_Model_RoleTree 
{
	
	protected $_roles;
	protected $_db;
	protected $_user;
	protected $_mainSelect;

	const BASE_SUPER_ADMIN = 'superadmin';
	const BASE_USER = 'user';
	const BASE_SESSION = 'session';
	
	
	
	/**
	 * 
	 * If user has superadmin role the full tree tree data gets loaded 
	 * otherwise it checks all the roles of current user from session and load
	 * tree
	 * 
	 * @param array|string $roles roles by which to populate tree
	 */
	public function __construct($roles)
	{
		if(!is_array($roles)) 
		    $this->_roles[] = $roles;
		 else
		$this->_roles = $roles;
		
		
		$this->_db = Zend_Db_Table::getDefaultAdapter();
	
		$this->_mainSelect = clone $this->_db->select();
		
		if(!Zend_Auth::getInstance()->hasIdentity()) throw new Honeycomb_Exception('You need to be logged in to edit permission tree');
		
		$this->_user = Zend_Auth::getInstance()->getIdentity();
	
		
	
	
	}
	

	
	public function getData()
	{
		
	    if($this->_user->isSuperadmin())
		{
	        
		   $this->_mainSelect = $this->_db->select()->from('modules',array('moduleName'=>'name','moduleKey'=>'identifier','module_id'))
     	                            ->join('resources', 'resources.module_id = modules.module_id',array('resourceName'=>'name','resourceKey'=>'identifier','resource_id'))
     	                            ->join('privileges','privileges.resource_id = resources.resource_id',array('privilegeName'=>'name','privilegeKey'=>'identifier','privilegeDescription'=>'description','privilege_id'));

		}else {
		     
		    
     	    foreach ($this->_user->getRoles() as $role) $roles[] = $role->role_id;
		    $this->_mainSelect->from('roles','role_id')
	                            ->join('roles_privileges','roles.role_id = roles_privileges.role_id')
	                            ->join('privileges','roles_privileges.privilege_id = privileges.privilege_id',array('privilegeName'=>'name','privilegeKey'=>'identifier','privilegeDescription'=>'description','privilege_id'))
	                            ->join('resources', 'privileges.resource_id = resources.resource_id',array('resourceName'=>'name','resourceKey'=>'identifier','resource_id'))
	                            ->join('modules','resources.module_id = modules.module_id',array('moduleName'=>'name','moduleKey'=>'identifier','module_id'))  
     	                        ->where('roles.role_id IN ?',new Zend_Db_Expr('(' . implode(',', $roles). ')'));                       
		    
		}
		//die('not superadmin');
		
	 $data = $this->_mainSelect->query()
     	                        ->fetchAll();
		
		
		
     	                        
     	                        
   l('getJson',$data);

      
          
     	                            
     $temp = array();
      foreach($data as $row)
      {
          //$temp[$row['moduleName']][$row['resourceName']][$row['privilegeName']][] = $row;
          $temp[$row['moduleKey']][$row['resourceKey']][$row['privilegeKey']] = $row;
      }       



    //$output = array(array('title'=>'moduleName','children'=>array(array('title'=>'controllerName','children'=>array(array('title'=>'actionName')))))); //template
    //$this->_helper->json($output);
    $output = array();
    
    foreach($temp as $moduleName => $controllers)
    {
    	$module = array('title'=>$moduleName,'isFolder'=>true,'children'=>array());
    	
    	
    	foreach($controllers as $controllerName => $actions)
    	{
           $controllerActions = array();
    	    
           foreach ($actions as $actionName => $row)
           {
            $select = false;
           
            
            foreach($this->_roles as $roleId)
           	{
           	  $select = Honeycomb_Acl::getInstance()->isAllowed($roleId,new Honeycomb_Acl_Resource_Mvc($controllerName, $moduleName),$actionName);
           	  if($select) break;
           	}
           	
           	
           	
           	 $dbRow = $temp[$moduleName][$controllerName][$actionName];

           	 $node = array();
           	 $node['title'] = $actionName;
           	 $node['select'] = $select;
           	 $node['tooltip'] = $dbRow['privilegeDescription'];
           	 $node['key'] = $dbRow['privilege_id'];
           	  	 
           	 
           	 $node['module_id'] = $dbRow['module_id'];
           	 $node['resource_id'] = $dbRow['resource_id'];
             $node['privilege_id'] = $dbRow['privilege_id'];
             
             
           	 $node['module'] = $moduleName;
           	 $node['controller'] = $controllerName;
           	 $node['action'] = $actionName;
           	 
           	 
           	 $controllerActions[] = $node;
           }
    		
    		$module['children'][] = array('title'=>$controllerName,'isFolder'=>true,'children'=>$controllerActions);	
    	}
    	
    	$output[] = $module;
    }
    
    return  $output;
   
	}
	
}