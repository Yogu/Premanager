<?php                             
namespace Premanager;

use Premanager\IO\CorruptDataException;
use Premanager\DisposedException;
use Premanager\PropertyException;
                   
/**
 * The super class for all objects with properties or events 
 */
class Module {
	private $_properties = array();
	private $_isDisposed = false;

	// ===========================================================================
	
	/**
	 * Specifies that a property can be accessed reading and writing
	 *
	 * Example:
	 *
	 * class Example extends Object {
	 *   private $_dummy = 'dummy';
	 *
	 *   public $dummy = Object::PROPERTY_GET_SET;
	 *
	 *   public function getDummy() {
	 *     return $this->_dummy;
	 *   }
	 *
	 *   public function setDummy($value) {
	 *     $this->_dummy = $value;
	 *   }
	 * }
	 *
	 * Usage:
	 * 
	 * $obj = new Example();
	 * $obj->dummy = 'hello';
	 * echo $obj->dummy;
	 */ 
	const PROPERTY_GET_SET = "\0\0";
	
	/**
	 * Specifies that a property is read-only
	 *
	 * Example:
	 *
	 * class Example extends Object {
	 *   private $_dummy = 'dummy';
	 *
	 *   public $dummy = Object::PROPERTY_GET;
	 *
	 *   public function getDummy() {
	 *     return $this->_dummy;
	 *   }
	 * }
	 *
	 * Usage:
	 * 
	 * $obj = new Example();
	 * echo $obj->dummy;
	 */ 
	const PROPERTY_GET = "\0\0\0";     
	
	/**
	 * Specifies that a property is write-only
	 *
	 * Example:
	 *
	 * class Example extends Object {
	 *   private $_dummy = 'dummy';
	 *
	 *   public $dummy = Object::PROPERTY_GET_SET;
	 *
	 *   public function setDummy($value) {
	 *     $this->_dummy = $value;
	 *     echo $this->_dummy;
	 *   }
	 * }
	 *
	 * Usage:
	 * 
	 * $obj = new Example();
	 * $obj->dummy = 'hello';
	 */ 
	const PROPERTY_SET = "\0\0\0\0";

	// ===========================================================================

	/**
	 * Initializes properties and events
	 */
	protected function __construct() {
		// Store properties and events
		foreach (\get_object_vars($this) as $name => $value) {
			switch ($value) {
				case self::PROPERTY_GET_SET:
				case self::PROPERTY_GET:
				case self::PROPERTY_SET:
					$readable = $value != self::PROPERTY_SET;
					$writeable = $value != self::PROPERTY_GET;
					
					if ($readable) {
						$getter = array($this, 'get'.$this->nameToUpper($name));
						if (!is_callable($getter))
							throw new CorruptDataException(
								"Getter of readable property '$name' is not callable in class ".
								get_class($this));
					}
			
					
					if ($writeable) {
						$setter = array($this, 'set'.$this->nameToUpper($name));
						if (!is_callable($setter))
							throw new CorruptDataException(
								"Setter of writeable property '$name' is not callable in class ".
								get_class($this));
					}
					
					$this->_properties[$name] = array($getter, $setter);
					unset($this->$name);
					break;
			}
    }
	}

	// ===========================================================================
	
	/**
	 * Gets the value of a property 
	 *
	 * @param string $name the name of the property
	 * @return mixed the value of the property
	 *
	 * If the property does not exist or is a write-only property, throws
	 * PropertyException. 
	 */
	public function __get($name) {
		$this->checkDisposed();
		
		if (isset($this->_properties[$name])) {
			if ($getter = $this->_properties[$name]->getter) {
				return call_user_func($getter);
			} else
				throw new PropertyException("Property '$name' is write-only in class ".
					get_class($this), 'name');
		} else
			throw new PropertyException("Property '$name' does not exist in class ".
				get_class($this), 'name');  
	}
	     
	/**
	 * Sets the value of a property - or - registers a new event handler
	 *
	 * @param string $name the name of the property
	 * @param mixed $value the value of the property - or - a callback
	 *
	 * If the property does not exist or is a read-only property, throws
	 * PropertyException. 
	 */
	public function __set($name, $value) {
		$this->checkDisposed();
		
		if (isset($this->_properties[$name])) {
			if ($setter = $this->_properties[$name]->setter) {
				call_user_func($setter, $value);
			} else
				throw new PropertyException("Property '$name' is read-only in class ".
					get_class($this), 'name');
		} else
			throw new PropertyException("Property '$name' does not exist in class ".
				get_class($this), 'name');  
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
	
	/**
	 * Transforms the first part of $name to upper case
	 * 
	 * @param string $name the name to transform
	 * @return string the transformed name
	 */
	private function nameToUpper($name) {
		\preg_match('/[A-Z]/', $name, &$matches, \PREG_OFFSET_CAPTURE);
		if (\count($matches)) {
			$firstPartLength = $matches[0][1];
			return Strings::toUpper(Strings::substring($name, 0, $firstPartLength)).
				Strings::substring($name, $firstPartLength);
		} else
			return Strings::toUpper($name);
	}
}

?>
