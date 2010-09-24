<?php             
namespace Premanager\Models;

use Premanager\IO\DataBase\DataBase;

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
 * A class for a style
 */
final class StyleClass extends Model {
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
	 * The id of this style class
	 *
	 * Ths property is read-only.
	 * 
	 * @var int
	 */
	public $id = Module::PROPERTY_GET;
   
	/**
	 * The plugin that has registered this style class
	 *
	 * Ths property is read-only.
	 * 
	 * @var string
	 */
	public $plugin = Module::PROPERTY_GET;
	
	/**
	 * The class name of this style
	 *
	 * Ths property is read-only.
	 * 
	 * @var string
	 */
	public $className = Module::PROPERTY_GET;    
	
	/**
	 * An instance of this style class
	 *
	 * Ths property is read-only.
	 * 
	 * @var Premanager\Execution\Style
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
	 * Gets a style class using its id
	 * 
	 * @param int $id the id of the style class
	 * @return Premanager\Models\StyleClass
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
	 * Creates a new style class and inserts it into data base
	 *
	 * @param Plugin $plugin the plugin that registers this style class             
	 * @param string $className the class name for the style 
	 * @return Premanager\Models\StyleClass
	 */
	public static function createNew(Plugin $plugin, $className) {
		$className = trim($className);
		
		if (!$plugin)
			throw new ArgumentNullException('plugin');
			
		if (!class_exists($className))
			throw new ArgumentException('$className does not refer to an existing '.
				'class', 'className');
		
		// Check if the class extends Premanager\Execution\Style
		$class = $className;
		while ($class != 'Premanager\Execution\Style') {
			$class = get_parent_class($class);
			if (!$class)
				throw new ArgumentException('The class specified by $className '.
					'does not inherit from Premanager\Execution\Style');
		}
	
		DataBase::query(
			"INSERT INTO ".DataBase::formTableName('Premanager_Styles')." ".
			"(pluginID, class) ".
			"VALUES ('$plugin->id', '".DataBase::escape($className)."'");
		$id = DataBase::insertID();
		
		$instance = self::createFromID($id, $plugin, $className);

		if (self::$_count !== null)
			self::$_count++;		
		
		return $instance;
	}        
	    
	/**
	 * Gets the count of styles
	 *
	 * @return int
	 */
	public static function getCount() {
		if (self::$_count === null) {
			$result = DataBase::query(
				"SELECT COUNT(style.id) AS count ".
				"FROM ".DataBase::formTableName('Premanager_Styles')." AS style");
			self::$_count = $result->get('count');
		}
		return self::$_count;
	}  

	/**
	 * Gets the default style class
	 * 
	 * @return Premanager\Models\StyleClass
	 */
	public static function getDefault() {
		if (self::$_default === null) {
			$result = DataBase::query(
				"SELECT style.id, style.pluginID, style.class ".
				"FROM ".DataBase::formTableName('Premanager_Styles')." AS style ".
				"WHERE style.isDefault = '1'");
			if ($result->next()) {
				self::$_default = self::createFromID($result->get('id'),
					$result->get('pluginID'), $result->get('class'));
			} else
				throw new CorruptDataException('No default style found');
		}
		return self::$_default;
	}
	
	/**
	 * Sets the default style class
	 * 
	 * @param Premanager\Models\StyleClass $style the new default style class
	 */
	public static function setDefault(StyleClass $style) {
		if (!$style)
			throw new ArgumentNullException('style');
		
		// Remove former default style
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager_Styles')." AS style ".
			"SET style.isDefault = '0' ".
			"WHERE style.isDefault = '1'");
		
		// Set the new default style
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager_Styles')." AS style ".
			"SET style.isDefault = '1' ".
			"WHERE style.id = '$style->id'");
		
		self::$_default = $style;
	}

	/**
	 * Gets a list of style classes
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getStyleClasses() {
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
				'className' => DataType::STRING),
				'Premanager_Styles', array(__CLASS__, 'getByID'));
		}
		return self::$_descriptor;
	}      

	// ===========================================================================
	
	/**
	 * Gets the id of this style class
	 *
	 * @return int
	 */
	public function getID() {
		$this->checkDisposed();
	
		return $this->_id;
	}

	/**
	 * Gets the plugin that has registered this style class
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
	 * Gets the class name for this style
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
	 * Creates an instance of this style class
	 * 
	 * @return Premanager\Models\Style
	 */
	public function getInstance()  {
		if ($this->_instance === null)
			$this->_instance = new $this->className();
		return $this->_instance;
	}   
	
	/**
	 * Deletes and disposes this style class
	 * 
	 * If this style was the default style, any other style will get the new
	 * default style.
	 * 
	 * Throws a Premanager\InvalidOperationException if this is the last style
	 * because there must be at least one style
	 */
	public function delete() {         
		$this->checkDisposed();
		
		// Check if this is the last style
		if (self::getCount() == 1)
			throw new InvalidOperationException('Last style can not be deleted');
			
		// If this was the default style, select another default style
		if (self::getDefault() == $this) {
			$arr = self::getStyleClasses(0, 1);
			self::setDefault($arr[0]);
		}
			
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager_Styles')." ".
			"WHERE style.id = '$this->_id'");
			
		unset(self::$_instances[$this->_id]);
		if (self::$_count !== null)
			self::$_count--;			
	
		$this->dispose();
	}              
	
	private function load() {
		$result = DataBase::query(
			"SELECT style.pluginID, style.className ".    
			"FROM ".DataBase::formTableName('Premanager_Styles')." AS style ".
			"WHERE style.id = '$this->_id'");
		if (!$result->next())
			return false;
		
		$this->_pluginID = $result->get('pluginID');
		$this->_className = $result->get('className');
		
		return true;
	}
}

?>