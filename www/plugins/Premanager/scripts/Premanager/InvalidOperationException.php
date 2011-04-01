<?php
namespace Premanager;

/**
 * Is thrown when the call of a method is invalid at the current object state
 */
class InvalidOperationException extends Exception {
	/**
	 * Creates a new InvalidOperationException
	 * 
	 * @param string $message if specified, a custom exception message
	 */
	public function __construct($message = '') {
		parent::__construct($message);
	}
}

?>