<?php
namespace Premanager\Debug;

use Premanager\Module;

/**
 * A log item
 */
class LogItem extends Module {
	private $_message;
	private $_time;
	
	/**
	 * The message of this log item
	 * 
	 * This property is read-only.
	 * 
	 * @var string
	 */
	public $message = Module::PROPERTY_GET;
	
	/**
	 * The time this log item has been created
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $time = Module::PROPERTY_GET;
	
	/**
	 * Creates a new LogItem
	 * 
	 * @param string $message the message to log
	 */
	public function __construct($message) {
		$this->_message = $message;
		$this->_time = time();
	}
	
	/**
	 * Gets the message of this log item
	 * @return string
	 */
	public function getMessage() {
		return $this->_message;
	}
	
	/**
	 * Gets the time this log item has been created
	 * @return int
	 */
	public function getTime() {
		return $this->_time;
	}
}

?>