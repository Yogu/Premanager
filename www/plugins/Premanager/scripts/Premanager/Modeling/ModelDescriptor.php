<?php
namespace Premanager\Modeling;

use Premanager\Strings;
use Premanager\IO\DataBase\QueryBuilder;
use Premanager\NotSupportedException;
use Premanager\IO\DataBase\DataBase;
use Premanager\Pages\AddGroupHomePage;
use Premanager\Models\User;
use Premanager\Modeling\ModelFlags;
use Premanager\InvalidOperationException;
use Premanager\ArgumentException;
use Premanager\Module;
use Premanager\Types;

abstract class ModelDescriptor extends Module {
	/**
	 * @var array
	 */
	private $_members;
	/**
	 * @var array
	 */
	private $_instances;
	/**
	 * @var Premanager\Modeling\QueryList
	 */
	private $_queryList;
	
	// ===========================================================================
	
	/**
	 * Creates a new model descriptor
	 * 
	 * @param string $className the class name
	 * @param array $properties an array with property names as keys and an array
	 *   as value (containing type, getter name and, optional, field name). Use
	 *   'this' as type if the type is this model.
	 * @param string $table the name of the table that contains this models
	 * @param int $flags (enum set Premanager\Modeling\ModelFlags)
	 * @param string $nameGroupSQL an SQL expression that determines the name
	 *   group for an item (alias for item table is 'item')
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	// ===========================================================================
	
	/**
	 * Gets information about the member called $name if it exists, returns null
	 * otherwise.
	 * 
	 * @param string $name the member name
	 * @return Premanager\Modeling\MemberInfo information about the member
	 */
	public function getMemberInfo($name) {
		if ($this->_members === null)
			$this->loadMembers();
		
		if (array_key_exists($name, $this->_members))
			return $this->_members[$name];
	}
	
	/**
	 * Gets the name of the class this descriptor describes
	 * 
	 * @return string
	 */
	public abstract function getClassName();
	
	/**
	 * Gets the name of the plugin containing the models
	 * 
	 * @return string
	 */
	public abstract function getPluginName();
	
	/**
	 * Gets the name of the model's table
	 * 
	 * @return string
	 */
	public abstract function getTable();
	
	/**
	 * Specifies whether there is a translation table for the table specified by
	 * getTable()
	 * 
	 * @return string
	 */
	public function isTableTranslated() {
		return $this->getFlags() & ModelFlags::HAS_TRANSLATION;
	}
	
	/**
	 * Gets flags set for this model descriptor 
	 * 
	 * @return int (enum set Premanager\Modeling\ModelFlags)
	 */
	public abstract function getFlags();
	
	/**
	 * Gets an SQL expression that determines the name group for an item (alias
	 * for item table is 'item')
	 * 
	 * @return string an SQL expression
	 */
	public abstract function getNameGroupSQL();
	
	/**
	 * Gets an instance of the model class this descriptor describes using its id
	 * 
	 * @param int $id the model's id
	 * @return mixed
	 * @throws Premanager\InvalidOperationException the method getByID is not
	 *   available on this object. See canGetByID().
	 */
	public function getByID($id) {
		$id = (int)$id;
		
		if (!$this->_instances)
			$this->_instances = array();
			
		if (!Types::isInteger($id) || $id < 0)
			return null;
		else if (array_key_exists($id, $this->_instances))
			return $this->_instances[$id];
		else
			return $this->createFromID($id, true);
	}
	
	/**
	 * Creates a new instance of this model and inserts some values into its
	 * data base row
	 * 
	 * @param array $values the set of untranslated initial values
	 * @param array $translatedValues the set of translated initial values
	 * @param string $name if required, the name
	 * @param string $nameGroup if required, the name group identifier
	 * @return Premanager\Modeling\Model the created model
	 */
	protected function createNewBase($values, $translatedValues, $name = null,
		$nameGroup = null)
	{
		$user = Environment::getCurrent()->getUser()->getID();
		$lang = Environment::getCurrent()->getLanguage()->getID();
		
		// -------------- Item ------------
			
		// Extend value array
		if ($this->getFlags() & ModelFlags::CREATOR_FIELDS) {
			$values['createTime!'] = 'NOW()';
			$values['creatorID'] = $user;
		}
		
		if ($this->getFlags() & ModelFlags::EDITOR_FIELDS) {  
			$values['editTime!'] = 'NOW()';
			$values['editorID'] = $user;
			$values['editTimes'] = 0;
		}
			
		if ($name !== null) {
			if ($this->flags & ModelFlags::UNTRANSLATED_NAME)
				$values['name'] = $name;
			else if ($this->flags & ModelFlags::TRANSLATED_NAME)
				$translatedValues['name'] = $name;
		}
		   
		// Execute query
		DataBase::query(QueryBuilder::buildInsertQuery(
			$this->getItemTableName(),
			$values));
		$id = DataBase::getInsertID();
			
		// -------------- Translation ------------
		if ($this->getFlags() & ModelFlags::HAS_TRANSLATION) {
			// Extend value array
			$translatedValues['id'] = $id;
			$translatedValues['languageID'] = $lang;
			
			// Execute query
			DataBase::query(QueryBuilder::buildInsertQuery(
				$this->getTranslationTableName(),
				$translatedValues));
		}
		
		$model = $this->createFromID($id, false);

		// -------------- Name ------------
		
		if ($name !== null)		
			$model->insertName($name, $nameGroup);
					
		return $model;
	}
	
	/**
	 * Gets a model by its name
	 * 
	 * @param string $name the name to check
	 * @param string $nameGroup the name group identifier, if required
	 * @return Premanager\Modeling\Model the model having this name or null
	 */
	protected function getByNameBase($name, $nameGroup = null,
		&$isNameInUse = false)
	{
		if ($this->getFlags() & ModelFlags::NO_NAME)
			throw new NotSupportedException('Models of this class do not have a name');
		
		if ($nameGroupSQL = $this->getNameGroupSQL())
			$nameGroupSQL = "AND $nameGroupSQL = '$nameGroup'";
			
		$result = DataBase::query(
			"SELECT name.id, name.inUse ".
			"FROM ".$this->getNameTableName()." name ".
			"INNER JOIN ".$this->getNameTableName()." item ON name.id = item.id ".
			"WHERE name.name = '".DataBase::escape(Strings::unitize($name))."' ".
				$nameGroupSQL.
				($ignoreThis ? "AND item.id != '".$ignoreThis->getID()."'" : ''));
		if ($result->next()) {
			$isNameInUse = $result->get('inUse');
			return $this->createFromID($id, false);
		} else
			return null;
	}
	
	/**
	 * Gets a list of all items of this model
	 * 
	 * @return Premanager\Modeling\QueryList a query list with all items
	 */
	public function getQueryList() {
		if ($this->_queryList === null)
			$this->_queryList = new QueryList($this);
		return $this->_queryList;
	}
	
	/**
	 * Gets the formatted name of the item table
	 * 
	 * @return string the formatted name of the item table
	 */
	public function getItemTableName() {
		return DataBase::formTableName($this->getPluginName(), $this->getTable());
	}
	
	/**
	 * Gets the formatted name of the name table
	 * 
	 * This does NOT make sure that there is a name table.
	 * 
	 * @return string the formatted name of the name table
	 */
	public function getNameTableName() {
		return
			DataBase::formTableName($this->getPluginName(), $this->getTable().'Name');
	}
	
	/**
	 * Gets the formatted name of the translation table
	 * 
	 * @return string the formatted name of the translation table
	 */
	public function getTranslationTableName() {
		return DataBase::formTableName(
			$this->getPluginName(), $this->getTable().'Translation');
	}
	
	/**
	 * Is called by Premanager\Modeling\Model::delete() and makes sure that the
	 * model is removed from the instances list
	 * 
	 * @param int $id the id of the deleted model
	 */
	public function internalOnModelDeleted($id) {
		unset($this->_instances[$id]);
	}
	
	// ===========================================================================
	
	protected function addProperty($name, $type, $getterName, $fieldName = '')
	{
		$this->_members[$name] = new MemberInfo($this, $name, MemberKind::PROPERTY,
			$type, $getterName, $fieldName);
	}
	
	/**
	 * Loads the members calling addProperty()
	 */
	protected function loadMembers() {
		$this->_members = array();
	
		// Add fields by flags
		$this->addProperty('id', DataType::NUMBER, 'getID', 'id');
		if ($this->getFlags() && ModelFlags::UNTRANSLATED_NAME)
			$this->addProperty('name', DataType::STRING, 'getName', 'name');
		else if ($this->getFlags() && ModelFlags::TRANSLATED_NAME)
			$this->addProperty('name', DataType::STRING, 'getName', '*name');
		if ($this->getFlags() && ModelFlags::CREATOR_FIELDS) {
			$this->addProperty('creator', User::getDescriptor(), 'getCreator',
				'creatorID');
			$this->addProperty('createTime', DataType::DATE_TIME, 'getCreateTime',
				'createTime');
		}
		if ($this->getFlags() && ModelFlags::EDITOR_FIELDS) {
			$this->addProperty('editor', User::getDescriptor(), 'getEditor',
				'editorID');
			$this->addProperty('editTime', DataType::DATE_TIME, 'getEditTime',
				'editTime');
			$this->addProperty('editTimes', DataType::NUMBER, 'getEditTimes',
				'editTimes');
		}
	}
	
	// ===========================================================================
	
	private function createFromID($id, $validateID = false) {
		if (!$this->_instances)
			$this->_instances = array();
			
		if (array_key_exists($id, $this->_instances))
			return self::$_instances[$id];
			
		$className = $this->getClassName();
		$instance = new $className($id, false);
		if ($instance->load()) {
			$this->_instances[$id] = $instance;
			return $instance;
		} else
			return null;
	} 
}

