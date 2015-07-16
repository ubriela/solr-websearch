<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

defined('LIB_PATH', '/home/hientt/public_html/PeopleSearch/lib/');

define('CONFIG_PATH', realpath(dirname(__FILE__) . '/../application/configs'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../application'),
    '/home/hientt/public_html/PeopleSearch/lib/',
    get_include_path(),
//    './crawler/Lib/Solr/Services.php',
//    '../lib/Solr/Service.php',
)));

/** Zend_Application */



require_once 'Zend/Application.php';
require_once 'Zend/Log.php';
require_once '../application/controllers/crawler/Lib/Solr/Services.php';
require_once '../application/controllers/crawler/Lib/Log.php';
require_once 'Solr/Service.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
            ->run();

