<?php
/**
 * 
 * This is some description
 * @author root
 * @aclDescription Allows to view Index
 */
class IndexController extends Default_Library_Controller_Action
{

	protected $_modulesTb;
	protected $_resourcesTb;
	protected $_privilegesTb;
	
	protected $_rolesTb;
	protected $_usersTb;
	
	protected $_usersRolesTb;
	
	protected $_db;
	protected $_acl;
	
    public function init()
    {
        /* Initialize action controller here */
    	$this->_modulesTb = new Zend_Db_Table('modules');
    	$this->_resourcesTb = new Zend_Db_Table('resources');
    	$this->_privilegesTb = new Zend_Db_Table('privileges');
    	$this->_usersRolesTb = new Default_Model_DbTable_UsersRoles();
    	$this->_rolesTb = new Default_Model_DbTable_Roles();
    	$this->_usersTb = new Default_Model_DbTable_Users();
    	$this->_db = Zend_Db_Table::getDefaultAdapter();
    	$this->_acl = Honeycomb_Acl::getInstance();
    	//$aclLoader = new Default_Model_AclLoader();
    	//$aclLoader->refresh();
    	$view = new Zend_View();
    	$paths = $this->view->getScriptPaths();
    	$view->addScriptPath($paths[0]);
    	$view->name = "open source";
    	$test = $view->render("test.phtml");
    	echo $test;
    }

    public function indexAction()
    {
     	
        
     if($this->getRequest()->isXmlHttpRequest())
   {
       $this->_helper->viewRenderer->setNoRender();
       if($this->_helper->layout()->isEnabled()) $this->_helper->layout()->disableLayout();
     //Zend_Debug::dump($this->_getAllParams());exit;
     $command = $this->_getParam('command',null);
    // die($command);
    /* if($command === null)
     {
           
     }*/
     
     switch($command)
     {
         case 'allow':
             //Zend_Debug::dump($this->_getAllParams());exit;
             $this->recursiveData($this->_getParam('data'));
             
             break;

         case 'removeAllow':
             $this->recursiveData($this->_getParam('data'),true);
             break;    
             
         case 'loadRole':
             l("role id ", $this->_getParam('role_id'));
           $this->getSession()->role_id = $this->_getParam('role_id');
           $aclTree = new Admin_Model_RoleTree($this->_getParam('role_id',$this->_getParam('role_id')));
           $this->_helper->json($aclTree->getData());
           return;  
     }
   return;
   }
        
        $roles = $this->_rolesTb->fetchAll();
        $select = new Zend_Form_Element_Select('roleSelect');
        //$select->setLabel('Select Role');
        $select->addMultiOption('label','Select a Role');
        foreach($roles as $role)
        {
            $option = $this->_helper->url('index','index','default',array('role_id'=>$role->role_id));
            $select->addMultiOption($option,$role->name);  
        }
        
        $this->view->roleSelect = $select;
 
  
                             
                                            
    }
    
    
    protected function ruleUpdate($data,$deny = false)
    {
     
        foreach($data as $node)
        {
            //die($this->getSession()->role_id);
            //Zend_Debug::dump($node);exit;
           //TODO activate the below line latter
            // if(!$this->acl->isOk($node['action'],$node['controller'],$node['module'])) throw new Honeycomb_Exception('You are not allowd to make such changes !!');
            if($deny)
               $this->acl->removeAllow($this->getSession()->role_id,new Honeycomb_Acl_Resource_Mvc($node['controller'],$node['module']),$node['action']);
              else
               $this->acl->allow($this->getSession()->role_id,new Honeycomb_Acl_Resource_Mvc($node['controller'],$node['module']),$node['action']);
            
        }
    }
    
    
    protected function recursiveData($data,$deny = false)
    {
        //l($data);return;
        //Zend_Debug::dump($data);exit;
        if((!array_key_exists('isFolder', $data)) || $data['isFolder'] == 'false')
        {
            //Zend_Debug::dump($data,'Current node');exit; 
      
           
            //die($this->getSession()->role_id);
            //Zend_Debug::dump($node);exit;
           //TODO activate the below line latter
            // if(!$this->acl->isOk($node['action'],$node['controller'],$node['module'])) throw new Honeycomb_Exception('You are not allowd to make such changes !!');
            try {
                
          $node = $data;
            if($deny)
               $this->acl->removeAllow($this->getSession()->role_id,new Honeycomb_Acl_Resource_Mvc($node['controller'],$node['module']),$node['action']);
              else
               $this->acl->allow($this->getSession()->role_id,new Honeycomb_Acl_Resource_Mvc($node['controller'],$node['module']),$node['action']);
               
            }catch (Exception $e)
            {
                l('something went wrong with removeAllow or allow with ACL');
                throw $e;
            }
            
        
            
        }else {
            
            foreach ($data['children'] as $subdata) $this->recursiveData($subdata,$deny);
            
        }
        
        
    }
  
    
    public function treeAction()
    {
    if($this->getRequest()->isXmlHttpRequest())
   {
     //Zend_Debug::dump($this->_getAllParams());exit;
     $aclTree = new Admin_Model_RoleTree(2);
    $this->_helper->json($aclTree->getData());
   }
    }
    
    public function loginAction()
    {
    	
    	$form = new Default_Form_Login();
    	if($this->getRequest()->isPost())
    	{
    		if($form->isValid($_POST))
    		{
    			
    			$authAdapter = new Honeycomb_Auth();
    			
    			if(!$authAdapter->checkForm($form))
    			{
    				print_r($authAdapter->getErrors());
    			}else {
    				//die(Zend_Auth::getInstance()->getIdentity()->first_name);
    			}
    		}
    	}
    	
    	echo $form;
    	
    }
    
    public function appformAction()
    {
      $this->view->addHelperPath('Honeycomb/View/Helper','Honeycomb_View_Helper');

    }
    
    public function foobar()
    {
    	
    }
    
    public function testAction()
    {
    	
    }
    
    
    /**
     * Show all users to select from 
     * after selecting the user it redirect to roleAction 
     * 
     * @name Read User
     * @acl Select user to edit there roles
     */
    public function userAction()
    {
        $this->view->users = new Zend_Form_Element_Select('user');
        $usersTb = new Default_Model_DbTable_Users();
        $users = $usersTb->fetchAll();
        
        foreach($users as $user)
        {
            $option = $this->_helper->url('role','index','default',array('user_id'=>$user->user_id));
            $this->view->users->addMultiOption($option,$user->email);
            
        }
    }
    
    /**
     * Takes input user_id from GET request for the user 
     * enable selecting multiple roles once the form is submited it 
     * redirecters towards sortable action
     * 
     */
    
    public function roleAction()
    {
        $this->getSession()->user_id = $this->_getParam('user_id',$this->getSession()->user_id);
        $this->view->form = new Default_Form_RoleSelect($this->_getParam('user_id'));
        $initSelected = $this->view->form->roleSelect->getValue();
        $usersRolesTb = new Default_Model_DbTable_UsersRoles();
    
        if($this->getRequest()->isPost())
        {
            if($this->view->form->isValid($_POST))
            {
                //$this->view->form = null;
                $selected = (array)$this->view->form->roleSelect->getValue();
                //Zend_Debug::dump($selected,'selected');
               
                $removed = array_diff($initSelected, $selected);
                $added = array_diff($selected,$initSelected);
                $selectedUser = $this->_usersTb->find($this->getSession()->user_id)->current();
                //$this->_helper->redirector('sort');
                
                l('roles removed',$removed);
                l('roles added',$added);
                if(!empty($removed))
                $selectedUser->removeRoles($removed);
                
                if(!empty($added))
                $selectedUser->addRoles($added);
                
                
                foreach($selected as $order => $roleId)
                {
                	
                	$role = $this->_usersRolesTb->find($this->getSession()->user_id,$roleId)->current();
                	
                	$role->sort_order = $order + 1 ; //+1 so no role with sort_order 0 i.e specific to the user remain intact
                	$role->save();
                	
                }
                     
               $this->_forward('user'); 
            }
        }
       
        
    }
    

    
    public function sortAction()
    {
    	
    }
    
    


}

