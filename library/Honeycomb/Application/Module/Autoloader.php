<?php
class Honeycomb_Application_Module_Autoloader extends Zend_Application_Module_Autoloader
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->initDefaultResourceTypes();
    }

    /**
     * Initialize default resource types for module resource classes
     *
     * @return void
     */
    public function initDefaultResourceTypes()
    {
        $basePath = $this->getBasePath();
        $this->addResourceTypes(array(
            'library' => array(
                'namespace' => '',
                'path'      => 'library',
            ),
        
            'dbtable' => array(
                'namespace' => 'Model_DbTable',
                'path'      => 'models/DbTable',
            ),
            'mappers' => array(
                'namespace' => 'Model_Mapper',
                'path'      => 'models/mappers',
            ),
            'form'    => array(
                'namespace' => 'Form',
                'path'      => 'forms',
            ),
            'model'   => array(
                'namespace' => 'Model',
                'path'      => 'models',
            ),
            'plugin'  => array(
                'namespace' => 'Plugin',
                'path'      => 'plugins',
            ),
            'service' => array(
                'namespace' => 'Service',
                'path'      => 'services',
            ),
            'viewhelper' => array(
                'namespace' => 'View_Helper',
                'path'      => 'views/helpers',
            ),
            'viewfilter' => array(
                'namespace' => 'View_Filter',
                'path'      => 'views/filters',
            ),
        ));
        $this->setDefaultResourceType('model');
    }
}