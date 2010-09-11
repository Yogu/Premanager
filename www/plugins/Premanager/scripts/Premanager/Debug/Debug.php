<?php
namespace Premanager\Debug;

class Debug {
	private static $_log = array();
	
	/**
	 * Throws an AssertionFailedException if a condition is false
	 * 
	 * @param bool $condition the condition to check
	 * @param string $message an optional description for the assertion
	 */
	public static function assert($condition, $message = '') {
		if (!$condition)
			throw new AssertionFailedException($message);
	}
	
	/**
	 * Adds a string to the log
	 * 
	 * @param string $message the message to log
	 * @see getLog()
	 */
	public static function log($message) {
		self::$_log[] = new LogItem($message);
		
		//TODO: remove this line when log works
		//var_dump($message);
		echo "<b>Log:</b> $message<br />\n";
	}
	
	/**
	 * Gets an array of all log items
	 * 
	 * @return array
	 */
	public static function getLog() {
		return $this->_log;
	}
}