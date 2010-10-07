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
	 * @param int $indirectCallDepth the count of methods in call stack to be
	 *   excluded from stored call stack
	 * @see getLog()
	 */
	public static function log($message, $indirectCallDepth = 0) {
		self::$_log[] = new LogItem($message, $indirectCallDepth+1);
	}
	
	/**
	 * Gets an array of all log items
	 * 
	 * @return array
	 */
	public static function getLog() {
		return self::$_log;
	}
}