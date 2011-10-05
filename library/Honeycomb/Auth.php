<?php
class Honeycomb_Auth extends Zend_Auth_Adapter_DbTable
{
	protected $_result;
	
	public function __construct()
	{
		parent::__construct(Zend_Db_Table::getDefaultAdapter());
		$this->setTableName('users')
		     ->setIdentityColumn('email')
		     ->setCredentialColumn('password')
		     ->setCredentialTreatment('MD5(?)');
	}
	
	public function setEmail($email)
	{
		return  $this->setIdentity($email);
	}
	
	public function setPassword($password)
	{
		return $this->setCredential($password);
	}
	
	public function check()
	{
		$this->_result = Zend_Auth::getInstance()->authenticate($this);
		if($this->_result->isValid())
		{
			$userRow = $this->getResultRowObject();
			$userTb = new Default_Model_DbTable_Users();
			$user = $userTb->find($userRow->user_id)->current();
			Zend_Auth::getInstance()->getStorage()->write($user);
			return true;
			
		}
		return false;
	}
	
	public function checkForm(Zend_Form $form)
	{
		return $this->setEmail($form->getValue('email'))
		            ->setPassword($form->getValue('password'))
		            ->check();
		           
		           
	}
	
	public function getErrors()
	{
		return $this->_result->getMessages();
	}
	
	public function getResult()
	{
		return $this->_result;
	}
}