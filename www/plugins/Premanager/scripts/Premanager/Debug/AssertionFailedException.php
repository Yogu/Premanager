<?php
namespace Premanager\Debug;

use Premanager\Exception;

/**
 * Is thrown when a code design error is detected
 */
class AssertionFailedException extends Exception {
	/**
	 * Creates a new AssertionFailedException
	 *
	 * @param string $message if specified, a custom exception message
	 */
	public function __construct($message = '', $paramName = '') {
		parent::__construct($message ? $message : 'Assertion failed');
	}
}

?>