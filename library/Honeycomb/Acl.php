<?php
class Honeycomb_Acl extends  Zend_Acl
{
	private static $_instance = NULL;
	protected static  $_cache;
	protected $_db;
	protected $_user;
	protected $_roleTb;
	protected $_resourceTb;
	protected $_privilegeTb;
    protected $_moduleTb;
    protected $_userId;
    
    protected $_defaultCompanyRoles = array('1'=>'simple User');
    
    const ROLE_IS_GLOBAL = 'is_global';
    const ROLE_IS_USER_BASED = 'is_user_based';
    const ROLE_IN_COMPANY = 'company_id';  
    const COMPANY_INIT_ROLE = 'init';
    const GUEST_ID = 3;
    //const  SUPERADMIN_ID = 4;
	
	
	protected  function __construct()
	{
		// $this->_cache = Zend_Registry::get('cacheManager')->getCache('database')->save($this,'acl');
		//if constroctur getting run means acl is getting loaded fresh so load everthing from database into it
		
		//$this->_initResources();
		//$this->_initRoles();
		$this->_db = Zend_Db_Table::getDefaultAdapter();
		
		$this->_roleTb = new Default_Model_DbTable_Roles();
		$this->_resourceTb = new Default_Model_DbTable_Resources();
		$this->_moduleTb = new Default_Model_DbTable_Modules();
		
		$this->_initRoles();
		$this->_initResources();
		$this->_initPrivileges();
	}
	
	public function loadDefaultRolesForCompany($companyId)
	{
	  
	  foreach($this->_defaultCompanyRoles as $parentId => $roleName)
	  {
	  	$this->addRole($roleName,$parentId,self::COMPANY_INIT_ROLE,$companyId);
	  }
	}
	
	
	private function _initRoles()
	{
	   $roles = $this->_roleTb->fetchAll(); //alreadys sorted by there primary key
	 
	   
	   foreach($roles as $role)
	   {
           $theRole = new Honeycomb_Acl_Role($role); 
           
	       if(!$this->hasRole($theRole)) //TODO remove this check since role id always gona be unique and hence by mistake no one can add two same role id in database
	       {
	            if($role->name == 'superadmin') 
	            {
	                parent::addRole($theRole);
	                parent::allow($theRole,null,null);
	                continue;
	            }
	            
	            if($role->parent_id != null && $this->hasRole($role->parent_id))
	            {
	                 parent::addRole($theRole,$role->parent_id); //extending the role 
	            }else {
	                 parent::addRole($theRole);
	            }
	            
	            //superadmin fix
	       }
	   }
	}
	
	private function _initResources()
	{
	   Honeycomb_Acl_Resource_Mvc::deleteAll();
	   $modules = $this->_moduleTb->fetchAll();
	   
	   foreach($modules as $module)
	   {
	      /* @var $module Zend_Db_Table_Row */
	      $resources = $module->findDependentRowset($this->_resourceTb);

	      foreach($resources as $resource)
	      {
	           $mvcResource = new Honeycomb_Acl_Resource_Mvc($resource->identifier,$module->identifier,$resource->resource_id);
             	         
	           if(!$this->has($mvcResource)) parent::addResource($mvcResource);
	           
	           
	           
	      }
	   }
	 
	}
	
	
	
	private function _initPrivileges()
	{
      /* $this->_db->select()->from('roles',array('role'=>'name','role_id'))
                           ->join('roles_privileges',array('role'));*/
             
      //$roles = $this->getRoles();
   
          
          $roles = $this->_db->select()->from('roles')
                              ->join('roles_privileges','roles.role_id = roles_privileges.role_id')
                              ->join('privileges','roles_privileges.privilege_id = privileges.privilege_id')
                              ->query()
                              ->fetchAll();
                        
                           
          $data = array();                
          foreach($roles as $role) 
          {
              $data[$role['role_id']][] = array('privilege' => $role['identifier'],'privilege_id'=>$role['privilege_id'],'resource_id' => $role['resource_id'],'is_deny' => $role['is_deny']);
          }                   
          
          
        // $aclRoles = $this->getRoles();
        //Zend_Debug::dump($data,'privileges');
         //exit;
         foreach ($data as $roleId => $privileges)
         {
         
       /* echo $roleId;
             $privileges =  $data[$roleId];*/
            
             foreach($privileges as $permission)
             {
             
             if($permission['is_deny'])
            {
              //logic to sotre special deny  
              parent::deny($roleId,$permission['resource_id'],$permission['privilege']); 
              l("Role $roleId PrivilegeId : {$permission['privilege_id']} , ResourceID : {$permission['resource_id']}" ,"false");
            }else {
                parent::allow($roleId,$permission['resource_id'],$permission['privilege']);
                 l("Role $roleId PrivilegeId : {$permission['privilege_id']} , ResourceID : {$permission['resource_id']}" ,"true");
                
            }
            
             }
         
         }
                           
                           
		
	}
	
	
	/**
	 * 
	 * Wrapper arround isAllowed
	 * @param unknown_type $action
	 * @param unknown_type $controller
	 * @param unknown_type $module
	 */
	
	public function isOk($action = null,$controller = null,$module = null)
	{
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $user = Zend_Auth::getInstance()->getIdentity();
            
        } else {
            $userTb = new Default_Model_DbTable_Users();
            $user = $userTb->find(self::GUEST_ID)->current();
         
        }
	     
	     $roles = $user->getRoles(); //roles are returned in sorted order
	     l($user->toArray(),$roles);
	     
	    
	     //In case query being made by directly sending resource Id and privilege name
	     if(is_int($action) && is_null($module))
	    {
	        $resource = $action;
	        $action = $controller;
	    }else {
	     $resource = new Honeycomb_Acl_Resource_Mvc($controller,$module);
	    }
	     
	     foreach($roles as $role)
	     {
	         if($this->isDenied($role->role_id, $resource, $action)) return false; //specially dienied take preference
	         if($this->isAllowed($role->role_id,$resource,$action)) return true; //spcially allowed 
	     }
	     
	   
	   
	    return false; //all roles checked nowehere alloed
	}
	
	
	/**
	 * 
	 * Search in rules to find if specifily denied i.e by using $this->deny() 
	 * @param unknown_type $roleId
	 * @param unknown_type $resource
	 * @param unknown_type $privilege
	 */
	public function isDenied($roleId,$resource,$privilege)
	{
	    //l($this->_rules);
	    //die('foo');
	    if($this->has($resource) && $this->hasRole($roleId))
	    {
	       
	     $roleId = $this->getRole($roleId)->getRoleId();
	     $resourceId = $this->get($resource)->getResourceId();   
	      
	   return @$this->_rules['byResourceId'][$resourceId]['byRoleId'][$roleId]['byPrivilegeId'][$privilege]['type'] === 'TYPE_DENY';
	    }
	    
	    return false;
	}
	
	public function testWaste()
	{
	    l($this->_rules);
	    return $this->_rules;
	}
	
	
   
	
	
	/* (non-PHPdoc)
     * @see Zend_Acl::allow()
     */
    public function allow ($role = null, $resource = null, $privilegeIdentifier = null, 
    Zend_Acl_Assert_Interface $assert = null)
    {
        // TODO Auto-generated method stub
       
        $resourceId = $this->get($resource)->getResourceId();
        $privilegeId = $this->getPrivilegeId($resourceId,$privilegeIdentifier);
        //die($privilegeId);
        $roleId = $this->getRole($role)->getRoleId();
       
        if($this->isDenied($roleId, $resourceId, $privilegeIdentifier))
        {
            
            $this->removeDeny($roleId,$resourceId,$privilegeIdentifier);
        }
         
        
        if(is_null($resourceId) || is_null($privilegeId) || is_null($roleId)) throw new Exception("Resource , Role or Privilege Invalid is Null");
        
       $rolesPrivilegesTb = new Default_Model_DbTable_RolesPrivileges();
     
       //if($rolesPrivilegesTb->find($roleId,$privilegeId)->current() != null) throw new Exception("Given combination of role ID : $roleId and privilege ID : $privilegeId in database already exist");
       if($rolesPrivilegesTb->find($roleId,$privilegeId)->current() != null) return; //no need to allow since its already allowed
       
       
       $rule = $rolesPrivilegesTb->createRow();
       $rule->privilege_id = $privilegeId;
       $rule->role_id = $roleId;
       $rule->save();
       
       parent::allow($roleId,$resourceId,$privilegeIdentifier); //Update the current acl
       
       //self::$_cache->remove('acl');
       //self::$_cache->save(self::$_instance,'acl');
        
    }
    
    
    
    
    
    /* (non-PHPdoc)
     * @see Zend_Acl::deny()
     */
    public function deny ($role = null, $resource = null, $privilegeIdentifier = null, 
    Zend_Acl_Assert_Interface $assert = null)
    {
        // TODO Auto-generated method stub
       
        $resourceId = $this->get($resource)->getResourceId();
        $privilegeId = $this->getPrivilegeId($resourceId,$privilegeIdentifier);
        $roleId = $this->getRole($role)->getRoleId();

        if($this->isAllowed($roleId,$resourceId,$privilegeIdentifier))
        {
            $this->removeAllow($roleId,$resourceId,$privilegeIdentifier);
        }
        
        if(is_null($resourceId) || is_null($privilegeId) || is_null($roleId)) throw new Exception("Resource , Role or Privilege Invalid");
        
       $rolesPrivilegesTb = new Default_Model_DbTable_RolesPrivileges();
       if($rolesPrivilegesTb->find($roleId,$privilegeId)->current() != null) return;
       $rule = $rolesPrivilegesTb->createRow();
       $rule->privilege_id = $privilegeId;
       $rule->role_id = $roleId;
       $rule->is_deny = true;
       $rule->save();
       
       parent::deny($roleId,$resourceId,$privilegeIdentifier);
        
    }
    
    

	/* (non-PHPdoc)
     * @see Zend_Acl::removeAllow()
     */
    public function removeAllow ($role = null, $resource = null, $privilegeName = null)
    {
        // TODO Auto-generated method stub
        $roleId = $this->getRole($role)->getRoleId();
        $resourceId = $this->get($resource)->getResourceId();
        $privilegeId = $this->getPrivilegeId($resourceId, $privilegeName);
        $rolesPrivileges = new Default_Model_DbTable_RolesPrivileges();
        $rule = $rolesPrivileges->find($roleId,$privilegeId)->current();
        
        
        if($rule != null && !$rule->is_deny)
        {
          $rule->delete(); 
          parent::removeAllow($role,$resource,$privilegeName); 
        }
        
      
        
    }

	/* (non-PHPdoc)
     * @see Zend_Acl::removeDeny()
     */
    public function removeDeny ($role = null, $resource = null, $privilegeName = null)
    {
        // TODO Auto-generated method stub
        
        $roleId = $this->getRole($role)->getRoleId();
        $resourceId = $this->get($resource)->getResourceId();
        $privilegeId = $this->getPrivilegeId($resourceId, $privilegeName);
        $rolesPrivileges = new Default_Model_DbTable_RolesPrivileges();
        $rule = $rolesPrivileges->find($roleId,$privilegeId)->current();
        
        if($rule != null && $rule->is_deny)
        {
          $rule->delete(); 
          parent::removeDeny($role,$resource,$privilegeName); 
        }
        
        
    }
    
    public function addRole($roleName ,$parentRole = NULL,$roleType = null,$companyId = null,$isDefault = null)
    {
       $rolesTb = new Default_Model_DbTable_Roles();
       $roleRow = $rolesTb->createRow();
       $roleRow->name = $roleName;
       
       switch ($roleType)
       {
       	case null;
       	case self::ROLE_IS_GLOBAL:
       		$roleRow->is_global = true;
       		break;
       	case self::ROLE_IN_COMPANY:
       		$roleRow->company_id = Zend_Auth::getInstance()->getIdentity()->getCompanyId();
       		break;
       	case self::COMPANY_INIT_ROLE:
       		$roleRow->company_id = $companyId;
       		break;	
       	case self::ROLE_IS_USER_BASED:
       		$roleRow->is_user_based = true;				
       }
       
       
       if($parentRole) 
          $roleRow->parent_id = $this->getRole($parentRole)->getRoleId();
       
       if($isDefault)
         $roleRow->is_default = true;   
          
        $newRoleId = $roleRow->save();
       
       if($parentRole)
       
       parent::addRole($newRoleId,$roleRow->parent_id);
       
          else 
       
      parent::addRole($newRoleId);
        
       return $newRoleId;
    }
    
    
    public function removeRole($roleId)
    {
        $rolesTb = new Default_Model_DbTable_Roles();
        $roleRow = $rolesTb->find($roleId)->current();
        
        if($roleRow == null) throw new Exception("Role With ID : $roleId Does not exist in Database hence cant be removed");
        
         //TODO implement this delete to remove association from pivot table of roles_privileges
        $roleRow->delete();
        parent::removeRole($roleId);
        
    }

	public function getPrivilegeId($resourceId,$privilegeName)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $privilegeId = $db->select()->from('privileges','privilege_id')
                                    ->where('resource_id = ?',$resourceId)
                                    ->where('identifier = ?',$privilegeName)
                                    ->query()
                                    ->fetchColumn();
        if($privilegeId == null) throw  new Exception('Privilege ID Not Found !! ');
        return $privilegeId;                            
    }
    
    protected function getRoleTb()
    {
        if($this->_roleTb == null)
        {
            $this->_roleTb = new Default_Model_DbTable_Roles();
        }
        return $this->_roleTb;
    }
    
    
    

	/*public function isAllowed($roleId,$resourceId,$privilegeId)
	{
	   
	}*/
	
	
	
	
	/**
	 * 
	 * To enforce singleton pattern.
	 */
	private function __clone()
	{
		
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @return Honeycomb_Acl
	 */
	
  public static function getInstance()
    {
        self::$_cache = Zend_Registry::get('cacheManager')->getCache('database');
       // self::$_cache->clean();
        if(($acl = self::$_cache->load('acl')) !== false) return $acl;
     l("acl not getting loaded from cache");
    	
    	if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
	
	
	
	public function __destruct()
	{
		
	    //parent::allow(2,36,"uhaish");
	    self::$_cache->save($this,'acl');
		
	}
}
