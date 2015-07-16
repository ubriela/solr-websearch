<?php
define('BASE_PATH', realpath(dirname(__FILE__)));//public folder
define('ROOT_PATH', BASE_PATH);//base folder
define('APP_ENV',$_SERVER["APP_ENV"]);
set_include_path('/zserver/php/lib/corelib/' . PATH_SEPARATOR . get_include_path());
set_include_path(get_include_path() . PATH_SEPARATOR .BASE_PATH.'/Lib');
define('GEN_DIR', 'Zing/Packages/Generator');
define('CAS_DIR', 'Apache/Cassandra');
define('SES_DIR', 'Zing/Packages/Session');
define('XCACHE_DIR', 'Zing/Packages/Xcache');
define('LIB_PACKAGES_DIR',  'Zing/Packages');
define('STDPROFILE_DIR', 'Zing/Packages/stdprofile');
define('EXPROFILE_DIR', 'Zing/Packages/exprofile');

$GLOBALS['THRIFT_ROOT'] = 'Apache/Thrift';
$GLOBALS['GENERATOR_ROOT'] = GEN_DIR;
$GLOBALS['CASSANDRA_ROOT'] = CAS_DIR;
$GLOBALS['SESSION_ROOT'] = SES_DIR;
$GLOBALS['HBASE_ROOT'] = HBASE_DIR;
$GLOBALS['XCACHE_ROOT'] = XCACHE_DIR;

$GLOBALS['LIB_PACKAGES_ROOT'] = LIB_PACKAGES_DIR;
$GLOBALS['STDPROFILE_ROOT'] = STDPROFILE_DIR;
$GLOBALS['EXPROFILE_ROOT'] = EXPROFILE_DIR;

require_once $GLOBALS['THRIFT_ROOT'].'/Thrift.php';
require_once $GLOBALS['THRIFT_ROOT'].'/protocol/TBinaryProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'].'/transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'].'/transport/THttpClient.php';
require_once $GLOBALS['THRIFT_ROOT'].'/transport/TBufferedTransport.php';
require_once $GLOBALS['THRIFT_ROOT'].'/transport/TFramedTransport.php';

require_once "Zend/Loader.php";

require_once BASE_PATH ."/SyncWorker.php";
Zend_Loader::registerAutoload();
$sync_worker = new SyncWorker('./'.APP_ENV.'.global.ini',APP_ENV);
$sync_worker->run();
?>