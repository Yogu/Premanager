<?php
namespace Premanager\Models;

use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\DataType;
use Premanager\IO\DataBase\QueryBuilder;
use Premanager\NotSupportedException;
use Premanager\IO\DataBase\DataBase;
use Premanager\Pages\AddGroupHomePage;
use Premanager\Models\User;
use Premanager\Modeling\ModelFlags;
use Premanager\InvalidOperationException;
use Premanager\ArgumentException;
use Premanager\Module;

/**
 * Provides a model descriptor for plugin models
 */
class PluginModel extends ModelDescriptor {
	private static $_instance;
	
	private $_idNameTable;
	
	// ===========================================================================
	
	/**
	 * Loads the members calling addProperty()
	 */
	protected function loadMembers() {
		parent::loadMembers();
	
		$this->addProperty('name', DataType::STRING, 'getName', 'name');
	}
	
	// ===========================================================================
	
	/**
	 * Gets the single instance of Premanager\Models\PluginModel
	 * 
	 * @return Premanager\Models\PluginModel the single instance of this class
	 */
	public static function getInstance() {
		if (self::$_instance === null)
			self::$_instance = new self();
		return self::$_instance;
	}
	
	/**
	 * Gets the name of the class this descriptor describes
	 * 
	 * @return string
	 */
	public function getClassName() {
		return 'Premanager\Models\Plugin';
	}
	
	/**
	 * Gets the name of the plugin containing the models
	 * 
	 * @return string
	 */
	public function getPluginName() {
		return 'Premanager';
	}
	
	/**
	 * Gets the name of the model's table
	 * 
	 * @return string
	 */
	public function getTable() {
		return 'Plugins';
	}
	
	/**
	 * Gets flags set for this model descriptor 
	 * 
	 * @return int (enum set Premanager\Modeling\ModelFlags)
	 */
	public function getFlags() {
		return ModelFlags::NO_NAME;
	}
	
	/**
	 * Gets an SQL expression that determines the name group for an item (alias
	 * for item table is 'item')
	 * 
	 * @return string an SQL expression
	 */
	public function getNameGroupSQL() {
		return '';
	}

	/**
	 * Creates a new plugin and inserts it into data base
	 *
	 * @param string $name the plugin name
	 * @param string $initializerClassName the name of a class that implements
	 *   Premanager\Execution\PluginInitializer. Can be an empty string.
	 * @param string $initializerClassName the name of a class that extends
	 *   Premanager\Execution\PageNode. Can be an empty string.
	 * @return Plugin
	 */
	public static function createNew($name, $initialiizerClassName = '',
		$backendTreeClassName = '') {
		$name = \trim($name);

		if (!Plugin::isValidName($name))
		throw new ArgumentException('$name is not a valid plugin name', 'name');

		if (!$this->isNameAvailable($name))
			throw new ArgumentException(
				'There is already a plugin with this name', 'name');
	
		if ($initialiizerClassName) {
			if (!\class_exists($initialiizerClassName))
				throw new ArgumentException('The class \''.$initialiizerClassName.
					'\' does not exist', 'initializerClassName');
			
			// Check if the class implements Premanager\Execution\PluginInitializer
			$obj = new $initialiizerClassName();
			if (!($obj instanceof PluginInitializer))
				throw new ArgumentException('The class '.$initialiizerClassName.' does '.
					'not implement Premanager\Execution\PluginInitializer');
		}
	
		if ($backendTreeClassName) {
			if (!\class_exists($backendTreeClassName))
				throw new ArgumentException('The class \''.$initialiizerClassName.
					'\' does not exist', 'backendTreeClassName');
			
			// Check if the class implements Premanager\Execution\PluginInitializer
			$obj = new $backendTreeClassName();
			if (!($obj instanceof PageNode))
				throw new ArgumentException('The class '.$backendTreeClassName.' does '.
					'not extend Premanager\Execution\PageNode');
		}

		DataBase::query(
			"INSERT INTO ".DataBase::formTableName('Premanager', 'Plugins')." ".
			"(name, initializerClass, backendTreeClass) ".
			"VALUES ('".DataBase::escape($name)."', ".
				"'".DataBase::escape($initialiizerClassName)."', ".
				"'".DataBase::escape($backendTreeClassName)."')");
		$id = DataBase::insertID();
		
		return $this->getByID($id);
	}
	
	/**
	 * Gets the id of the plugin specified by its name
	 * 
	 * @param string $name
	 * @return Premanager\Models\Plugin
	 */
	public function getIDFromName($name) {
		if (!$this->_idNameTable) {
			$this->_idNameTable = array();
			$result = DataBase::query(
				"SELECT plugin.id, plugin.name ".
				"FROM ".Config::getDataBasePrefix()."premanager_plugins AS plugin");
			while ($result->next()) {
				$this->_idNameTable[$result['name']] = $result['id'];
			}
		}
		return $this->_idNameTable[$name];
	}
                               
	/**
	 * Gets a project using its name
	 *
	 * Returns null if $name is not found
	 *
	 * @param string $name name of the project
	 * @return Premanager\Models\Project
	 */
	public function getByName($name) {
		$id = $this->getIDFromName($name);
		if ($id !== null)
			return $this->getByID($id);
		else
			return null;
	}

	/**
	 * Checks if a name is not already assigned to a project
	 * 
	 * Note: this does NOT check whether the name is valid (see isValidName())
	 *
	 * @param $name name to check 
	 * @param Premanager\Models\Plugin|null $ignoreThis a plugin which may have
	 *   the name; it is excluded
	 * @return bool true, if $name is available
	 */
	public function isNameAvailable($name, Project $ignoreThis = null)
	{
		$result = DataBase::query(
			"SELECT plugin.id ".
			"FROM ".DataBase::formTableName('Premanager', 'Plugins')." AS plugin ".
			"WHERE LOWER(plugin.name) = '".
		DataBase::escape(Strings::unitize($name)."'"));
		return !$result->next() ||
			($ignoreThis && $result->get('id') == $ignoreThis->getID());
	}
}

