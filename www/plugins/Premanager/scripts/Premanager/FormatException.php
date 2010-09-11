<?php
namespace Premanager;

/**
 * Is thrown when an input string has an invalid format
 */
class FormatException extends Exception {
	/**
	 * Creates a new InvalidOperationException
	 * 
	 * @param string $message if specified, a custom exception message
	 */
	public function __construct($message = '', $paramName = '') {
		parent::__construct($message);
	}
}

?>