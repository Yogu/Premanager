<?php
namespace Premanager\Models;

use Premanager\Modeling\Model;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\IO\Config;
use Premanager\Execution\PluginInitializer;
use Premanager\NotImplementedException;
use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\QueryList;
use Premanager\Module;
use Premanager\Types;
use Premanager\ArgumentException;
use Premanager\PageTree\TreeNode;
use Premanager\Models\Plugin;
use Premanager\Models\StructureNode;
use Premanager\Debug\AssertionFailedException;
use Premanager\IO\DataBase\DataBase;
use Premanager\IO\CorruptDataException;
use Premanager\Modeling\MemberInfo;
use Premanager\Modeling\MemberKind;
use Premanager\Modeling\DataType;

/**
 * A plugin
 */
final class Plugin extends Model {
	private $_name;
	private $_initializerClassName;
	private $_backendTreeClassName;
	private $_initializer = false;
	private $_backendPageNode = false;
	
	private static $_descriptor;
	
	const NAME_REGEX = '/^[A-Za-z][A-Za-z0-9]*$/';

	// ===========================================================================

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Models\PluginModel
	 */
	public static function getDescriptor() {
		return PluginModel::getInstance();
	}
	
	/**
	 * Gets a plugin using its id
	 * 
	 * @param int $id
	 * @return Premanager\Models\Plugin
	 */
	public static function getByID($id) {
		return self::getDescriptor()->getByID($id);
	}
                               
	/**
	 * Gets a plugin using its name
	 *
	 * Returns null if $name is not found
	 *
	 * @param string $name name of the plugin
	 * @return Premanager\Models\Plugin
	 */
	public static function getByName($name) {
		return self::getDescriptor()->getByName($name);
	}
	    
	/**
	 * Gets the count of plugins
	 *
	 * @return int
	 */
	public static function getCount() {
		$result = DataBase::query(
			"SELECT COUNT(plugin.id) AS count ".
			"FROM ".DataBase::formTableName('Premanager', 'Plugins')." AS plugin");
		return $result->get('count');
	}

	/**
	 * Creates a new plugin and inserts it into data base
	 *
	 * @param string $name the plugin name
	 * @param string $initializerClassName the name of a class that implements
	 *   Premanager\Execution\PluginInitializer. Can be an empty string.
	 * @param string $initializerClassName the name of a class that extends
	 *   Premanager\Execution\PageNode. Can be an empty string.
	 * @return Premanager\Models\Plugin
	 */
	public static function createNew($name, $initialiizerClassName = '',
		$backendTreeClassName = '') {
		return self::getDescriptor()->createNew($name,
			$initialiizerClassName, $backendTreeClassName);
	}

	/**
	 * Gets a list of plugins
	 * 
	 * @return Premanager\Modeling\QueryList
	 */
	public static function getPlugins() {
		return self::getDescriptor()->getQueryList();
	}              

	/**
	 * Checks whether the name is a valid plugin name
	 * 
	 * Note: this does NOT check whether the name is available
	 * (see isNameAvailable())
	 * 
	 * @param string $name the name to check
	 * @return bool true, if the name is valid
	 */
	public static function isValidName($name) {
		$name = Strings::normalize($name);
		return $name && preg_match(self::NAME_REGEXP, $name);
	}

	/**
	 * Checks if a name is available
	 *
	 * Checks, if $name is not already assigned to a plugin.
	 *
	 * @param $name name to check
	 * @param Premanager\Models\Plugin $ignoreThis a plugin whose name is to be
	 *   excluded
	 * @return bool true, if $name is available
	 */
	public static function isNameAvailable($name, Plugin $ignoreThis) {
		return self::getDescriptor()->isNameAvailable($name, $ignoreThis);
	}
	
	/**
	 * Gets the id of the plugin specified by its name
	 * 
	 * @param string $name
	 * @return int
	 */
	public static function getIDFromName($name) {
		return PluginModel::getInstance()->getIDFromName($name);
	}

	// ===========================================================================

	/**
	 * Gets a boulde of information about the Plugin model
	 *
	 * @return Premanager\Models\PluginModel
	 */
	public function getModelDescriptor() {
		return PluginModel::getInstance();
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
	 * Gets a class that can initialize this plugin or null if there is no
	 * initializer used
	 * 
	 * @return Premanager\Execution\PluginInitializer
	 */
	public function getInitializer() {
		$this->checkDisposed();
		
		if ($this->_initializer === false) {
			if ($this->_initializerClassName === null)
				$this->load();
				
			if ($this->_initializerClassName != '') {
				if (!class_exists($this->_initializerClassName))
					throw new CorruptDataException('The initializer class for the plugin '.
						$this->_name.' does not exist (\''.$this->_initializerClassName.'\')');
				$obj = new $this->_initializerClassName();
				if (!($obj instanceof PluginInitializer))
					throw new CorruptDataException('The initializer class for the plugin '.
						$this->_name.' ('.$this->_initializerClassName.') does not implement '.
							'Premanager\Execution\PluginInitializer');
				$this->_initializer = $obj;
			} else
				$this->_initializer = null;
		}
				
		return $this->_initializer;
	}
	
	/**
	 * Gets a PageNode that is used for backend requests or null if there is no
	 * backend tree class specified
	 * 
	 * @return Premanager\Execution\PageNode
	 */
	public function getBackendPageNode() {
		$this->checkDisposed();
		
		if ($this->_backendPageNode === false) {
			if ($this->_backendTreeClassName === null)
				$this->load();
				
			if ($this->_backendTreeClassName != '') {
				if (!class_exists($this->_backendTreeClassName))
					throw new CorruptDataException('The backend tree class for the '.
						'plugin '.$this->_name.' does not exist (\''.
						$this->_backendTreeClassName.'\')');
				$rootNode = new StructurePageNode();
				$obj = new $this->_backendTreeClassName($rootNode,
					'!'.$this->getName());
				if (!($obj instanceof PageNode))
					throw new CorruptDataException('The backend tree class for the '.
						'plugin '.$this->_name.' ('.$this->_backendTreeClassName.') does '.
						'not extend Premanager\Execution\PageNode');
				$this->_backendPageNode = $obj;
			} else
				$this->_backendPageNode = null;
		}
				
		return $this->_backendPageNode;
	}

	/**
	 * Deletes the reference to this plugin off the data base
	 *
	 * No files are deleted, and no other data sets are changed than the plugins
	 * DataBase::formTableName.
	 */
	public function delete() {
		parent::delete();
	}

	// ===========================================================================

	/**
	 * Fills the fields from data base
	 * 
	 * @param array $fields an array($name => $sql) where $sql is a SQL statement
	 *   to store under the alias $name
	 * @return array ($name => $value) the values for the fields - or false if the
	 *   model does not exist in data base
	 */
	public function load(array $fields = array()) {
		$fields[] = 'name';
		$fields[] = 'initializerClass';
		$fields[] = 'backendTreeClass';
		
		if ($values = parent::load($fields)) {
			$this->_name = $values['name'];
			$this->_initializerClassName = $values['initializerClass'];
			$this->_backendTreeClassName = $values['backendTreeClass'];
		}
		
		return $values;
	}
}

?>
