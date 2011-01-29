<?php
namespace Premanager\IO;

use Premanager\Module;

/**
 * Defines an inline attachment (can be used in the html content of an email)
 */
class InlineAttachment extends Attachment {
	private $_contentID;
	
	/**
	 * Creates a new attachment
	 * 
	 * @param string $fileName the displayed file name
	 * @param string $contentType the MIME content type
	 * @param string $contentID a unique identifier for this attachment 
	 * @param string $content the content as string
	 */
	public function __construct($fileName, $contentType, $contentID, $content) {
		parent::__construct($fileName, $contentType, $content);
		$this->_contentID = $contentID;
	}
	
	/**
	 * Gets a unique identifier for this attachment
	 * 
	 * @return string a unique identifier for this attachment
	 */
	public function getContentID() {
		return $this->_contentID;
	}
}

?>