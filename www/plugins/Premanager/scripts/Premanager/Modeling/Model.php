<?php 
namespace Premanager\Modeling;

use Premanager\IO\DataBase\QueryBuilder;
use Premanager\IO\DataBase\DataBase;
use Premanager\Module;
use Premanager\Strings;
use Premanager\Models\User;
use Premanager\NotSupportedException;
use Premanager\Modeling\ModelFlags;
use Premanager\DateTime;
use Premanager\Execution\Environment;
use Premanager\NotImplementedException;

/**
 * Defines a model
 * 
 * Should implement the static function getDescriptor()
 */
abstract class Model extends Module {
	/**
	 * @var int
	 */
	protected $_id;
	/**
	 * @var string
	 */
	private $_name;
	/**
	 * @var Premanager\Models\User
	 */
	private $_creator = false;
	/**
	 * @var int
	 */
	private $_creatorID;
	/**
	 * @var Premanager\DateTime
	 */
	private $_createTime = false;
	/**
	 * @var Premanager\Models\User
	 */
	private $_editor = false;
	/**
	 * @var int
	 */
	private $_editorID;
	/**
	 * @var Premanager\DateTime
	 */
	private $_editTime = false;
	/**
	 * @var int
	 */
	private $_editTimes;
	
	// ===========================================================================
	
	/**
	 * Do not call this constructor manually.
	 */
	public function __construct($id, $onCreation) {
		$this->_id = $id;
		
		if ($onCreation) {
			$this->_creator = Environment::getCurrent()->getUser();
			$this->_createTime = DateTime::getNow();
			$this->_editor = Environment::getCurrent()->getUser();
			$this->_editTime = DateTime::getNow();
			$this->_editTimes = 0;
		}
	}
	
	/**
	 * Inserts a new name for this group or, if it already exists, makes sure it
	 * is marked as used.
	 * 
	 * This method is used by Premanager\Modeling\ModelDescriptor
	 * 
	 * @param string $name the name to insert
	 * @param string $nameGroup a string that specifies the group in that the
	 *   name has to be unique
	 */
	public function insertName($name, $nameGroup = '', $languageID = null) {
		$this->checkDisposed();
		
		if ($this->getModelDescriptor()->getFlags() & ModelFlags::NO_NAME)
			throw new NotSupportedException('This model does not have a name');
			
		if ($languageID === null)
			$languageID = Environment::getCurrent()->getLanguage()->getID();
		$name = DataBase::escape(Strings::unitize($name));
		$m = $this->getModelDescriptor();
		$itemTable = DataBase::formTableName($m->getPluginName(), $m->getTable());
		$nameTable =
			DataBase::formTableName($m->getPluginName(), $m->getTable().'Name');
		if ($nameGroupSQL = $m->getNameGroupSQL())
			$nameGroupSQL = "AND $nameGroupSQL = '$nameGroup'";
					
		$result = DataBase::query(
			"SELECT nameID ".
			"FROM $nameTable AS name ".
			"INNER JOIN $itemTable AS item ".
				"ON name.id = item.id ".
			"WHERE LOWER(name.name) = '$name' ".
				$nameGroupSQL);
			
		if ($result->next()) {
			DataBase::query(
				"UPDATE $nameTable ".
				"SET inUse = '1', ".
					"languageID = '$languageID', ".
					"id = '$this->_id' ".
				"WHERE nameID = '".$result->get('nameID')."'");
		} else {
			DataBase::query(
				"INSERT INTO $nameTable ".
				"(id, name, languageID, inUse) ".
				"VALUES ('$this->_id', '$name', '$languageID', '1')");           	
		}
	}
	
	protected function update(array $values, array $translatedValues = null,
		$name = null, $nameGroup = '')
	{
		$this->checkDisposed();
		
		$user = Environment::getCurrent()->getUser()->getID();
		$lang = Environment::getCurrent()->getLanguage()->getID();
		$m = $this->getModelDescriptor();
			
		// Extend value array		
		if ($m->getFlags() & ModelFlags::EDITOR_FIELDS) {  
			$values['editTime!'] = 'NOW()';
			$values['editorID'] = $user;
			$values['editTimes!'] = 'editTimes + 1';
		}
		
		if ($name !== null) {
			if ($m->getFlags() & ModelFlags::UNTRANSLATED_NAME)
				$values['name'] = $name;
			else if ($translatedValues && $m->getFlags() & ModelFlags::TRANSLATED_NAME)
				$translatedValues['name'] = $name;
		}

		// Update item table
		DataBase::query(QueryBuilder::buildUpdateQuery(
			DataBase::formTableName($m->getPluginName(), $m->getTable()),
			$values,
			"id = '$this->_id'"));
			
		// Update language table
		if ($translatedValues && $m->getFlags() & ModelFlags::HAS_TRANSLATION) {
			$translatedValues['id'] = $this_id;
			$translatedValues['languageID'] = $lang;
			if ($name === null && $m->getFlags() & ModelFlags::TRANSLATED_NAME)
				$translatedValues['name'] = $this->getName();
				
			DataBase::query(QueryBuilder::buildInsertOrUpdateQuery(
				DataBase::formTableName($m->getPluginName(), $m->getTable().'Translation'),
				$translatedValues));
		}
	
		// Update name table
		if ($name !== null && !($m->getFlags() && ModelFlags::NO_NAME)) {
			$this->insertName($name, $nameGroup);
			$this->checkNames();
		}
		
		// Update fields
		if ($name !== null)
			$this->_name = $name;
		$this->_editor = Environment::getCurrent()->getUser();
		$this->_editTime = DateTime::getNow();
		if ($this->_editTimes !== null)
			$this->_editTimes++; 
	}
	
	/**
	 * Deletes this model and disposes this object
	 */
	protected function delete() {
		$this->checkDisposed();
		
		$m = $this->getModelDescriptor();
		
		DataBase::query(
			"DELETE FROM ".$m->getItemTableName()." ".
			"WHERE id = '$this->_id'");

		if ($m->getFlags() & ModelFlags::HAS_TRANSLATION)
			DataBase::query(
				"DELETE FROM ".$m->getTranslationTableName()." ".
				"WHERE id = '$this->_id'");	
			
		if (!($m->getFlags() & ModelFlags::NO_NAME))
			DataBase::query(
				"DELETE FROM ".$m->getNameTableName()." ".
				"WHERE id = '$this->_id'");

		$m->internalOnModelDeleted($this->_id);
	
		$this->dispose();
	}
	         
	/**
	 * Deletes all names that are no more in use
	 */
	protected function deleteUnusedNames() {
		$this->checkDisposed();
		$m = $this->getModelDescriptor();
		if ($m->getFlags() & ModelFlags::NO_NAME)
			throw new NotSupportedException('This model does not have a name');
			
		DataBase::query(
			"DELETE FROM ".$m->getNameTableName()." ".
			"WHERE id = '$this->_id' ".
				"AND NOT inUse");
	}  
	
	/**
	 * Gets all names used by this model and their languages
	 * 
	 * @return array array($name =>
	 *   array('languageID' => $languageID, 'nameGroup' => $nameGroup))
	 */
	protected function getNamesInUse() {
		$this->checkDisposed();
		$m = $this->getModelDescriptor();
		if ($m->getFlags() & ModelFlags::NO_NAME)
			throw new NotSupportedException('This model does not have a name');
			
		$nameGroupSQL =
			($m->getNameGroupSQL() ? $m->getNameGroupSQL()." AS nameGroup, " : '');
			
		if ($flags & self::UNTRANSLATED_NAME) {
			$result = DataBase::query(
				"SELECT $nameGroupSQL item.name ".
				"FROM ".DataBase::formTableName($m->getPluginName(), $m->getTable()).
					" AS item ".   
				"WHERE item.id = '$this->_id'");
		} else {
			$result = DataBase::query(
				"SELECT $nameGroupSQL translation.name, translation.languageID ".
				"FROM ".DataBase::formTableName(
					$m->getPluginName(), $m->getTable().'Translation')." AS translation ".
				"WHERE translation.id = '$this->_id'");
		}
		$names = array();
		while ($result->next()) {
			$names[$result->get('name')] = array(
				'languageID' => $result->get('languageID'),
				'nameGroup' => $result->get('nameGroup'));
		}
		return $names;
	} 
	
	/**
	 * Checks whether the inUse flags and languageID fields of names of this model
	 * are correctly set
	 */
	private function checkNames() {
		$this->checkDisposed();
		$m = $this->getModelDescriptor();
		if ($m->getFlags() & ModelFlags::NO_NAME)
			throw new NotSupportedException('This model does not have a name');
						
		$namesInUse = $this->getNamesInUse();
						
		// Check names assigned to this item, check if they are still in use and
		// update, if neccessary, their language
		$result = DataBase::query(
			"SELECT name.nameID, name.name, name.languageID ".
			"FROM ".$m->getNameTableName()." AS name ".
			"WHERE name.id = '$this->_id' ".
				"AND name.inUse = '1'");
		while ($result->next()) {
			$name = DataBase::escape($result->get('name'));
			$nameID = $result->get('nameID');
			$languageID = $result->get('languageID');
			
			$isInUse = isset($namesInUse[$name]);
			if ($isInUse)
				$languageID = $namesInUse[$name]['languageID'];
			else
				$languageID = 0;
					
			// If there is no translation with this name, set inUse to false
			if (!$isInUse) {
				DataBase::query(
					"UPDATE ".$m->getNameTableName()." ".
					"SET inUse = '0' ".
					"WHERE nameID = '$nameID'");
			
			// If language has changed, update this
			} else if ($m->getFlags() & ModelFlags::TRANSLATED_NAME &&
				$realLanguageID != $languageID)
			{
				DataBase::query(
					"UPDATE ".$m->getNameTableName()." ".
					"SET languageID = '$languageID' ".
					"WHERE nameID = '$nameID'");  							
			}			
		}
	}
	
	/**
	 * Fills the fields from data base
	 * 
	 * @param array $fields an array($name => $sql) where $sql is a SQL statement
	 *   to store under the alias $name
	 * @return array ($name => $value) the values for the fields - or false if the
	 *   model does not exist in data base
	 */
	public function load(array $fields = array()) {
		$this->checkDisposed();
		$m = $this->getModelDescriptor();
		
		if ($m->getFlags() & ModelFlags::UNTRANSLATED_NAME)
			$fields['model_name'] = 'item.name';
		if ($m->getFlags() & ModelFlags::TRANSLATED_NAME)
			$fields['model_name'] = 'translation.name';
		if ($m->getFlags() & ModelFlags::CREATOR_FIELDS) {
			$fields['model_creatorID'] = 'item.creatorID';
			$fields['model_createTime'] = 'item.createTime';
		}
		if ($m->getFlags() & ModelFlags::EDITOR_FIELDS) {
			$fields['model_editorID'] = 'item.editorID';
			$fields['model_editTime'] = 'item.editTime';
			$fields['model_editTimes'] = 'item.editTimes';
		}
		
		$query = '';
		foreach ($fields as $name => $sql) {
			if (strpos($sql, '.') === false)
				$sql = 'item.'.$sql;
			if (!is_int($name))
				$sql .= " AS $name";
			if ($query)
				$query .= ', ';
			$query .= $sql;
		}
		
		if ($m->getFlags() & ModelFlags::HAS_TRANSLATION) {
			$result = DataBase::query(
				"SELECT $query ".
				"FROM ".$m->getItemTableName()." AS item ",
				/* translating */
				"WHERE item.id = '$this->_id'");
		} else {
			$result = DataBase::query(
				"SELECT $query ".
				"FROM ".$m->getItemTableName()." AS item ".
				"WHERE item.id = '$this->_id'");
		}
		if ($result->next()) {
			$values = $result->getRow();
			
			$this->_name = $values['model_name'];
			$this->_creatorID = $values['model_creatorID'];
			if ($m->getFlags() & ModelFlags::CREATOR_FIELDS)
				$this->_createTime = new DateTime($values['model_createTime']);
			$this->_editorID = $values['model_editorID'];
			if ($m->getFlags() & ModelFlags::EDITOR_FIELDS)
				$this->_editTime = new DateTime($values['model_editTime']);
			$this->_editTimes = $values['model_editTimes'];
			
			return $values;
		} else
			return false;
	}
	
	// ===========================================================================
	
	/**
	 * Gets the id of this model
	 *
	 * @return int the id
	 */
	public function getID() {
		$this->checkDisposed();
		
		return $this->_id;
	}
	
	/**
	 * Gets an object that provides information about the model class
	 * 
	 * @return Premanager\Modeling\ModelDescriptor the model descriptor
	 */
	public abstract function getModelDescriptor();
	
	// ===========================================================================
	
	/**
	 * Gets the name of this model
	 * 
	 * @return string the name
	 * @throws Premanager\NotSupportedException this model does not have a name
	 */
	protected function getName() {
		$this->checkDisposed();
		
		if (!($this->getModelDescriptor()->getFlags() &
			(ModelFlags::TRANSLATED_NAME | ModelFlags::UNTRANSLATED_NAME)))
			throw new NotSupportedException('This model does not have a name');
			
		if ($this->_name === null)
			$this->load();
		return $this->_name;
	}
	
	/**
	 * Gets the user who has created this model
	 * 
	 * @return Premanager\Models\User the creator
	 * @throws Premanager\NotSupportedException this model does not have creator
	 *   fields
	 */
	protected function getCreator() {
		$this->checkDisposed();
		
		if (!($this->getModelDescriptor()->getFlags() & ModelFlags::CREATOR_FIELDS))
			throw new NotSupportedException('This model does not have creator fields');
			
		if ($this->_creator === false) {
			if ($this->_creatorID === null)
				$this->load();
			$this->_creator = User::getByID($this->_creatorID);
		}
		return $this->_creator;
	}
	
	/**
	 * Gets the date/time when this model has been created
	 * 
	 * @return Premanager\DateTime the date/time of creation
	 * @throws Premanager\NotSupportedException this model does not have creator
	 *   fields
	 */
	protected function getCreateTime() {
		$this->checkDisposed();
		
		if (!($this->getModelDescriptor()->getFlags() & ModelFlags::CREATOR_FIELDS))
			throw new NotSupportedException('This model does not have creator fields');
			
		if ($this->_createTime === false)
			$this->load();
		return $this->_createTime;
	}
	
	/**
	 * Gets the user who has edited this model the last time, or the creator, if
	 * this model does not have been edited yet 
	 * 
	 * @return Premanager\Models\User the creator or editor, respectively
	 * @throws Premanager\NotSupportedException this model does not have editor
	 *   fields
	 */
	protected function getEditor() {
		$this->checkDisposed();
		
		if (!($this->getModelDescriptor()->getFlags() & ModelFlags::EDITOR_FIELDS))
			throw new NotSupportedException('This model does not have editor fields');
			
		if ($this->_editor === false) {
			if ($this->_editorID === null)
				$this->load();
			$this->_editor = User::getByID($this->_editorID);
		}
		return $this->_editor;
	}
	
	/**
	 * Gets the date/time when this model has been edited the last time, or the
	 * create time, if this model does not have been edited yet 
	 * 
	 * @return Premanager\DateTime the date/time of the last edit or creation,
	 *   respectively
	 * @throws Premanager\NotSupportedException this model does not have editor
	 *   fields
	 */
	protected function getEditTime() {
		$this->checkDisposed();
		
		if (!($this->getModelDescriptor()->getFlags() & ModelFlags::EDITOR_FIELDS))
			throw new NotSupportedException('This model does not have editor fields');
			
		if ($this->_editTime === false)
			$this->load();
		return $this->_editTime;
	}
	
	/**
	 * Gets the count of times this model has been edited
	 * 
	 * @return Premanager\DateTime the count of edit times
	 * @throws Premanager\NotSupportedException this model does not have editor
	 *   fields
	 */
	protected function getEditTimes() {
		$this->checkDisposed();
		
		if (!($this->getModelDescriptor()->getFlags() & ModelFlags::EDITOR_FIELDS))
			throw new NotSupportedException('This model does not have editor fields');
			
		if ($this->_editTimes === null)
			$this->load();
		return $this->_editTimes;
	}
}

?>
