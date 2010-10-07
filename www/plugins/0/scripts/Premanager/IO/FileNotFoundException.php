<?php
namespace Premanager\IO;

/**
 * Is thrown when a file is accessed that does not exist
 */
class FileNotFoundException extends IOException {
	private $_fileName;
	
	/**
	 * Creates a new FileNotFoundException
	 * 
	 * @param string $message if specified, a custom exception message
	 * @param string $fileName the path to the missing file
	 */
	public function __construct($message = '', $fileName = '') {
		parent::__construct($message);
		$this->_fileName = $fileName;
	}
	
	/**
	 * Gets the path to the missing file
	 * 
	 * @return string
	 */
	public function getFileName() {
		return $this->_fileName;
	}
}

?>