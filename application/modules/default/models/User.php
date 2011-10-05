<?php
class Default_Model_User extends Zend_Db_Table_Row_Abstract
{
   protected $_usersRolesTb;
   protected $_rolesTb;
   protected $_db;
   protected $_isSuperadmin;
   
  
   
   
   public function __construct($config = null)
   {
       parent::__construct($config);
       $this->_usersRolesTb = new Default_Model_DbTable_UsersRoles();
       $this->_rolesTb = new Default_Model_DbTable_Roles();
       $this->_db = Zend_Db_Table::getDefaultAdapter();

       
   }
   
   public function __wakeup()
   {
       parent::__wakeup();
      $this->_db = Zend_Db_Table::getDefaultAdapter();
      $this->_usersRolesTb = new Default_Model_DbTable_UsersRoles();
      $this->_rolesTb = new Default_Model_DbTable_Roles();
   }
    
   /**
    * 
    * TODO will store roles later in instance variable for runtime caching
    */
    public function getRoles()
    {
        
        //not using specific table object coz we need some data from pevot table aswell
        return $this->_db->select()->from('users',array())
                           ->join('users_roles','users_roles.user_id = users.user_id')
                           ->join('roles','roles.role_id = users_roles.role_id')
                           ->where('users.user_id = ?',$this->user_id)
                           ->order('users_roles.sort_order')
                           ->query()
                           ->fetchAll(Zend_Db::FETCH_OBJ);
      //  return  $this->findManyToManyRowset(new Default_Model_DbTable_Roles(),$this->_usersRolesTb,$db->select()->order('users_roles.sort_order'));
    }
    
    

	
	/**
	 * 
	 * A new role to user getting assinged
	 * @param Zend_Db_Table_Row $role
	 * @param unknown_type $sortOrder
	 */
    
    
    public function addRole(Zend_Db_Table_Row $role,$sortOrder = null)
    {
    	
        $userRole = $this->_usersRolesTb->createRow();
        
        if($sortOrder == null)
        {
           //find the last sort order
           $sortOrder = $this->_usersRolesTb->select()
                                            ->from($this->_usersRolesTb,'sort_order')
                                            ->where('user_id = ?',$this->user_id)
                                            ->limit(1)
                                            ->order('sort_order DESC')
                                            ->query()
                                            ->fetchColumn();  
                                            
        }
        
        $userRole->user_id = $this->user_id;
        $userRole->role_id = $role->role_id;
        $userRole->sort_order = $sortOrder;
        return $userRole->save();
    }
    
    public function removeRole(Zend_Db_Table_Row $role)
    {
        //removing role means decreasing all roles > $role->sort_order by one
        //$sortOrder = $role->sort_order;
        //Zend_Debug::dump($role->role_id . "and user ID " . $this->user_id);exit;
        $userRole = $this->_usersRolesTb->find($this->user_id,$role->role_id)->current();
        //Zend_Debug::dump($userRole->toArray(),"User role");
        $sortOrder = $userRole->sort_order;
        $ret = $userRole->delete();
       // echo $ret ? "Deleted " : "not deleted";
/*        $this->_usersRolesTb->update(array('sort_order'=> new Zend_Db_Expr('sort_order - 1')),
                                                array('user_id = $this->user_id', "sort_order > $sortOrder"));*/
                                                 
                                                 
        
    }
    
    public function isSuperadmin()
    {
        if($this->_isSuperadmin === null)
        {
           
            foreach($this->getRoles() as $role)
            {
                if($role->name == 'superadmin') $this->_isSuperadmin = true;
            }
        }
        
        return $this->_isSuperadmin;
    }
    
    public function addRoles($rolesId)
    {
    
    	
    	foreach($rolesId as $role)
    	{
    	  $role = $this->_rolesTb->find($role)->current();
    	  $this->addRole($role);
    	  
    	}
    }
    
    public function removeRoles($rolesId)
    {
    
   
        foreach($rolesId as $role)
        {
           $role = $this->_rolesTb->find($role)->current();
    	   $this->removeRole($role);
        }
    }
    
    public function getCompanyId()
    {
      //$this->findDependentRowset(new Default_Model_DbTable_Company());
      $this->findParentRow(new Default_Model_DbTable_Company())->company_id;
    }
}