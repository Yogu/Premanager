<?php

// Store request time
define('REQUEST_TIME', microtime(true));

// Specify how to load classes
function autoload($className) {
	static $paths;
	
	// Collect all sub-folders of "plugins" (the directory of this file)
	// Each plugin directory contains a folder called "scripts" which contains
	// a common namespace structure.
	if (!$paths) {
		$paths = array();
		foreach (new DirectoryIterator(__DIR__) as $fileInfo) {
	    if ($fileInfo->isDir() && !$fileInfo->isDot()) {
	    	$name = $fileInfo->getPathname();
	    	if ($name == 'Premanager')
	    		array_unshift($paths, $name);
	    	else
	    		$paths[] = $name;
	    }
		}
	}
	
	foreach ($paths as $path) {
		$fileName = $path.'/scripts/'.str_replace('\\', '/', $className).'.php';
		if (\file_exists($fileName) && \is_file($fileName)) {
			require_once($fileName);
			break;
		}
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
	//echo "<b>$type:</b> ".$errfile.':'.$errline.': '.$errstr.'<br />';
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler", (E_ALL | E_STRICT) & ~E_NOTICE);

function exception_handler($exception) {
  echo "<b>Uncaught exception:</b> " . $exception->getMessage() . " (" .
  	get_class($exception) . ")\n";
}

//???set_exception_handler('exception_handler');

// Run Premanager
Premanager\Premanager::run();

?>
