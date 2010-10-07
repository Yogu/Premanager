<?php
namespace Premanager;

/**
 * Is thrown when an argument of a method has an invalid value
 */
class ArgumentException extends Exception {
	private $_paramName;
	
	/**
	 * Creates a new ArgumentException
	 * 
	 * @param string $message if specified, a custom exception message
	 * @param string $paramName the name of the parameter that caused this
	 *   exception
	 */
	public function __construct($message = '', $paramName = '') {
		parent::__construct($message .
			($paramName ? (" (Parameter: ".$paramName.")") : ''));
		$this->_paramName = $paramName;
	}
	
	/**
	 * Gets the name of the parameter that caused this exception
	 * @return string the name of the parameter that caused this exception
	 */
	public function getParamName() {
		return $this->_paramName;
	}
}

?>