<?php
class Honeycomb_Controller_Action extends Zend_Controller_Action
{

    	public $acl;
    	public $rolesTb;
    	public $usersTb;
    	public $usersRolesTb;
    	public $cache;
    	
    	
    public function __construct(Zend_Controller_Request_Abstract $request,Zend_Controller_Response_Abstract  $response,array $invokeArgs = array())
	{
	    parent::__construct( $request, $response,$invokeArgs);
	    
	    $this->cache = Zend_Registry::get('cacheManager')->getCache('database');
	    /* @var $this->cache Zend_Cache */ //TODO why not working ?
	    
	    $this->acl = Honeycomb_Acl::getInstance();
	    $this->rolesTb = new Default_Model_DbTable_Roles(); 
	    $this->usersTb = new Default_Model_DbTable_UsersRoles();
	    
	}
	
	protected function getSession()
	{
	    return new Zend_Session_Namespace(get_class($this));
	}
    
	
    
}