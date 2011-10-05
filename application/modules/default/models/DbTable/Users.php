<?php

class Default_Model_DbTable_Users extends Default_Library_DbTable_Abstract
{

    protected $_name = 'users';
    protected $_primary = 'user_id';
    protected $_rowClass = 'Default_Model_User';
    
    /*protected $_referenceMap = array('comapny'=>array('columns'=>'company_id',
                                                        'refColumns')=>'company_id',
                                                         'refTableClass') ;*/
  public function __construct($config = NULL)
  {
  	$this->addRefCol(new Default_Model_DbTable_Company(),'comapny_id');
  	parent::__construct($config);
  }
  
  public function registerUser($input)
  {
  	$newUser = $this->createRow();
  	$newUser->email = $input['email'];
  	$newUser->password = $input['password'];
  	$newUser->save();
  	
  	if($input['company'])
  	{
  		$companyTb = new Default_Model_DbTable_Company();
  		$newCompany = $companyTb->createRow();
  		$newCompany->name = $input['company'];
  		$newCompany->admin_id = $newUser->user_id;
  		$companyId = $newCompany->save();
  		
  		Honeycomb_Acl::getInstance()->loadDefaultRolesForCompany($companyId);
  		//not set one of the loaded role to default
  		
  		
  	    $newUser->company_id = $companyId;
  	    $newUser->save();
  	}
  	
  	$roleId = Honeycomb_Acl::getInstance()->addRole($input['email'],null,Honeycomb_Acl::ROLE_IS_USER_BASED);
  	$usersRoles = new Default_Model_DbTable_UsersRoles();
  	$rel = $usersRoles->createRow();
  	$rel->user_id = $newUser->user_id;
  	$rel->role_id = $roleId;
  	$rel->sort_order = 0;
  	$rel->save();
  	
  }

}

