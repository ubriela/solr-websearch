<?php

class Log extends Zend_Log {

    protected static $_instance = null;
    protected $_enabled = true;

    public static function getInstance() {
	if (self::$_instance == null) {
	    self::$_instance = new Log();
	}
	return self::$_instance;
    }

    public function __construct() {
	$writer = new Log_Writer_Console();
	parent::__construct($writer);
	$enable = Zend_Registry::get("configuration")->log->enable;
	$this->setEnable($enable);
    }

    public static function dumpLog($message, $type = null) {
//	echo "LOG";
	$logger = self::getInstance();
	if ($type == null)
	    $type = Zend_Log::INFO;
	if ($logger->getEnable() || $type == Zend_Log::ERR) {
	    $logger->log($message, $type);
	}
    }

    public function getEnable() {
	return $this->_enabled;
    }

    public function setEnable($enable = true) {
	$this->_enabled = $enable;
    }

}

?>