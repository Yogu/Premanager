<?php
namespace Premanager;

/**
 * The base class for Premanager exceptions
 */
class Exception extends \Exception {
	/**
	 * Creates a new Exception
	 * 
	 * @param string $message if specified, a custom exception message
	 */
	public function __construct($message = '') {
		parent::__construct($message ? $message : 'An exception occured.');
	}
}

?>