<?php
namespace Premanager\IO;

use Premanager\Exception;

/**
 * Is thrown when a server-sided data source contains invalid data 
 */
class CorruptDataException extends Exception {
	/**
	 * Creates a new CorruptDataException
	 * 
	 * @param string $message if specified, a custom exception message
	 */
	public function __construct($message = '') {
		parent::__construct($message);
	}
}

?>