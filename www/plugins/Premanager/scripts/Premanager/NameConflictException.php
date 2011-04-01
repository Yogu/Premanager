<?php 
namespace Premanager;

/**
 * Is thrown when a operation would cause a name conflict
 */
class NameConflictException extends Exception {
	private $_name;
	
	/**
	 * Creates a new NameConflictException
	 * 
	 * @param string $message if specified, a custom exception message
	 * @param string $name name that causes the conflict
	 */
	public function __construct($message = '', $name = '') {
		parent::__construct($message);
		$this->_name = $name;
	}
	
	/**
	 * Gets the name that causes the conflict
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}
}

?>