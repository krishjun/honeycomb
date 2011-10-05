<?php
class Default_Form_RegisterForm extends Zend_Form
{
	public function init()
	{
		$this->setMethod('POST')
		     ->setName('Register');
		     
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Email')
		      ->setRequired(true)
		      ->addValidator(new Zend_Validate_EmailAddress())
		      ->addValidator(new Honeycomb_Validate_UniqueColumn(new Default_Model_DbTable_Users(), 'email'));

		$company = new Zend_Form_Element_Text('company');
		$company->setLabel('Company Name : ')
		        ->addValidator(new Zend_Validate_Alnum());

	    $password = new Zend_Form_Element_Password('password');
	    $password->setLabel('Password')
	             ->setRequired(true);
	             
	    $rePassword = new Zend_Form_Element_Password('rePassword');
	    $rePassword->setLabel('Retrype Password')
	               ->addValidator(new Zend_Validate_Identical(Zend_Controller_Front::getInstance()->getRequest()->getParam('password',null)))
	               ->setRequired(true);

	    $submit = new Zend_Form_Element_Submit('Register');
	    
	    $this->addElements(array($email,$company,$password,$rePassword,$submit));
        
	    
	    
		             
	}
}