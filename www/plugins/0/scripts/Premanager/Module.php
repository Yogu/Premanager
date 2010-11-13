<?php                             
namespace Premanager;

use Premanager\IO\CorruptDataException;
                   
/**
 * The super class for all objects with properties or events 
 */
class Module {
	/**
	 * @var bool
	 */
	private $_isDisposed = false;

	// ===========================================================================

	protected function __construct() {
		
	}

	// ===========================================================================
	
	/**
	 * Throws a Premanager\PropertyException
	 * 
	 * This method is called when a property is accessed that does not exist
	 */
	public function __get($name) {
		throw new PropertyException('Property does not exist (get '.
			get_class($this).'::'.$name, $name);
	}
	     
	/**
	 * Throws a Premanager\PropertyException
	 * 
	 * This method is called when a property is accessed that does not exist
	 */
	public function __set($name, $value) {
		throw new PropertyException('Property does not exist (set '.
			get_class($this).'::'.$name, $name);
	}
	
	/**
	 * Declares this object as disposed so that its methods and properties will
	 * no longer work
	 * 
	 * All methods that call checkDisposed() will throw a
	 * Premanager\DisposedException after disposing this object
	 */
	protected function dispose() {
		$this->checkDisposed();
		$this->_isDisposed = true;
	}
	
	/**
	 * Throws Premanager\DisposedException, if this object is disposed
	 */
	protected function checkDisposed() {
		if ($this->_isDisposed)
			throw new DisposedException('Can not access members of a disposed '.
				'object');
	}
	
	/**
	 * Indicates whether this object is disposed
	 * 
	 * This method works on disposed objects.
	 * 
	 * @return bool true, if this object is disposed
	 */
	public function isDisposed() {
		return $this->_isDisposed;
	}
}

?>
