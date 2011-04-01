<?php
namespace Premanager;

/**
 * Is thrown when a method of a disposed object is called
 */
class DisposedException extends Exception {
	/**
	 * Creates a new DisposedException
	 * 
	 * @param string $message an exception message
	 */
	public function __construct($message) {
		parent::__construct($message);
	}
}

?>