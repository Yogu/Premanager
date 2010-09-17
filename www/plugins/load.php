<?php

// Specify how to load classes
function autoload($className) {
	static $paths;
	
	echo '<b>Class:</b> '.$className.'<br />';
	
	// Collect all sub-folders of "plugins" (the directory of this file)
	// Each plugin directory contains a folder called "scripts" which contains
	// a common namespace structure.
	if (!$paths) {
		$paths = array();
		foreach (new DirectoryIterator(__DIR__) as $fileInfo) {
	    if ($fileInfo->isDir() && !$fileInfo->isDot()) 
	    	$paths[] = $fileInfo->getPathname();
		}
	}
	
	foreach ($paths as $path) {
		$fileName = $path.'/scripts/'.str_replace('\\', '/', $className).'.php';
		if (\file_exists($fileName) && \is_file($fileName))
			require_once($fileName);
	}
}

spl_autoload_register('autoload');

// Define an error handler
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
	switch ($errno) {
		case E_NOTICE:
			$type = "Notice";
			break;
		case E_WARNING:
			$type = "Warning";
			break;
		case E_STRICT:
			$type = "strict";
			break;
		default:
			$type = "Error";
	}
	echo "<b>$type:</b> ".$errfile.':'.$errline.': '.$errstr.'<br />';
	//throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler", (E_ALL | E_STRICT) & ~E_NOTICE);

// Run Premanager
require_once("Premanager/scripts/Premanager/Premanager.php");

?>
