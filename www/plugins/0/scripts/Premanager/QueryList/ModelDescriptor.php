<?php
namespace Premanager\QueryList;

use Premanager\InvalidOperationException;

use Premanager\ArgumentException;

use Premanager\Module;

class ModelDescriptor extends Module {
	/**
	 * @var string
	 */
	private $_className;
	/**
	 * @var array
	 */
	private $_members;
	/**
	 * @var string
	 */
	private $_pluginName;
	/**
	 * @var string
	 */
	private $_table;
	/**
	 * @var callback
	 */
	private $_getByIDCallback;
	
	/**
	 * The name of the class this descriptor describes 
	 * @var string
	 */
	public $className = Module::PROPERTY_GET;
	
	/**
	 * The name of the plugin that contains the models
	 * 
	 * @var string
	 */
	public $pluginName = Module::PROPERTY_GET;
	
	/**
	 * The name of the model's table
	 * 
	 * @var string
	 */
	public $table = Module::PROPERTY_GET;
	
	/**
	 * Creates a new model descriptor
	 * 
	 * @param string $className the class name
	 * @param array $properties an array with property names as keys and an array
	 *   as value (containing type, getter name and, optional, field name). Use
	 *   'this' as type if the type is this model.
	 * @param string $table the name of the table that contains this models
	 * @param callback $getByIDCallback a callback that gets an instance by its id
	 * @param bool $tableIsTranslated true (default), if there is a translation
	 *   table for the table speicified by the $table argument
	 */
	public function __construct($className, array $properties, $pluginName,
		$table = null, $getByIDCallback = null, $tableIsTranslated = true) {
		parent::__construct();
			
		if ($getByIDCallback != null && !\is_callable($getByIDCallback))
			throw new ArgumentException('$getByIDCallback must be either a valid '.
				'callback or null', 'getByIDCallback');
			
		$this->_className = $className;
		$this->_pluginName = $pluginName;
		$this->_table = $table;
		$this->_getByIDCallback = $getByIDCallback;
		$this->_tableIsTranslated = $tableIsTranslated;
		foreach ($properties as $name => $value) {
			if (is_array($value)) {
				$type = $value[0];
				$getter = $value[1];
				$field = count($value) > 1 ? $value[2] : '';
			} else {
				$type = $value;
				$field = '';
				$getter = 'get'.$name;
			}
			if ($type == 'this')
				$type = $this;
			$this->_members[$name] =
				new MemberInfo($this, $name, MemberKind::PROPERTY, $type, $getter,
					$field);
		}
	}
	
	/**
	 * Gets information about the member called $name if it exists, returns null
	 * otherwise.
	 * 
	 * @param string $name the member name
	 * @return Premanager\QueryList\MemberInfo information about the member
	 */
	public function getMemberInfo($name) {
		if (\array_key_exists($name, $this->_members))
			return $this->_members[$name];
	}
	
	/**
	 * Gets the name of the class this descriptor describes
	 * 
	 * @return string
	 */
	public function getClassName() {
		return $this->_className;
	}
	
	/**
	 * Gets the name of the plugin containing the models
	 * 
	 * @return string
	 */
	public function getPluginName() {
		return $this->_pluginName;
	}
	
	/**
	 * Gets the name of the model's table
	 * 
	 * @return string
	 */
	public function getTable() {
		return $this->_table;
	}
	
	/**
	 * Indicates whether the method getByID is available on this model descriptor
	 * 
	 * @return bool
	 */
	public function canGetByID() {
		return $this->_getByIDCallback != null;
	}
	
	/**
	 * Gets an instance of the model class this descriptor describes using its id
	 * 
	 * @param int $id the model's id
	 * @return mixed
	 * @throws Premanager\InvalidOperationException the method getByID is not
	 *   available on this object. See canGetByID().
	 */
	public function getByID($id) {
		if (!$this->_getByIDCallback)
			throw new InvalidOperationException('The method getByID is not '.
				'available on this object');
		return call_user_func($this->_getByIDCallback, $id);
	}
}

