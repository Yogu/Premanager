<?php 
namespace Premanager\Execution;

use Premanager\Module;
use Premanager\Strings;

/**
 * Defines an object that provides the data for a html response
 */
abstract class Response extends Module {
	/**
	 * Gets the content to be sent
	 * 
	 * @return string
	 */
	public abstract function getContent();
	
	/**
	 * Gets the MIME type of this response
	 * 
	 * @return string
	 */
	public abstract function getContentType();
	
	/**
	 * Gets the HTML status code to be sent (e.g. 200 for OK)
	 * 
	 * @return int
	 */
	public abstract function getStatusCode();
	
	/**
	 * Sends this response to the client.
	 * 
	 * Do not call this method manually because it allows to send a response
	 * multiple times. It's recommended to use Premanager\IO\Output::send()
	 * instead.
	 */
	public function send() {
		// Let php generate the html status code phrase
		header('x', true, $this->getStatusCode());
		
		$contentType = $this->getContentType();
		if (Strings::substring($contentType, 0, 4) == 'text')
			$contentType .= '; charset=UTF-8';
		header('Content-Type: '.$contentType);
		
		echo $this->getContent();
	}
}

?>
