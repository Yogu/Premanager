<?php
namespace Premanager;

/**
 * Is thrown when an operation is not supported on an object 
 */
class NotSupportedException extends Exception {
	/**
	 * Creates a new NotSupportedException
	 * 
	 * @param string $message an exception message
	 */
	public function __construct($message = '') {
		parent::__construct($message ? $message :
			'This feature is not supported by this object.');;
	}
}

?>