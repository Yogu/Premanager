<?php             
namespace Premanager\Models;

use Premanager\Module;
use Premanager\Model;
use Premanager\ArgumentException;
use Premanager\ArgumentNullException;
use Premanager\InvalidOperationException;
use Premanager\Strings;
use Premanager\Types;
use Premanager\IO\CorrputDataException;
use Premanager\Debug\Debug;
use Premanager\Debug\AssertionFailedException;
use Premanager\QueryList\QueryList;
use Premanager\QueryList\DataType;
use Premanager\QueryList\ModelDescriptor;
              
/**
 * A class for a theme
 */
final class ThemeClass extends Model {
	private $_id;
	private $_pluginID;
	private $_plugin;
	private $_className;
	private $_instance;
	
	private static $_instances = array();
	private static $_count;
	private static $_default;
	private static $_descriptor;
	private static $_queryList;

	// ===========================================================================  

	/**
	 * The id of this theme class
	 *
	 * Ths property is read-only.
	 * 
	 * @var int
	 */
	public $id = Module::PROPERTY_GET;
   
	/**
	 * The plugin that has registered this theme class
	 *
	 * Ths property is read-only.
	 * 
	 * @var string
	 */
	public $plugin = Module::PROPERTY_GET;
	
	/**
	 * The class name of this theme
	 *
	 * Ths property is read-only.
	 * 
	 * @var string
	 */
	public $className = Module::PROPERTY_GET;    
	
	/**
	 * An instance of this theme class
	 *
	 * Ths property is read-only.
	 * 
	 * @var Premanager\Execution\Theme
	 */
	public $instance = Module::PROPERTY_GET;    

	// ===========================================================================  
	
	protected function __construct() {
		parent::__construct();	
	}
	
	private static function createFromID($id, $pluginID = null,
		$className = null) {
		
		if (array_key_exists($id, self::$_instances)) {
			$instance = self::$_instances[$id]; 
			if ($instance->_pluginID === null)
				$instance->_pluginID = $pluginID;
			if ($instance->_className === null)
				$instance->_className = $className;
				
			return $instance;
		}

		if (!Types::isInteger($id) || $id < 0)
			throw new ArgumentException(
				'$id must be a nonnegative integer value');
				
		$instance = new self();
		$instance->_id = $id;
		$instance->_pluginID = $pluginID;
		$instance->_className = $className;
		self::$_instances[$id] = $instance;
		return $instance;
	} 

	// ===========================================================================
	
	/**
	 * Gets a theme class using its id
	 * 
	 * @param int $id the id of the theme class
	 * @return Premanager\Models\ThemeClass
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
	 * Creates a new theme class and inserts it into data base
	 *
	 * @param Plugin $plugin the plugin that registers this theme class             
	 * @param string $className the class name for the theme 
	 * @return Premanager\Models\ThemeClass
	 */
	public static function createNew(Plugin $plugin, $className) {
		$className = trim($className);
		
		if (!$plugin)
			throw new ArgumentNullException('plugin');
			
		if (!class_exists($className))
			throw new ArgumentException('$className does not refer to an existing '.
				'class', 'className');
		
		// Check if the class extends ThemeNode
		$class = $className;
		while ($class != 'Premanager\Execution\Theme') {
			$class = get_parent_class($class);
			if (!$class)
				throw new ArgumentException('The class specified by $className '.
					'does not inherit from Premanager\Execution\Theme');
		}
	
		DataBase::query(
			"INSERT INTO ".DataBase::formTableName('Premanager_Themes')." ".
			"(pluginID, class) ".
			"VALUES ('$plugin->id', '".DataBase::escape($className)."'");
		$id = DataBase::insertID();
		
		$instance = self::createFromID($id, $plugin, $className);

		if (self::$_count !== null)
			self::$_count++;		
		
		return $instance;
	}        
	    
	/**
	 * Gets the count of themes
	 *
	 * @return int
	 */
	public static function getCount() {
		if (self::$_count === null) {
			$result = DataBase::query(
				"SELECT COUNT(theme.themeID) AS count ".
				"FROM ".DataBase::formTableName('Premanager_Themes')." AS theme");
			self::$_count = $result->get('count');
		}
		return self::$_count;
	}  

	/**
	 * Gets the default theme class
	 * 
	 * @return Premanager\Models\ThemeClass
	 */
	public static function getDefault() {
		if (self::_defaul === null) {
			$result = DatabBase::query(
				"SELECT theme.themeID, theme.pluginID, theme.class ".
				"FROM ".DataBase::formTableName('Premanager_Themes')." AS theme ".
				"WHERE theme.isDefault = '1'");
			if ($result->next()) {
				self::$_default = self::createFromID($result->get('themeID'),
					$result->get('pluginID'), $result->get('class'));
			} else
				throw new CorruptDataException('No default theme found');
		}
		return self::_default;
	}
	
	/**
	 * Sets the default theme class
	 * 
	 * @param Premanager\Models\ThemeClass $theme the new default theme class
	 */
	public static function setDefault(ThemeClass $theme) {
		if (!$theme)
			throw new ArgumentNullException('theme');
		
		// Remove former default theme
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager_Themes')." AS theme ".
			"SET theme.isDefault = '0' ".
			"WHERE theme.isDefault = '1'");
		
		// Set the new default theme
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager_Themes')." AS theme ".
			"SET theme.isDefault = '1' ".
			"WHERE theme.themeID = '$theme->id'");
		
		self::$_default = $theme;
	}

	/**
	 * Gets a list of theme classes
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getThemeClasses() {
		if (!self::$_queryList)
			self::$_queryList = new QueryList(self::getDescriptor());
		return self::$_queryList;
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
				'plugin' => Plugin::getDescriptor(),
				'className' => DataType::STRING,
				'creator' => User::getDescriptor(),
				'createTime' => DataType::DATE_TIME,
				'editor' => User::getDescriptor(),
				'editTime' => DataType::DATE_TIME),
				'Premanager_Themes', array(__CLASS__, 'getByID'));
		}
		return self::$_descriptor;
	}      

	// ===========================================================================
	
	/**
	 * Gets the id of this theme class
	 *
	 * @return int
	 */
	public function getID() {
		$this->checkDisposed();
	
		return $this->_id;
	}

	/**
	 * Gets the plugin that has registered this theme class
	 *
	 * @return Premanager\Models\Plugin
	 */
	public function getPlugin() {
		$this->checkDisposed();
			
		if ($this->_plugin === null) {
			if ($this->_pluginID === null)
				$this->load();
			$this->_plugin = Plugin::getByID($this->_pluginID);
		}
		return $this->_plugin;	
	}        

	/**
	 * Gets the class name for this theme
	 *
	 * @return string
	 */
	public function getClassName() {
		$this->checkDisposed();
			
		if ($this->_className === null)
			$this->load();
		return $this->_className;	
	}        

	/**
	 * Creates an instance of this theme class
	 * 
	 * @return Premanager\Models\Theme
	 */
	public function getInstance()  {
		if ($this->_instance === null)
			$this->_instance = new $this->className();
		return $this->_instance;
	}   
	
	/**
	 * Deletes and disposes this theme class
	 * 
	 * If this theme was the default theme, any other theme will get the new
	 * default theme.
	 * 
	 * Throws a Premanager\InvalidOperationException if this is the last theme
	 * because there must be at least one theme
	 */
	public function delete() {         
		$this->checkDisposed();
		
		// Check if this is the last theme
		if (self::getCount() == 1)
			throw new InvalidOperationException('Last theme can not be deleted');
			
		// If this was the default theme, select another default theme
		if (self::getDefault() == $this) {
			$arr = self::getThemeClasses(0, 1);
			self::setDefault($arr[0]);
		}
			
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanger_Themes')." ".
			"WHERE theme.themeID = '$this->_id'");
			
		unset(self::$_instances[$this->_id]);
		if (self::$_count !== null)
			self::$_count--;			
	
		$this->dispose();
	}              
	
	private function load() {
		$result = DataBase::query(
			"SELECT theme.pluginID, theme.className ".    
			"FROM ".DataBase::formTableName('Premanager_Nodes')." AS node ".
			"WHERE theme.themeID = '$this->_id'");
		if (!$result->next())
			return false;
		
		$this->_pluginID = $result->get('pluginID');
		$this->_className = $result->get('className');
		
		return true;
	}
}

?>