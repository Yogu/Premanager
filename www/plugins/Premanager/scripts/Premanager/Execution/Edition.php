<?php
namespace Premanager\Execution;

/**
 * Specifies the way a page should be displayed
 */
class Edition {
	/**
	 * Specifies that the page should be rendered commonly
	 */
	const COMMON = 0;
	
	/**
	 * Specifies that the client is a mobile device and the page should be 
	 * rendered in a compact edition
	 */
	const MOBILE = 1;
	
	/**
	 * Specifies that the page should be rendered for a print preview
	 */
	const PRINTABLE = 2;
}

?>