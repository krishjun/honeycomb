<?php
class Honeycomb_Application_Module_Bootstrap extends  Zend_Application_Module_Bootstrap
{
	public function _initHoneycombResourceTypes()
	{
		$this->getResourceLoader()->addResourceType('library', 'library/','Library');
	}
}