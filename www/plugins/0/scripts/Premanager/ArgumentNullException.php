<?php
namespace Premanager;

/**
 * Is thrown when the value of an argument is null, but this is not a valid
 * value for the argument.
 *
 */
class ArgumentNullException extends Exception {
	private $_paramName;
	
	/**
	 * Creates a new ArgumentNullException
	 * 
	 * @param string $paramName the name of the parameter that caused this
	 *   exception
	 * @param string $message if specified, a custom exception message
	 */
	public function __construct($paramName = '', $message = '') {
		parent::__construct($message ? $message : $paramName ?
			'Value for argument $'.$paramName.' is null' : '');
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