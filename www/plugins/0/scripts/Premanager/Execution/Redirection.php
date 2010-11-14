<?php 
namespace Premanager\Execution;

use Premanager\IO\Request;

/**
 * Defines a redirection response
 */
class Redirection extends Response {
	private $_location;
	private $_statusCode;
	
	/**
	 * Creates a redirection response
	 * 
	 * @param string|null $location the new location or null to redirect to the
	 *   request url
	 * @param string $code the HTTP status code, by default 303 See other
	 */
	public function __construct($location = null, $code = 303) {
		if ($location === null)
			$location = Request::getRequestURL();
		elseif (preg_match('/^[a-zA-Z0-9+.-]\:/', $location) === false)
			$location = Environment::getCurrent()->getURLPrefix().$location;
		$this->_location = $location;
	}
	
	/**
	 * Gets the content to be sent
	 * 
	 * @return string
	 */
	public function getContent() {
		return '<?xml version="1.0" encoding="utf-8" ?'.'><!DOCTYPE html>'.
			'<html lang="en"><head><meta charset="utf-8" />'.
			'<title>Redirection</title><meta http-equiv="refresh" '.
			'content="0;url='.htmlspecialchars($this->_location).'" />'.
			'<script type="text/javascript">document.location=\''.
			addslashes($this->_location).'\';'.
			'</script></head><body><h1>Redirection</h1>'.
			'<p>You have been redirected to <a href="'.
			htmlspecialchars($this->_location).'">'.
			htmlspecialchars($this->_location).'</a>.</p></body></html>';
	}
	
	/**
	 * Gets the MIME type of this response
	 * 
	 * @return string
	 */
	public function getContentType() {
		return 'text/html';
	}
	
	/**
	 * Gets the HTML status code to be sent (e.g. 200 for OK)
	 * 
	 * @return int
	 */
	public function getStatusCode() {
		return $this->_statusCode;
	}
	
	/**
	 * Sends this response to the client.
	 * 
	 * Do not call this method manually because it allows to send a response
	 * multiple times. It's recommended to use Premanager\IO\Output::send()
	 * instead.
	 */
	public function send() {
		header('Location: '.$this->_location, true, $this->_statusCode);
		parent::send();
	}
}

?>
