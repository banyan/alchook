<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
require_once APPLICATION_PATH . "/configs/environment.php";

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library/my'),
    realpath(APPLICATION_PATH . '/../library/zend'),
    get_include_path(),
)));

require_once APPLICATION_PATH . '/lib/functions.php';

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);


$front = Zend_Controller_Front::getInstance();

$application->bootstrap()
            ->run();

