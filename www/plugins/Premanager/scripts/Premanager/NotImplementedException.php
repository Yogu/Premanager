<?php
namespace Premanager;

/**
 * Is thrown when a method is called that is not yet implemented
 */
class NotImplementedException extends Exception {
	/**
	 * Creates a new NotImplementedException
	 * 
	 * @param string $message an exception message
	 */
	public function __construct($message = '') {
		parent::__construct($message);
	}
}

?>