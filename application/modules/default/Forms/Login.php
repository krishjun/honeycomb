<?php
class Default_Form_Login extends  Zend_Form
{
	public function init()
	{
		$email = new Zend_Form_Element_Text('email');
		$email->addValidator(new Zend_Validate_EmailAddress())
		      ->setLabel('Email')
		      ->isRequired();

		$password = new Zend_Form_Element_Password('password');
		$password->setLabel('Password')
		        ->setRequired(TRUE);

		$submit = new Zend_Form_Element_Submit('Login');
		$submit->setValue('Login');
		
		$this->addElements(array($email,$password,$submit));
	}
}