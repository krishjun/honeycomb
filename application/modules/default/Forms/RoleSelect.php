<?php
class Default_Form_RoleSelect extends Zend_Form
{
    protected $_usersTb;
    protected $_rolesTb;
    protected $_usersRoles;
    protected $_userId;
    
    
    public function init()
    {
        $this->setName("RoleSelect")
             ->setMethod('POST');
        $this->_initModels();     

        $multiSelect = new Zend_Form_Element_Multiselect("roleSelect");
        $multiSelect->setAttrib('class', 'multiselect');
       /* $roles = $this->_user->findManyToManyRowset(new Default_Model_DbTable_Roles(), new Default_Model_DbTable_UsersRoles())->toArray();
        $allRoles = $this->_rolesTb->fetchAll();*/
        
      $db = Zend_Db_Table::getDefaultAdapter();
      $rows = $db->select()->from('roles')
                           ->joinLeft('users_roles',"users_roles.role_id = roles.role_id AND users_roles.user_id = {$this->_userId}",array('user_id','sort_order'))
                           //->order('users_roles.user_id')
                           ->order('users_roles.sort_order')
                           ->where('roles.is_user_based = ?',false) //hide the user specific role
                           ->query()
                           ->fetchAll();
            // Zend_Debug::dump($rows,"all data");exit;            
                    
        $setValues = array();
        
           //Zend_Debug::dump($rows);exit;
        foreach($rows as $row)
        {
            $multiSelect->addMultiOption($row['role_id'],$row['name']);
     
            if($row['user_id'] == $this->_userId) $setValues[] = $row['role_id']; 
        }
            
        
       // Zend_Debug::dump($setValues);exit;
        $multiSelect->setValue($setValues);
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setValue('submit');
       $this->addElements(array($multiSelect,$submit));
                      
    }
    
    private function _initModels()
    {
        $this->_usersRoles = new Default_Model_DbTable_UsersRoles();
        $this->_rolesTb = new Default_Model_DbTable_Roles();
        $this->_usersTb = new Default_Model_DbTable_Users();
        
        
    }
    
    public function __construct($userId = null)
    {
        
    
        $this->_userId = $userId;
        parent::__construct();
       
    }
    
}