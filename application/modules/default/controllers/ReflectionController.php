<?php
class ReflectionController extends Default_Library_Controller_Action
{
	
	protected $_aclLoader;
	protected $_db;
	
	public function init()
	{
		$this->_helper->layout()->disableLayout();
		$this->_aclLoader = new Default_Model_AclLoader();
		//$this->_helper->viewRenderer->setNoRender(true);
		$this->_db = Zend_Db_Table::getDefaultAdapter();
	}
	
	public function indexAction()
	{
		
		//$acl = Honeycomb_Acl::getInstance();
		
		//$this->_aclLoader->init();
		$this->_aclLoader->refresh();
	}
	
	public function listAction()
	{
		$userId = 2;
		$userTb = new Zend_Db_Table('users');
		
		
		/*$data = $this->_db->select()->from(array('u'=>'users'))->where('u.user_id = ?',$userId)
		                         ->join('users_roles','u.user_id = users_roles.user_id','users_roles.sort_order')
		                         ->join(array('r'=>'roles'),'users_roles.role_id = r.role_id')
		                         //->__toString();
		                        ->query()
		                         ->fetchAll();
		                         Zend_Debug::dump($data);*/
	if($this->getRequest()->isPost())
	{
		$state = $this->_getParam('state','sortupdate');
		if($state == 'sortupdate')
		{
			$orders = $this->_getParam('role');
			$newOrder = 0;
			foreach($orders as  $oldOrder )
			{
             
				//we will use oldOrder to find the row and and update it
				//$usersRoles = new Zend_Db_Table('users_roles');
				$newOrder++;
				$usersRoles = new Admin_Model_DbTable_UsersRoles();
				list($userId,$roleId) = explode('#',$oldOrder);
				$current = $usersRoles->find($userId,$roleId)->current();
				$current->sort_order = $newOrder;
				$current->save();
				
			          
				
			}
		}
		exit; //since it was an ajax post call simply exist
	}	
		
		
	$this->view->roles = $this->_db->select()->from('users',array())
		                         ->join('users_roles','users.user_id = users_roles.user_id')
		                         ->join('roles','users_roles.role_id = roles.role_id')
		                         ->where('users.user_id = ?',$userId)
		                         ->where('users_roles.sort_order != ?',0)
		                         ->order('users_roles.sort_order')
		                         ->query()
		                         ->fetchAll();
		                         
		                         
		                         
		         
		                         
	}
	
	
}

