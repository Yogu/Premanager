<?php 
namespace Premanager\IO\DataBase;

use Premanager\Exception;
use Premanager\IO\IOException;

/**
 * Is thrown when an error related to data base occurs
 */
class DataBaseException extends IOException {
	/**
	 * Creates a new DataBaseException
	 * 
	 * @param string $message if specified, a custom exception message
	 */
	public function __construct($message = '') {
		parent::__construct($message);
	}
}

?>