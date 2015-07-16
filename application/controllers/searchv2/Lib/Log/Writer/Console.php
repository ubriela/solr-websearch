<?php
/** Zend_Log_Writer_Abstract */
require_once 'Zend/Log/Writer/Abstract.php';

/** Zend_Log_Formatter_Simple */
require_once 'Zend/Log/Formatter/Simple.php';
class Log_Writer_Console extends Zend_Log_Writer_Abstract
{
    /**
     * Holds the PHP stream to log to.
     * @var null|stream
     */
    protected $_formatter = null;
	
    /**
     * Class Constructor
     *
     * @param  streamOrUrl     Stream or URL to open as a stream
     * @param  mode            Mode, only applicable if a URL is given
     */
    public function __construct()
    {
       $this->_formatter = new Zend_Log_Formatter_Simple();
    }

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    protected function _write($event)
    {
		$line = $this->_formatter->format($event);
		echo $line;
    }
}
