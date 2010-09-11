<?php
namespace Premanager;

/**
 * Is thrown when the value of a numeric argument is not in the expected range
 */
class ArgumentOutOfRangeException extends Exception {
	private $_paramName;
	private $_actualValue;
	
	/**
	 * Creates a new ArgumentOutOfRangeException
	 * 
	 * @param string $paramName the name of the parameter that caused this
	 *   exception
	 * @param mixed $actualValue the argument value that caused this exception
	 * @param string $message if specified, a custom exception message
	 */
	public function __construct($paramName = '', $actualValue = null,
		$message = 'A value is out of range') {
		parent::__construct(
			$message.
			($paramName || $actualValue ? (' ('.
				($paramName ? "Parameter: ".$paramName : '').
				($paramName && $actualValue ? "; " : '').
				($actualValue ? "Actual Value: ".$actualValue : '').')') : ''));
		$this->_paramName = $paramName;
		$this->_actualValue = $actualValue;
	}
	
	/**
	 * Gets the name of the parameter that caused this exception
	 * @return string the name of the parameter that caused this exception
	 */
	public function getParamName() {
		return $this->_paramName;
	}
	
	/**
	 * Gets the argument value that caused this exception
	 * @return mixed the value of the argument that caused this exception
	 */
	public function getActualValue() {
		return $this->_actualValue;
	}
}

?>