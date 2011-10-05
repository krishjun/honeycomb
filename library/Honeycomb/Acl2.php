<?php
class Honeycomb_Acl extends  Zend_Acl
{
	protected $_cache;
	protected $_userTb;
	
	public function __construct()
	{
		$this->_cache = Zend_Registry::get('cacheManager')->getCache('database');
		$this->_userTb = new Default_Model_DbTable_Users();
	}
	
	
	public function isAllowed($user = null,$request,$privilege = null)
	{
		$userId = 0; //set to guest id
		
		if(is_null($user) === false && $user !== false && $user instanceof Zend_Db_Table_Row)
		{
			$userId = $user->user_id;
		}
		
		if($request instanceof Zend_Controller_Request_Abstract)
		{
			if(($roles = $this->_cache->load('acl_user_id_' . $userId)) === false)
			{
				//cache missed
				/* @var $user Default_Model_DbRow_User */
				$user = $this->_userTb->find($userId)->current();
				$roles = $user->getRoles();
				$this->_cache->save($roles,'acl_user_id_' . $userId);
			}
			
			return $this->haveAccessForRequest($roles,$request);
		}
			
		
	}
	
	
	private function haveAccessForRequest(Zend_Db_Table_Rowset $roles,Zend_Controller_Request_Http $request)
	{
		foreach($roles as $role)
		{
			/* @var $role Zend_Db_Table_Row */
			if(($privileges = $this->_cache->load('acl_role_id_' . $role->role_id )) === false)
			{
				//cache miss 
				$privileges = $role->findDependentRowset(new Default_Model_DbTable_Privileges());
				$this->_cache->save($privileges,'acl_role_id_' . $role->role_id);
			}
			
			if($this->hasAccessForPrivilege($privileges,$request)) return true;
			
		}
		//all privileges for all roles checked noone return truee;
		return false;
	}
	
	private function hasAccessForPrivilege(Zend_Db_Table_Rowset $privileges,Zend_Controller_Request_Http $request)
	{
		foreach ($privileges as $privilege)
		{
			/* @var $privilege Zend_Db_Table_Row */
		 if($privilege->module_name == '%') return  true;
		 if($privilege->module_name == $request->getModuleName() && $privilege->controller_name == '%') return true;
		 if($privilege->module_name == $request->getModuleName() && $privilege->controller_name == $request->getControllerName() && $privilege->action_name == '%') return true;	
		 if($privilege->module_name == $request->getModuleName() && $privilege->controller_name == $request->getControllerName() && $privilege->action_name == $request->getActionName()) return true;	
		}
		
		return false;
	}
	
	
}