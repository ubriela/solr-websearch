<?php
define('BASE_PATH', realpath(dirname(__FILE__)));//public folder
define('ROOT_PATH', BASE_PATH);//base folder
define('ZENDLIB_PATH', ROOT_PATH . "/Zend/");//base folder
define('APP_ENV',$_SERVER["APP_ENV"]);
set_include_path(get_include_path() . PATH_SEPARATOR .ROOT_PATH.'/Zend');
set_include_path(get_include_path() . PATH_SEPARATOR .BASE_PATH.'/Lib');
define('STDPROFILE_DIR', BASE_PATH . '/application/models/stdprofile');
$GLOBALS['STDPROFILE'] = STDPROFILE_DIR;
$GLOBALS['THRIFT_ROOT'] = ZENDLIB_PATH.'Apache/Thrift';
require_once "Zend/Loader.php";
require_once "Globals.php";
require_once BASE_PATH ."/SyncWorker.php";
Zend_Loader::registerAutoload();
$configuration = new Zend_Config_Ini(ROOT_PATH . '/'.APP_ENV.'.global.ini',APP_ENV);
Zend_Registry::set("configuration",$configuration);

try {
	$gserver = $configuration->gearman->server;
	$gport = $configuration->gearman->port;
	$client = new GearmanClient();
	$client->addServer($gserver,$gport);
// run reverse client in the background
	$jobs = $configuration->job;

// get input data
	$input_file_name = $_SERVER['argv'][1];
	$num_of_item_sleep = 1000;

// read file
	$handle = @fopen($input_file_name, "r");
	if ($handle) {
		$i=0;
		while (!feof($handle)) {
			$i++;
			// read with limit item pertime
			$userid = intval(fgets($handle));
			if(empty($userid))
				continue;
			$data = array("userid"=>$userid,"flag"=>1);
			$data = Zend_Json::encode($data);
			$job_handle = $client->doBackground($jobs->sync_search, $data);
			echo "Push userid = $userid to gearman update profile, Job handle = $job_handle\n";
			$data = array("userid"=>$userid,"flag"=>2);
			$data = Zend_Json::encode($data);
			$job_handle = $client->doBackground($jobs->sync_search, $data);
			echo "Push userid = $userid to gearman update occupation, Job handle = $job_handle\n";
			$data = array("userid"=>$userid,"flag"=>3);
			$data = Zend_Json::encode($data);
			$job_handle = $client->doBackground($jobs->sync_search, $data);
			echo "Push userid = $userid to gearman update knowlegde, Job handle = $job_handle\n";
			echo "\n";
			if($i == $num_of_item_sleep) {
				$i=0;
				echo "SLEEP 1/10s !!!!";
				usleep(100000);
			}
		}
		fclose($handle);
	}
	else {
		die("Error CANNOT READ FILE $input_file_name");
	}
}
catch ( Exception $ex) {
	die($ex->getMessage());
}
?>
