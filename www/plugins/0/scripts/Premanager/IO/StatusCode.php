<?php 
namespace Premanager\IO;

/**
 * Specifies the http status code of the output
 */
class StatusCode {
	/**
	 * The requested resource is found and sent
	 */
	const OK = 0;
	
	/**
	 * The requested resource does not exist
	 */
	const NOT_FOUND = 1;
	
	/**
	 * The visitor is not allowed to access the requested resource
	 */
	const FORBIDDEN = 2;
}

?>