<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
    
defined('PUBLIC_DIR')
    || define('PUBLIC_DIR', realpath(dirname(__FILE__)));    

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);


$application->bootstrap()
            ->run();
            
            
 function l($key,$value = null)
 {
 	$writer = new Zend_Log_Writer_Firebug();
 	$logger  = new Zend_Log();
 	$logger->addWriter($writer);
 	$logger->log($key, Zend_Log::INFO);
 	if($value) $logger->log($value,Zend_Log::DEBUG);
 	return;
 }           