<?php
class LoginController extends  Default_Library_Controller_Action
{
    protected $_adapter;
    protected $_userTb;
    
    public function init()
    {
        
        $this->_adapter = new Zend_Auth_Adapter_DbTable();
        $this->_adapter->setTableName('users')
                       ->setIdentityColumn('email')
                       ->setCredentialColumn('password');
       $this->_userTb = new Default_Model_DbTable_Users();
    
    
                       
    }
    
    public function indexAction()
    {
        //$user = Zend_Auth::getInstance()->getIdentity();
        /* @var $user Zend_Db_Table_Row */
   
        $form = new Default_Form_Login();
        
        if($this->getRequest()->isPost())
        {
            if($form->isValid($this->_getAllParams()))
            {
                $this->_adapter->setIdentity($form->getValue('email'))
                               ->setCredential($form->getValue('password'));
                $result = Zend_Auth::getInstance()->authenticate($this->_adapter); 
                    

                if($result->isValid())
                {
                    //login success
                    $row  = $this->_adapter->getResultRowObject();
                    $user = $this->_userTb->find($row->user_id)->current();
                    Zend_Auth::getInstance()->getStorage()->write($user);
                    $this->_helper->flashMessenger("foo");
                   
                    $this->_helper->redirector('index','index','default'); //redirection after login is important so in next request acl works from first request otherwise the current on going request will take into roles of guest into account since acl is in predispatch
                    
                    //Zend_Debug::dump($user->getRoles());
                    
                   
                    
                    $form = null; //hide the form
                    
                    
                }else {
                    
                    $this->view->loginFail = "Incorrect Email or Password !! ";
                    
                }
            }  
        }
        
        $this->view->form = $form;
    }
    
    public function logoutAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        
        if(Zend_Auth::getInstance()->hasIdentity())  Zend_Auth::getInstance()->clearIdentity();
        
        $this->_helper->redirector('index');
    }
    
    
    public function registerAction()
    {
    	$this->view->form = new Default_Form_RegisterForm();
    	
    	if($this->getRequest()->isPost())
    	{
    		if($this->view->form->isValid($this->_getAllParams()))
    		{
    			
    		    $usersTb = new Default_Model_DbTable_Users();
    		    $userId = $usersTb->registerUser($this->view->form->getValues());
    		    
    		    $this->view->form = null;
    		}
    	}
    		
    }
    

}