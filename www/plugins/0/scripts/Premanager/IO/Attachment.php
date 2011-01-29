<?php
namespace Premanager\IO;

use Premanager\Module;

/**
 * Defines an attachment to an email
 */
class Attachment extends Module {
	private $_fileName;
	private $_contentType;
	private $_content;
	
	/**
	 * Creates a new attachment
	 * 
	 * @param string $fileName the displayed file name
	 * @param string $contentType the MIME content type
	 * @param string $content the content as string
	 */
	public function __construct($fileName, $contentType, $content) {
		parent::__construct();
		$this->_fileName = $fileName;
		$this->_contentType = $contentType;
		$this->_content = $content;
	}
	
	/**
	 * Gets the displayed file name
	 * 
	 * @return string the displayed file name
	 */
	public function getFileName() {
		return $this->_fileName;
	}
	
	/**
	 * Gets the MIME content type
	 * 
	 * @return string the MIME content type
	 */
	public function getContentType() {
		return $this->_contentType;
	}
	
	/**
	 * Gets the content string
	 * 
	 * @return string the content string
	 */
	public function getContent() {
		return $this->_content;
	}
}

?>