<?php
namespace Premanager\Execution;

class FileDisposition {
	/**
	 * The file is displayed in the browser, if possible
	 * 
	 * @var int
	 */
	const INLINE = 0x00;
	
	/**
	 * The user has to save the file
	 * 
	 * @var int
	 */
	const ATTACHMENT = 0x01;
}

?>
