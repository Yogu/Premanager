<?php
namespace Premanager\IO;

use Premanager\Exception;

/**
 * Is thrown when a I/O error occurs
 */
class IOException extends Exception {
	/**
	 * Creates a new IOException
	 * 
	 * @param string $message if specified, a custom exception message
	 */
	public function __construct($message = '') {
		parent::__construct($message);
	}
}

?>