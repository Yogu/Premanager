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
	
	/**
	 * Reference to self::$_properties[class_name]
	 * 
	 * @var array
	 */
	private $_prop;
	
	/**
	 * array(class_name => array(property_name =>
	 *   array(getter_name, setter_name))
	 * @var array
	 */
	private static $_properties = array();

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
	 * Specifies that a property can be accessed reading and writing.
	 * 
	 * This is nearly the same as PROPERTY_GET_SET, but the names of the getter
	 * and setter are different. For example, for the property $htmlContent a
	 * getter called getHTMLContent() and a setter setHTMLContent() will be used
	 * (instead of getHtmlContent() and setHtmlContent() which it would be using
	 * PROPERTY_GET_SET)
	 */
	const PROPERTY_GET_SET_ACRONYM = "\0\0\0\0\0";
	
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
	 * Specifies that a property is read-only.
	 * 
	 * This is nearly the same as PROPERTY_GET, but the name of the getter is
	 * different. For example, for the property $htmlContent a getter called
	 * getHTMLContent() will be used (instead of getHtmlContent() which it would
	 * be using PROPERTY_GET)
	 */
	const PROPERTY_GET_ACRONYM = "\0\0\0\0\0\0";
	
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
	
	/**
	 * Specifies that a property is write-only.
	 * 
	 * This is nearly the same as PROPERTY_SET, but the name of the setter is
	 * different. For example, for the property $htmlContent a setter called
	 * setHTMLContent() will be used (instead of setHtmlContent() which it would
	 * be using PROPERTY_SET)
	 */
	const PROPERTY_SET_ACRONYM = "\0\0\0\0\0\0\0";

	// ===========================================================================

	/**
	 * Initializes properties and events
	 */
	protected function __construct() {
		// Store properties
		$class = get_class($this);
		if (!isset(self::$_properties[$class])) {
			self::$_properties[$class] = array();
			$this->_prop =& self::$_properties[$class];
			
			foreach (get_object_vars($this) as $name => $value) {
				switch ($value) {
					case self::PROPERTY_GET_SET:
					case self::PROPERTY_GET:
					case self::PROPERTY_SET:
					case self::PROPERTY_GET_SET_ACRONYM:
					case self::PROPERTY_GET_ACRONYM:
					case self::PROPERTY_SET_ACRONYM:
						$readable = $value != self::PROPERTY_SET &&
							$value != self::PROPERTY_SET_ACRONYM;
						$writeable = $value != self::PROPERTY_GET &&
							$value != self::PROPERTY_GET_ACRONYM;
							
						// PHP 6 will begin with the process of making function names case-
						// sensitive. As it seems now, using the wrong case will resolut in
						// a warning. One could also simply write $upper = $name for
						// performance reasons.
						if ($value == self::PROPERTY_GET_SET_ACRONYM ||
							$value == self::PROPERTY_GET_ACRONYM ||
							$value == self::PROPERTY_SET_ACRONYM)
						{
							$upper = $this->nameToUpper($name);
						} else
							$upper = ucfirst($name);
						
						if ($readable) {
							$getter = 'get'.$upper;
							if (!is_callable(array($this, $getter)))
								throw new CorruptDataException(
									"Getter of readable property '$name' is not callable in ".
									"class ".get_class($this));
						} else
							$getter = null;
						
						if ($writeable) {
							$setter = 'set'.$upper;
							if (!is_callable(array($this, $setter)))
								throw new CorruptDataException(
									"Setter of writeable property '$name' is not callable in ".
									"class ".get_class($this));
						} else
							$setter = null;
						
						self::$_properties[$class][$name] = array($getter, $setter);
						unset($this->$name);
						break;
				}
			}
    } else {
			$this->_prop =& self::$_properties[$class];
			
    	foreach ($this->_prop as $name => $value) {
    		unset($this->$name);
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
		
		if (isset($this->_prop[$name])) {
			if ($getter = $this->_prop[$name][0]) {
				return call_user_func(array($this, $getter));
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
		
		if (isset($this->_prop[$name])) {
			if ($setter = $this->_prop[$name][1]) {
				call_user_func(array($this, $setter), $value);
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
		preg_match('/[A-Z]/', $name, &$matches, \PREG_OFFSET_CAPTURE);
		if (count($matches)) {
			$firstPartLength = $matches[0][1];
			return Strings::toUpper(Strings::substring($name, 0, $firstPartLength)).
				Strings::substring($name, $firstPartLength);
		} else
			return Strings::toUpper($name);
	}
}

?>
