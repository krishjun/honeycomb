<?php
class Honeycomb_Controller_Action_Helper_Acl extends Zend_Controller_Action_Helper_Abstract
{
    
    const DEFAULT_CONTROLLER = 'error';
    const DEFAULT_MODULE = 'default';
    const DEFAULT_ACTION = 'deny';
    
    
    
    public function preDispatch()
    {
       $request = $this->getFrontController()->getRequest();
             
       if($request->getControllerName() == "error") return; //error fix 

        if(!Honeycomb_Acl::getInstance()->isOk($request->getActionName(),$request->getControllerName(),$request->getModuleName()))
       {
       
           $request->setActionName(self::DEFAULT_ACTION)
                   ->setControllerName(self::DEFAULT_CONTROLLER)
                   ->setModuleName(self::DEFAULT_MODULE);
                   l("Acl Access Deinied");
                   return;
       } 
       
       l('Acl Acess Allowed');
       
       
       return;
    }
}