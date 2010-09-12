<?php
namespace Premanager\Models;

use Premanager\QueryList\ModelDescriptor;
use Premanager\QueryList\QueryList;
use Premanager\Module;
use Premanager\Model;
use Premanager\ArgumentException;
use Premanager\PageTree\TreeNode;
use Premanager\Models\Plugin;
use Premanager\Models\StructureNode;
use Premanager\Debug\AssertionFailedException;
use Premanager\IO\DataBase\DataBase;
use Premanager\QueryList\MemberInfo;
use Premanager\QueryList\MemberKind;
use Premanager\QueryList\DataType;

/**
 * A plugin
 */
final class Plugin extends Model {
	private $_id;
	private $_name;

	private static $_instances = array();
	private static $_count;
	private static $_descriptor;
	private static $_queryList;

	// ===========================================================================

	/**
	 * The id of this plugin
	 *
	 * Ths property is read-only.
	 *
	 * @var int
	 */
	public $id = Module::PROPERTY_GET;

	/**
	 * The plugin name
	 *
	 * Ths property is read-only.
	 *
	 * @var string
	 */
	public $name = Module::PROPERTY_GET;

	// ===========================================================================

	protected function __construct() {
		parent::__construct();
	}

	private static function createFromID($id, $name = null) {
		if ($name !== null)
		$name = trim($name);

		if (array_key_exists($id, self::$_instances)) {
			$instance = self::$_instances[$id];
			if ($instance->_name === null)
			$instance->_name = $name;

			return $instance;
		}

		if (!is_int($id) || $id < 0)
		throw new InvalidArgumentException(
				'$id must be a nonnegative integer value');

		$instance = new User();
		$instance->_id = $id;
		$instance->_name = $name;
		self::$_instances[$id] = $instance;
		return $instance;
	}

	// ===========================================================================

	/**
	 * Gets a plugin using its id
	 *
	 * @param int $id the id of the plugin
	 * @return Premanager\Models\Plugin
	 */
	public static function getByID($id) {
		if (!Types::isInteger($id) || $id < 0)
		throw new ArgumentException(
				'$id must be a nonnegative integer value', 'id');
			
		if (\array_key_exists($id, self::$_instances)) {
			return self::$_instances[$id];
		} else {
			$instance = self::createFromID($id);
			// Check if id is correct
			if ($instance->load())
			return $instance;
			else
			return null;
		}
	}

	/**
	 * Creates a new plugin and inserts it into data base
	 *
	 * @param string $name the plugin name
	 * @return Plugin
	 */
	public static function createNew($name) {
		$name = \trim($name);

		if (!\preg_match('/^[A-Za-z][A-Za-z0-9]*$/', $name))
		throw new ArgumentException('$name is not a valid plugin name', 'name');

		if (!$this->isNameAvailable($name))
		throw new ArgumentException(
				'There is already a plugin with this name', 'name');

		DataBase::query(
			"INSERT INTO ".DataBase::formTableName('Premanager_Plugins')." ".
			"(name) ".
			"VALUES ('".DataBase::escape($name)."'");
		$id = DataBase::insertID();

		$instance = self::createFromID($id, $name);

		if (self::$_count !== null)
		self::$_count++;
		foreach (self::$_instances as $instance)
		$instance::$_index = null;

		return $instance;
	}

	/**
	 * Gets the count of plugins
	 *
	 * @return int
	 */
	public static function getCount() {
		if (self::$_count === null) {
			$result = DataBase::query(
				"SELECT COUNT(plugin.pluginID) AS count ".
				"FROM ".DataBase::formTableName('Premanager_Plugins')." AS plugin");
			self::$_count = $result->get('count');
		}
		return self::$_count;
	}

	/**
	 * Gets a list of plugins
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getPlugins() {
		if (!self::$_queryList)
			self::$_queryList = new QueryList(self::getDescriptor());
		return self::$_queryList;
	}

	/**
	 * Checks if a name is available
	 *
	 * Checks, if $name is not already assigned to a plugin.
	 *
	 * @param $name name to check
	 * @return bool true, if $name is available
	 */
	public static function isNameAvailable($name) {
		$result = DataBase::query(
			"SELECT plugin.pluginID ".
			"FROM ".DataBase::formTableName('Premanager_Plugins')." AS plugin ".
			"WHERE LOWER(plugin.name) = '".
		DataBase::escape(Strings::unitize($name)."'"));
		return !$result->next();
	}

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\QueryList\ModelDescriptor
	 */
	public static function getDescriptor() {
		if (self::$_descriptor === null) {
			self::$_descriptor = new ModelDescriptor(__CLASS__, array(
				'id' => DataType::NUMBER,
				'name' => DataType::STRING),
				'Premanager_Plugins', array(__CLASS__, 'getByID'));
		}
		return self::$_descriptor;
	}

	// ===========================================================================

	/**
	 * Gets the id of this plugin
	 *
	 * @return int
	 */
	public function getID() {
		$this->checkDisposed();

		return $this->_id;
	}

	/**
	 * Gets the plugin name
	 *
	 * @return string
	 */
	public function getName() {
		$this->checkDisposed();
			
		if ($this->_name === null) {
			$this->load();
		}
		return $this->_name;
	}

	/**
	 * Deletes the reference to this plugin off the data base
	 *
	 * No files are deleted, and no other data sets are changed than the plugins
	 * DataBase::formTableName.
	 */
	public function delete() {
		$this->checkDisposed();
			
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanger_Plugin')." ".
			"WHERE plugin.pluginID = '$this->_id'");
			
		unset(self::$_instances[$this->_id]);
		if (self::$_count !== null)
		self::$_count--;

		$this->dispose();
	}

	// ===========================================================================

	private function load() {
		$result = DataBase::query(
			"SELECT plugin.name ".    
			"FROM ".DataBase::formTableName('Premanager_Plugins')." AS plugin ".
			"WHERE plugin.pluginID = '$this->_id'");

		if (!$result->next())
		return false;

		$this->name = $result->get('name');

		return true;
	}
}

?>