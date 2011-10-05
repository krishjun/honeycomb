<?php
class Appform_Form_Element_Textarea extends Zend_Form_Element_Textarea
{
	public function addClass($className)
	{
	    if($class = $this->getAttrib('class')) $class .= ' ';
	    
	    $newClass = $class . $className;
	    $this->setAttrib('class',$newClass);
	    return $this;
	}
	
	
}