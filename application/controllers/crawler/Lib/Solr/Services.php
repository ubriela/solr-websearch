<?php

/*
  Search Model
  Solr Connector
 */

class Solr_Services {

    private $_solrService;

    public function __construct($config) {
	$this->initService($config);
    }

    /* factory */

    private function initService($config) {
	/* need parameterize */
	$this->_solrService = new Apache_Solr_Service($config["host"], $config["port"], $config["path"]);
	if (!$this->_solrService->ping()) {
	    echo "Solr service not responding.\n";
	    exit;
	}
    }

    /* Admin Functions */

    public function addDocuments($datas) {
	$documents = array();
	foreach ($datas as $item => $fields) {
	    $part = new Apache_Solr_Document();
	    foreach ($fields as $key => $value) {
		if (is_array($value)) {
		    foreach ($value as $datum) {
			$part->setMultiValue($key, $datum);
		    }
		} else {
		    $part->$key = $value;
		}
	    }
	    $documents[] = $part;
	}
	try {
	    $rs = $this->_solrService->addDocuments($documents);
	    // asynchronous commit
	    //$this->_solrService->commit(true);

	    return $rs;
	} catch (Exception $e) {
	    return $e->getMessage();
	}
    }

    public function addDocument($data) {
	$document = new Apache_Solr_Document();
	foreach ($data as $key => $value) {
	    if (is_array($value)) {
		foreach ($value as $datum) {
		    if (is_numeric($datum))
			number_format ($datum);
		    $document->setMultiValue($key, $datum);
		}
	    } else {
		if (is_numeric($value))
			number_format ($value);
		$document->$key = $value;
	    }
	}

	try {
	    $rs = $this->_solrService->addDocument($document);
	    // asynchronous commit
	    // $this->_solrService->commit(true);

	    return $rs;
	} catch (Exception $e) {
	    return $e->getMessage();
	}
    }

    public function removeDocument($documentid) {
	return $this->_solrService->deleteById($documentid);
    }

    /* Search Functions */

    public function search($query, $from=0, $count=10, $params=array()) {
	return $this->_solrService->search($query, $from, $count, $params);
    }

}

?>