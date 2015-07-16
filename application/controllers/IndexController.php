<?php

class IndexController extends Zend_Controller_Action {

    public $_configuration = null;

    public function init() {
	/* Initialize action controller here */
    }

    public function bdsAction() {
	// action body
//	$this->_helper->viewRenderer->setNoRender();
//	echo 'Here';
//require_once 'Zend/Loader.php';
//Zend_Loader::registerAutoload();


	$row = 1;
	if (($handle = fopen("/home/hientt/public_html/BDSIndex/library/estate2.csv", "r")) !== FALSE) {

	    $config = array(
		'host' => 'localhost',
		'port' => '8983',
		'path' => '/solr/standard/',
	    );
	    $doc_service = new Solr_Services($config);

	    echo count($handle) . '&nbsp; documents </br>';
	    while (($data = fgetcsv($handle, 10000, ",", "\"")) !== FALSE) {
		$num = count($data);
		$row++;

		if ($row == 2) {
		    $doc = array();
		    $mapindex = $data;
		    for ($c = 0; $c < $num; $c++) {
			$doc[$data[$c]] = 0;
		    }
		    continue;
		}

		if ($num != 27) {
		    echo "<p> Row $row has $num fields <br /></p>\n";
		    continue;
		}

//	$configuration = new Zend_Config_Ini('./global.ini', './');
//	Zend_Registry::set('configuration', $configuration->solr);
//	if (!$configuration) {
//	    Log::dumpLog("Config file not found or enviroment not found!!!", Zend_Log::ERR);
//	}

		foreach ($data as $field => $value) {
		    if ($value) {
			$doc[$mapindex[$field]] = html_entity_decode($value);
		    }
		};

//	foreach ($doc as $field => $value) {
//	    echo $field . '&nbsp;' . $value;
//	    echo "</br>";
//	};

		$doc_service->addDocument($doc);

		if ($row % 1000 == 0) {
		    echo "<p>Processed&nbsp; $row &nbsp;rows</p>\n";
		    //	Create a solr document to index
		}
	    }
	    fclose($handle);
	}
    }

    public function testlogAction() {
	$this->_helper->viewRenderer->setNoRender();

//	echo CONFIG_PATH;
	$options['nestSeparator'] = ':';
	$this->_configuration = new Zend_Config_Ini(CONFIG_PATH . '/application.ini', 'application', $options);

	Zend_Registry::set('configuration', $this->_configuration);

	if (!$this->_configuration) {
//	    Log::dumpLog("Config file not found or enviroment not found!!!", Zend_Log::ERR);
	} else {
//	    Log::dumpLog("Get environment successful");
	    $gearman_config = $this->_configuration->toArray();
	    echo $gearman_config['gearman.server'] . "</br>";
	    echo $gearman_config['gearman.port'] . "</br>";
	}

	$config = Zend_Registry::get("configuration")->toArray();
	print_r($config['log.enable']);
    }
}