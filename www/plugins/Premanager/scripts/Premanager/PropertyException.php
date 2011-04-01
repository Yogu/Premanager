<?php
namespace Premanager;

/**
 * Is thrown when the access to a property fails
 */
class PropertyException extends Exception {
	private $_propertyName;
	
	/**
	 * Creates a new PropertyException
	 * 
	 * @param string $message if specified, a custom exception message
	 * @param string $propertyName the name of the accessed property
	 */
	public function __construct($message = '', $propertyName) {
		parent::__construct($message);
		$this->_propertyName = $propertyName;
	}
	
	/**
	 * Gets the name of the accessed property
	 * 
	 * @return string
	 */
	public function getPropertyName() {
		return $this->_propertyName;
	}
}

?>