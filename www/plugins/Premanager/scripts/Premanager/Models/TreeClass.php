<?php             
namespace Premanager\Models;

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
              
/**
 * A class for a tree of dynamic page nodes
 */
final class TreeClass extends Model {
	private $_id;
	private $_pluginID;
	private $_plugin;
	private $_className;
	private $_index;
	
	private static $_instances = array();
	private static $_count;
	private static $_descriptor;
	private static $_queryList;

	// ===========================================================================  

	/**
	 * The id of this tree class
	 *
	 * Ths property is read-only.
	 * 
	 * @var int
	 */
	public $id = Module::PROPERTY_GET;
   
	/**
	 * The plugin that has registered this tree class
	 *
	 * Ths property is read-only.
	 * 
	 * @var string
	 */
	public $plugin = Module::PROPERTY_GET;
	
	/**
	 * The class name of this tree
	 *
	 * Ths property is read-only.
	 * 
	 * @var string
	 */
	public $className = Module::PROPERTY_GET;        

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
				
		$instance = new User();
		$instance->_id = $id;
		$instance->_pluginID = $pluginID;
		$instance->_className = $className;
		self::$_instances[$id] = $instance;
		return $instance;
	} 

	// ===========================================================================
	
	/**
	 * Gets a tree class using its id
	 * 
	 * Make sure that there is a tree class with this id. Otherwise, the returned
	 * object will not work as expected.
	 * 
	 * @param int $id the id of the tree class
	 * @return Premanager\Models\TreeClass
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
	 * Creates a new tree class and inserts it into data base
	 *
	 * @param Premanager\Models\Plugin $plugin the plugin that registers this
	 *   tree class             
	 * @param string $className the class name for the tree 
	 * @return Premanager\Models\TreeClass
	 */
	public static function createNew(Plugin $plugin, $className) {
		$className = \trim($className);
		
		if (!$plugin)
			throw new ArgumentNullException('plugin');
			
		if (!\class_exists($className))
			throw new ArgumentException('$className does not refer to an '.
				'existing class', 'className');
		
		// Check if the class extends TreeNode
		$class = $className;
		while ($class != 'Premanager\PageTree\TreeNode') {
			$class = \get_parent_class($class);
			if (!$class)
				throw new ArgumentException('The class specified by $className '.
					'does not inherit from Premanager\PageTree\TreeNode', 'className');
		}
	
		DataBase::query(
			"INSERT INTO ".DataBase::formTableName('Premanager_Trees')." ".
			"(pluginID, class) ".
			"VALUES ('$plugin->id', '".DataBase::escape($className)."'");
		$id = DataBase::insertID();
		
		$instance = self::createFromID($id, $plugin, $className);

		if (self::$_count !== null)
			self::$_count++;	
		foreach (self::$_instances as $instance)
			$instance::$_index = null;	
		
		return $instance;
	}        
	    
	/**
	 * Gets the count of tree classes
	 *
	 * @return int
	 */
	public static function getCount() {
		if (self::$_count === null) {
			$result = DataBase::query(
				"SELECT COUNT(tree.treeID) AS count ".
				"FROM ".DataBase::formTableName('Premanager_Trees')." AS tree");
			self::$_count = $result->get('count');
		}
		return self::$_count;
	}  

	/**
	 * Gets a list of tree classes
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getTreeClasses() {
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
				'Premanager_Trees', array(__CLASS__, 'getByID'));
		}
		return self::$_descriptor;
	}                                           

	// ===========================================================================
	
	/**
	 * Gets the id of this tree class
	 *
	 * @return int
	 */
	public function getID() {
		$this->checkDisposed();
	
		return $this->_id;
	}

	/**
	 * Gets the plugin that has registered this tree class
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
	 * Gets the class name for this tree
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
	 * Creates a new instance of this tree class
	 * 
	 * @return Tree
	 */
	public function createInstance()  {
		return new $this->className();
	}   
	
	/**
	 * Deletes and disposes this tree class
	 */
	public function delete() {         
		$this->checkDisposed();
			
		// Delete nodes
		$result = DataBase::query(
			"SELECT node.nodeID ".
			"FROM ".DataBase::formTableName('Premanger_Nodes')." AS node ".
			"WHERE node.treeID = '$this->_id'");
		// Change node type to SIMPLE because otherwise we could not delete 
		// them
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanger_Nodes')." ".
			"SET treeID = 0 ".
			"WHERE node.treeID = '$this->_id'");
		while ($result->next()) {
			$node = StructureNode::getFromID($result->get('nodeID'));
			$node->delete();
		}
			
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanger_Trees')." ".
			"WHERE tree.treeID = '$this->_id'");
			
		if (self::$_count !== null)
			self::$_count--;	
		foreach (self::$_instances as $instance)
			$instance::$_index = null;		
	
		$this->_id = null;
	}           

	// ===========================================================================   
	
	private function load() {
		$result = DataBase::query(
			"SELECT tree.pluginID, tree.className ".    
			"FROM ".DataBase::formTableName('Premanager_Nodes')." AS node ".
			"WHERE tree.treeID = '$this->_id'");
		
		if (!$result->next())
			return false;
		
		$this->_pluginID = $result->get('pluginID');
		$this->_className = $result->get('className');
		
		return true;
	}      
}

?>