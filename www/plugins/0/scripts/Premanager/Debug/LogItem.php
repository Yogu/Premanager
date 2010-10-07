<?php
namespace Premanager\Debug;

use Premanager\IO\Config;

use Premanager\Module;

/**
 * A log item
 */
class LogItem extends Module {
	private $_message;
	private $_time;
	private $_className;
	private $_functionName;
	private $_fullFunctionName;
	private $_fileName;
	private $_line;
	
	/**
	 * The HTML-formatted message of this log item
	 * 
	 * This property is read-only.
	 * 
	 * @var string
	 */
	public $message = Module::PROPERTY_GET;
	
	/**
	 * The time this log item has been created
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $time = Module::PROPERTY_GET;
	
	/**
	 * The name of the class that has called the log function
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $className = Module::PROPERTY_GET;
	
	/**
	 * The name of the function that has called the log function
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $functionName = Module::PROPERTY_GET;
	
	/**
	 * The function name and class name of the function that has called the log
	 * function
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $fullFunctionName = Module::PROPERTY_GET;
	
	/**
	 * The full path to the file that has called the log function
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $fileName = Module::PROPERTY_GET;
	
	/**
	 * The line number where the log function has been called
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $line = Module::PROPERTY_GET;
	
	/**
	 * Creates a new LogItem
	 * 
	 * @param mixed $message the message or any value to log
	 * @param int $indirectCallDepth the count of methods in call stack to be
	 *   excluded from stored call stack
	 */
	public function __construct($message, $indirectCallDepth = 0) {
		parent::__construct();
		
		if (!is_scalar($message)) {
			ob_start();
			var_dump($message);
			$message = ob_get_contents();
			ob_end_clean();
			if (!Config::getVarDumpReturnsHTML())
				$message = htmlspecialchars($message);
		} else
			$message = htmlspecialchars($message);
		
		$this->_message = $message;
		$this->_time = time();
				
		// Get Callstac information
		$stack = debug_backtrace();
		
		// Information about called function      
		$call0 = $stack[$indirectCallDepth];
		$call1 = $stack[$indirectCallDepth + 1];  
		$call2 = $stack[$indirectCallDepth + 2];  
		
		if ($call0['line'] == null) {
			$call0 = $call1;
			$call1 = $call2;
		}
		
		$this->_className = $call1['class'];
		$this->_functionName = $call1['function'];
		$this->_fullFunctionName = $this->_className.$call1['type'].
			$this->_functionName;
		$this->_fileName = $call0['file'];          
		$this->_line = $call0['line'];		
	}
	
	/**
	 * Gets the HTML-formatted message of this log item
	 * @return string
	 */
	public function getMessage() {
		return $this->_message;
	}
	
	/**
	 * Gets the time this log item has been created
	 * @return int
	 */
	public function getTime() {
		return $this->_time;
	}
	
	/**
	 * Gets the name of the class that has called the log function
	 * @return int
	 */
	public function getClassName() {
		return $this->_className;
	}
	
	/**
	 * Gets the name of the function that has called the log function
	 * @return int
	 */
	public function getFunctionName() {
		return $this->_functionName;
	}
	
	/**
	 * Gets class and function name 
	 * @return int
	 */
	public function getFullFunctionName() {
		return $this->_fullFunctionName;
	}
	
	/**
	 * Gets the full path to the file that has called the log function 
	 * @return int
	 */
	public function getFileName() {
		return $this->_fileName;
	}
	
	/**
	 * Gets the line in that the log function has been called 
	 * @return int
	 */
	public function getLine() {
		return $this->_line;
	}
}

?>
