<?php 
namespace Premanager\Execution;

use Premanager\IO\Request;

/**
 * Defines a response consting of a string
 */
class StringResponse extends Response {
	private $_content;
	private $_contentType;
	private $_statusCode;
	
	/**
	 * Creates a redirection response
	 * 
	 * @param string $content the response content
	 * @param string $contentType the MIME type of this response
	 * @param string|null $code the HTTP status code, by default 200 OK
	 */
	public function __construct($content, $contentType, $code = 200) {
		$this->_content = $content;
		$this->_contentType = $contentType;
		$this->_statusCode = $code;
	}
	
	/**
	 * Gets the content to be sent
	 * 
	 * @return string
	 */
	public function getContent() {
		return $this->_content;
	}
	
	/**
	 * Gets the MIME type of this response
	 * 
	 * @return string
	 */
	public function getContentType() {
		return $this->_contentType;
	}
	
	/**
	 * Gets the HTML status code to be sent (e.g. 200 for OK)
	 * 
	 * @return int
	 */
	public function getStatusCode() {
		return $this->_statusCode;
	}
}

?>
