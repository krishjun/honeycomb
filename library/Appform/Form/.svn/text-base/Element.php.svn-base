<?php
class Appform_Form_Element extends Zend_Form_Element
{
	
	public function addClass($className)
	{
	    if($class = $this->getAttrib('class')) $class .= ',';
	    
	    $newClass = $class . $className;
	    $this->setAttrib('class',$newClass);
	}
}