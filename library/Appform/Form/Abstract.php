<?php
abstract class Appform_Form_Abstract extends Zend_Form
{

	public function setMvcAction($module,$controller,$action)
	{
		$url = $this->getView()->url(array('module'=>$module,'controller'=>$controller,'action'=>$action),'default',true);
		$this->setAction($url);
	}
	
	public function getSubmitBtn($value = 'submit')
	{
		$submit = new Zend_Form_Element_Submit($value);
		$submit->setValue($value);
		return $submit;
	}
	
	public function __construct($options = null)
	{
	    parent::__construct($options);
	    $view = $this->getView();
	    $view->headScript()->appendFile($view->baseUrl('review/js/jquery.validationEngine.js'))
	                       ->appendFile($view->baseUrl('review/js/languages/jquery.validationEngine-en.js'));

	    $view->headLink()->appendStylesheet($view->baseUrl('review/css/validationEngine.jquery.css'));                   
	                       
	}
	
	/**
     * @override
	 */
	public function setAttrib($key, $value)
	{
	    parent::setAttrib($key, $value);
	    if($key == 'id')
	    {
	        $view = $this->getView();
$str = <<<EOD
$(function(){
$("#$value").validationEngine();
});
EOD;
	        
	        
/*$script = <<<EOD
$(function(){
$(#$value).validationEngine();
});
EOD;	*/        
	       
$view->headScript()->appendScript($str);

	    }
	    return $this;
	}
	
	
	
	


}