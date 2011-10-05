<?php
class Admin_IndexController extends  Admin_Library_Controller_Action
{
	public function indexAction()
	{
		echo "welcome admin";
		$roleTree = new Admin_Model_RoleTree(array('1','2','3'));
        $roleTree->getJson();
		
	
	}
	
	public function fooAction()
	{
	    $this->_helper->viewRenderer->setNoRender();
	    
      $usersTb = Honeycomb_Db::getTable('users_roles');
      Zend_Debug::dump($usersTb->fetchAll()->toArray());
		
	}
	
	//do action was here but deleted 
	
	/**
	 * 
	 * This will allow to test admin controller
	 * @acl I does nothing great
	 * @name testMe
	 */
	public function testAction()
	{
	    
	}
	
	/**
	 * 
	 * @name this does nothing
	 * @acl this is a review
	 * sdfsdf sdf s
	 * sdfsdf sdf
	 *  sdfd
	 */
	public function reviewAction()
	{
	    
	}
	

}