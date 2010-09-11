<?php
namespace Premanager\IO;

/**
 * Is thrown when a directory is accessed that does not exist
 */
class DirectoryNotFoundException extends IOException {
	private $_path;
	
	/**
	 * Creates a new DirectoryNotFoundException
	 * 
	 * @param string $message if specified, a custom exception message
	 * @param string $path the path to the missing directory
	 */
	public function __construct($message = '', $path = '') {
		parent::__construct($message);
		$this->_path = $path;
	}
	
	/**
	 * Gets the path to the missing directory
	 * 
	 * @return string
	 */
	public function getPath() {
		return $this->_path;
	}
}

?>