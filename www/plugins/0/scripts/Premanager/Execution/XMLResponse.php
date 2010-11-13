<?php 
namespace Premanager\Execution;

use Premanager\IO\Request;

/**
 * Defines a response consting of a xml document
 */
class XMLResponse extends Response {
	private $_statusCode;
	
	/**
	 * The XMLWriter that is used for output. The constructor initializes this
	 * property.
	 * 
	 * @var XMLWriter
	 */
	public $writer;
	
	/**
	 * Creates a redirection response
	 * 
	 * @param string|null $code the HTTP status code, by default 200 OK
	 */
	public function __construct($code = 200) {
		$this->writer = new \XMLWriter();
		$this->writer->openMemory();
		$this->writer->startDocument('1.0', 'utf-8');
		$this->_statusCode = $code;
	}
	
	/**
	 * Ends the document and gets the content to be sent
	 * 
	 * @return string
	 */
	public function getContent() {
		$this->writer->endDocument();
		return $this->writer->outputMemory(false);
	}
	
	/**
	 * Gets the MIME type of this response
	 * 
	 * @return string
	 */
	public function getContentType() {
		return 'text/xml';
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
