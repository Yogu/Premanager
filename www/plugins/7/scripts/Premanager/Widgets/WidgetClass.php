<?php             
namespace Premanager\Widgets;

use Premanager\IO\DataBase\DataBaseHelper;
use Premanager\InvalidEnumArgumentException;
use Premanager\Execution\PageNode;
use Premanager\Execution\StructurePageNode;
use Premanager\IO\CorruptDataException;
use Premanager\Module;
use Premanager\Model;
use Premanager\Types;
use Premanager\Strings;
use Premanager\ArgumentException;
use Premanager\ArgumentNullException;
use Premanager\InvalidOperationException;
use Premanager\Debug\Debug;
use Premanager\Processing\TreeNode;
use Premanager\Models\Plugin;
use Premanager\Models\StructureNode;
use Premanager\QueryList\ModelDescriptor;
use Premanager\QueryList\QueryList;
use Premanager\QueryList\DataType;
use Premanager\IO\DataBase\DataBase;

/**
 * A class for widget
 */
final class WidgetClass extends Model {
	private $_id;
	private $_pluginID;
	private $_plugin;
	private $_className;
	private $_title;
	private $_description;
	
	private static $_instances = array();
	private static $_count;
	/**
	 * @var Premanager\QueryList\ModelDescriptor
	 */
	private static $_descriptor;
	/**
	 * @var Premanager\QueryList\QueryList
	 */
	private static $_queryList;

	// ===========================================================================    
	
	protected function __construct() {
		parent::__construct();	
	}
	
	private static function createFromID($id, $pluginID = null,
		$className = null) {
		
		if (\array_key_exists($id, self::$_instances)) {
			$instance = self::$_instances[$id]; 
			if ($instance->_pluginID === null)
				$instance->_pluginID = $pluginID;
			if ($instance->_className === null)
				$instance->_className = $className;
				
			return $instance;
		}

		if (!Types::isInteger($id) || $id < 0)
			throw new ArgumentException('$id must be a nonnegative integer value',
				'id');
				
		$instance = new self();
		$instance->_id = $id;
		$instance->_pluginID = $pluginID;
		$instance->_className = $className;
		self::$_instances[$id] = $instance;
		return $instance;
	} 

	// ===========================================================================
	
	/**
	 * Gets a widget class using its id
	 * 
	 * @param int $id the id of the tree class
	 * @return Premanager\Widgets\WidgetClass
	 */
	public static function getByID($id) {
		$id = (int)$id;
			
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
	 * Creates a new widget class and inserts it into data base
	 *
	 * @param Premanager\Models\Plugin $plugin the plugin that registers this
	 *   widget class             
	 * @param string $className the class name for the widget class
	 * @param string $title a title
	 * @param string $description a short description
	 * @return Premanager\Widgets\WidgetClass
	 */
	public static function createNew(Plugin $plugin, $className, $title,
		$description)
	{
		$className = trim($className);
			
		if (!\class_exists($className))
			throw new ArgumentException('$className does not refer to an '.
				'existing class', 'className');
		
		// Check if the class extends WidgetClass
		$class = $className;
		while ($class != 'Premanager\Widgets\WidgetClass') {
			$class = get_parent_class($class);
			if (!$class)
				throw new ArgumentException('The class specified by $className '.
					'does not inherit from Premanager\Widgets\WidgetClass',
					'className');
		}
		
		$id = DataBaseHelper::insert('Premanager.Widgets', 'WidgetClasses', 0, null,
			array(
				'pluginID' => $plugin->getID(),
				'class' => $className),
			array(
				'title' => $title,
				'description' => $description));
		
		$instance = self::createFromID($id, $plugin);

		if (self::$_count !== null)
			self::$_count++;
		
		return $instance;
	}        
	    
	/**
	 * Gets the count of widget classes
	 *
	 * @return int
	 */
	public static function getCount() {
		if (self::$_count === null) {
			$result = DataBase::query(
				"SELECT COUNT(widgetClass.treeID) AS count ".
				"FROM ".DataBase::formTableName('Premanager.Widgets', 'WidgetClasses').
					" AS widgetClass");
			self::$_count = $result->get('count');
		}
		return self::$_count;
	}  

	/**
	 * Gets a list of all widget classes
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getWidgetClasses() {
		if (!self::$_queryList)
			self::$_queryList = new QueryList(self::getDescriptor());
		return self::$_queryList;
	}          

	/**
	 * Gets a boundle of information about this model
	 *
	 * @return Premanager\QueryList\ModelDescriptor
	 */
	public static function getDescriptor() {
		if (self::$_descriptor === null) {
			self::$_descriptor = new ModelDescriptor(__CLASS__, array(
				'id' => array(DataType::NUMBER, 'getID', 'id'),
				'plugin' => array(Plugin::getDescriptor(), 'getPlugin', 'pluginID'),
				'className' => array(DataType::STRING, 'getClassName', 'class'),
				'title' => array(DataType::NUMBER, 'getTitle', '*title'),
				'description' => array(DataType::NUMBER, 'getDescription',
					'*description')),
				'Premanager.Widgets', 'WidgetClasses', array(__CLASS__, 'getByID'));
		}
		return self::$_descriptor;
	}                                           

	// ===========================================================================
	
	/**
	 * Gets the id of this widget class
	 *
	 * @return int
	 */
	public function getID() {
		$this->checkDisposed();
	
		return $this->_id;
	}

	/**
	 * Gets the plugin that has registered this widget class
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
	 * Gets the class name for this widget class
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
	 * Gets a translated title
	 *
	 * @return string the title
	 */
	public function getTitle() {
		$this->checkDisposed();
			
		if ($this->_title === null)
			$this->load();
		return $this->_title;	
	}         

	/**
	 * Gets a short description
	 *
	 * @return string the description
	 */
	public function getDescription() {
		$this->checkDisposed();
			
		if ($this->_description === null)
			$this->load();
		return $this->_description;
	}   
	
	/**
	 * Deletes and disposes this widget class
	 */
	public function delete() {         
		$this->checkDisposed();
		
		DataBaseHelper::delete('Premanager.Widgets', 'WidgetClasses', 0,
			$this->_id);
			
		//TODO: Delete options
			
		if (self::$_count !== null)
			self::$_count--;	
	
		$this->_id = null;
	}       

	public function getSampleBlock() {
		return call_user_func(array($this->getClassName(), 'getSampleBlock'));
	}

	// ===========================================================================   
	
	private function load() {
		$result = DataBase::query(
			"SELECT widgetClass.pluginID, widgetClass.class, translation.title, ".
				"translation.description ".
			"FROM ".DataBase::formTableName('Premanager.Widgets', 'WidgetClasses').
				" AS widgetClass ",
			/* translating */
			"WHERE widgetClass.id = '$this->_id'");
		
		if (!$result->next())
			return false;
		
		$this->_pluginID = $result->get('pluginID');
		$this->_className = $result->get('class');
		$this->_title = $result->get('title');
		$this->_description = $result->get('description');
		
		return true;
	}      
}

?>
