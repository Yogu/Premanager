<?php
namespace Premanager\QueryList;

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
	 */
	public function __construct(ModelDescriptor $modelDescriptor, $name, $kind,
		$type) {
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
			
		$name = $this->_name;
		return $object->$name;
	}
}

?>