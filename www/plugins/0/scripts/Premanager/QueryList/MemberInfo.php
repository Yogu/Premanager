<?php
namespace Premanager\QueryList;

use Premanager\Debug\Debug;
use Premanager\ArgumentNullException;
use Premanager\ArgumentException;
use Premanager\Module;
use Premanager\Types;

/**
 * Provides information about a member of a model
 */
class MemberInfo extends Module{
	private $_modelDescriptor;
	private $_name;
	private $_kind;
	private $_type;
	private $_getterName;
	private $_fieldName;
	
	/**
	 * The model that contains this member
	 * 
	 * @var Premanager\QueryList\ModelDescriptor
	 */
	public $modelDescriptor = Module::PROPERTY_GET;
	
	/**
	 * The member name
	 * 
	 * @var string
	 */
	public $name = Module::PROPERTY_GET;
	
	/**
	 * The member kind (enum Premanager\QueryList\MemberKind)
	 * 
	 * @var int
	 */
	public $kind = Module::PROPERTY_GET;
	
	/**
	 * Result type (enum Premanager\QueryList\DataType or a class name)
	 * 
	 * @var int
	 */
	public $type = Module::PROPERTY_GET;
	
	/**
	 * Creates a new MemberInfo setting its properties
	 * 
	 * @param Premanager\QueryList\ModelDescriptor the model that contains this
	 *   member
	 * @param string $name the member name
	 * @param int $kind enum Premanager\QueryList\MemberKind
	 * @param int|Premanager\QueryList\ModelDescriptor $type
	 *   enum Premanager\QueryList\DataType or a model descriptor
	 * @param string $fieldName the name of the field the value of this member is
	 *   stored in (must be a field of the table specified by the model
	 *   descriptor) 
	 */
	public function __construct(ModelDescriptor $modelDescriptor, $name, $kind,
		$type, $getterName, $fieldName = '') {
		parent::__construct();
		
		if (!is_string($name))
			throw new ArgumentException('$name must be a string', 'name');
		if (!Types::isInteger($kind))
			throw new ArgumentException('$kind must be an integer', 'kind');
		if (!Types::isInteger($type) && !$type instanceof ModelDescriptor)
			throw new ArgumentException('$type must be an integer or a model ',
				'type');
		$this->_modelDescriptor = $modelDescriptor;
		$this->_name = $name;
		$this->_kind = $kind;
		$this->_type = $type;
		$this->_getterName = $getterName;
		$this->_fieldName = $fieldName;
	}
	
	/**
	 * Gets the model that contains this member
	 * 
	 * @return Premanager\QueryList\MemberInfo
	 */
	public function getModelDescriptor() {
		return $this->_modelDescriptor;
	}
	
	/**
	 * Gets the member name
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}
	
	/**
	 * Gets the member kind
	 * 
	 * @return int enum Premanager\QueryList\MemberKind
	 */
	public function getKind() {
		return $this->_kind;
	}
	
	/**
	 * Gets the result type
	 * 
	 * @return int|string enum Premanager\QueryList\DataType or a class name
	 */
	public function getType() {
		return $this->_type;
	}
	
	/**
	 * Gets the name of the method that returns the value of this member
	 * 
	 * @return string the getter's name
	 */
	public function getGetterName() {
		return $this->_getterName;
	}
	
	/**
	 * Gets the name of the field that contains the value
	 * 
	 * If this string starts with an asterix (*), the field belongs to the
	 * translation table. Two exclamation marks (!) embed the field name if an
	 * expression is given.
	 * 
	 * @return string the name of the field or an empty string if this field
	 *   can not be accessed using a data base field of the model's table
	 */
	public function getFieldName() {
		return $this->_fieldName;
	}
	
	/**
	 * Gets a query that results in the value of this field
	 * 
	 * @return string the query string or null if this field can not be accessed
	 *   using a data base field of the model's table
	 */
	public function getFieldQuery() {
		if ($this->_fieldName) {
			$field = $this->_fieldName;
			if ($isTranslated = $field[0] == '*')
				$field = substr($field, 1);
			if ($p0 = strpos('!', $field) !== false) {
				$expression = $field;
				// Extract the field name
				$pre = substr($field, 0, $p0-1);
				$field = substr($field, $p0+1);
				$p1 = strpos('!', $field)-1;
				if ($p1 !== false) {
					$field = substr($field, 0, $p1);
					$post = substr($field, $p1);
				}
			} 
			return $pre . ($isTranslated ? 'translation' : 'item') . '.`' .
				$field . '`' . $post;
		} else
			return null; 
	}
	
	/**
	 * Gets the value of this member on a specified object
	 * 
	 * @param mixed $object the object that contains this member
	 * @return mixed the member value
	 */
	public function getValue($object) {
		if (!$object)
			throw new ArgumentNullException('object');
		$className = $this->_modelDescriptor->getClassName();
		if (!$object instanceof $className)
			throw new ArgumentException('$object is not an instance of the model '.
				'that contains this member');
			
		$getter = array($object, $this->_getterName);
		Debug::assert(is_callable($getter), 'the getter for field ' .
			$this->_name . ' (' . $this->_getterName.') is not callable');
		return call_user_func($getter); 
	}
}

?>