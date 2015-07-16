<?php

class Application_Model_GuestbookMaper
{
    protected $_dbTable;

    public function get_dbTable() {
	return $this->_dbTable;
    }

    public function set_dbTable($dbTable) {
	if (is_string($dbTable)) {
	    $dbTable = new $dbTable();
	}

	if (!$dbTable instanceof Zend_Db_Table_Abstract) {
	    throw new Exception("Invalid dbname");
	}
	$this->_dbTable = $dbTable;
	return $this;
    }

}

