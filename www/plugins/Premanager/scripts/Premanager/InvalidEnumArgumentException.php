<?php
namespace Premanager;

/**
 * Is thrown when the value of an argument is not one of the enum values
 */
class InvalidEnumArgumentException extends Exception {
	private $_paramName;
	private $_actualValue;
	private $_enumClass;
	
	/**
	 * Creates a new InvalidEnumArgumentException
	 * 
	 * This method can be called with one argument or with three arguments. If
	 * only one argument is specified, it is taken as an error message; for the
	 * three-argument-call, see the argument specification.
	 * 
	 * @param string $paramName the name of the parameter that caused this
	 *   exception - or - an error message
	 * @param int $actualValue the argument value that caused this exception
	 * @param string $enumClass the name of the class that provides the valid enum
	 *   values 
	 */
	public function __construct($paramName = null, $actualValue = null,
		$enumClass = '') {
		if ($actualValue === null && $enumClass === '')
			parent::__construct($message);
		else {
			if ($paramName)
				parent::__construct(
					'Value for argument $'.$paramName.' is not a valid enum value'.
					($actualValue || $enumClass ? '( '.
						($actualValue ? 'Actual value: \''.$actualValue.'\'' : '').
						($actualValue && $enumClass ? '; ' : '').
						($enumClass ? '(Enum class: \''.$enumClass.'\'' : '').
						')' : ''));
			else
				parent::__construct();
			$this->_paramName = $paramName;
			$this->_actualValue = $actualValue;
			$this->_enumClass = $enumClass;
		}
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
	 * @return int the value of the argument that caused this exception
	 */
	public function getActualValue() {
		return $this->_actualValue;
	}
	
	/**
	 * Gets the name of the class that provides the valid enum values
	 * @return string the value of the argument that caused this exception
	 */
	public function getEnumClass() {
		return $this->_enumClass;
	}
}

?>