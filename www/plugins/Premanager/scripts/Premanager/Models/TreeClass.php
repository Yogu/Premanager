<?php             
namespace Premanager\Models;

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
use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\QueryList;
use Premanager\Modeling\DataType;
use Premanager\IO\DataBase\DataBase;
              
/**
 * A class for a tree of dynamic page nodes
 */
final class TreeClass extends Model {
	private $_id;
	private $_pluginID;
	private $_plugin;
	private $_className;
	private $_scope;
	private $_key;
	
	private static $_instances = array();
	private static $_count;
	/**
	 * @var Premanager\Modeling\ModelDescriptor
	 */
	private static $_descriptor;
	/**
	 * @var Premanager\Modeling\QueryList
	 */
	private static $_queryList;

	// ===========================================================================    
	
	protected function __construct() {
		parent::__construct();	
	}
	
	private static function createFromID($id, $pluginID = null,
		$className = null, $scope = null, $key = null) {
		
		if (\array_key_exists($id, self::$_instances)) {
			$instance = self::$_instances[$id]; 
			if ($instance->_pluginID === null)
				$instance->_pluginID = $pluginID;
			if ($instance->_className === null)
				$instance->_className = $className;
			if ($instance->_scope === null)
				$instance->_scope = $scope;
			if ($instance->_key === null)
				$instance->_key = $key;
				
			return $instance;
		}

		if (!Types::isInteger($id) || $id < 0)
			throw new ArgumentException('$id must be a nonnegative integer value',
				'id');
				
		$instance = new self();
		$instance->_id = $id;
		$instance->_pluginID = $pluginID;
		$instance->_className = $className;
		$instance->_scope = $scope;
		$instance->_key = $key;
		self::$_instances[$id] = $instance;
		return $instance;
	} 

	// ===========================================================================
	
	/**
	 * Gets a tree class using its id
	 * 
	 * @param int $id the id of the tree class
	 * @return Premanager\Models\TreeClass
	 */
	public static function getByID($id) {
		$id = (int)$id;
			
		if (!Types::isInteger($id) || $id < 0)
			return null;
		else if (array_key_exists($id, self::$_instances)) {
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
	 * Gets a tree class using its key
	 * 
	 * @param string $pluginName the plugin name
	 * @param string $key the key
	 * @return Premanager\Models\TreeClass
	 */
	public static function getByKey($pluginName, $key) {
		$pluginID = Plugin::getIDFromName($pluginName);
		$result = DataBase::query(
			"SELECT tree.id ".
			"FROM ".DataBase::formTableName('Premanager', 'Trees')." AS tree ".
			"WHERE tree.pluginID = '$pluginID' AND ".
				"tree.key = '".DataBase::escape($key)."'");
		if ($result->next())
			return self::getByID($result->get('id'));
		else
			return null;
	}
	
	/**
	 * Creates a new tree class and inserts it into data base
	 *
	 * @param Premanager\Models\Plugin $plugin the plugin that registers this
	 *   tree class             
	 * @param string $className the class name for the tree
	 * @param int $scope enum Premanager\Models\Scope
	 * @param string $key a unique string to identify the tree class within plugin
	 *   context
	 * @return Premanager\Models\TreeClass
	 */
	public static function createNew(Plugin $plugin, $className, $scope, $key) {
		$className = \trim($className);
		
		if (!$plugin)
			throw new ArgumentNullException('plugin');
			
		if (!\class_exists($className))
			throw new ArgumentException('$className does not refer to an '.
				'existing class', 'className');
		
		// Check if the class extends PageNode
		$class = $className;
		while ($class != 'Premanager\Execution\PageNode') {
			$class = get_parent_class($class);
			if (!$class)
				throw new ArgumentException('The class specified by $className '.
					'does not inherit from Premanager\Execution\PageNode', 'className');
		}
		
		// Check if the key already exists
		if ($key && self::getByKey($plugin->getName(), $key))
			throw new ArgumentException('The key \''.$key.'\' already exists for '.
				'plugin '.$plugin->getName(), 'key');
	
		switch ($scope) {
			case Scope::BOTH:
				$dbScope = 'both';
				break;
			case Scope::ORGANIZATION:
				$dbScope = 'organization';
				break;
			case Scope::PROJECTS:
				$dbScope = 'projects';
				break;
			default:
				throw new InvalidEnumArgumentException('scope', $scope,
					'Premanager\Scope');
		}
		
		DataBase::query(
			"INSERT INTO ".DataBase::formTableName('Premanager', 'Trees')." ".
			"(pluginID, class, scope) ".
			"VALUES ('$plugin->getid()', '".DataBase::escape($className)."', ".
				"'$dbScope'");
		$id = DataBase::insertID();
		
		$instance = self::createFromID($id, $plugin, $className, $key);

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
				"FROM ".DataBase::formTableName('Premanager', 'Trees')." AS tree");
			self::$_count = $result->get('count');
		}
		return self::$_count;
	}  

	/**
	 * Gets a list of tree classes
	 * 
	 * @return Premanager\Modeling\QueryList
	 */
	public static function getTreeClasses() {
		if (!self::$_queryList)
			self::$_queryList = new QueryList(self::getDescriptor());
		return self::$_queryList;
	}          

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Modeling\ModelDescriptor
	 */
	public static function getDescriptor() {
		if (self::$_descriptor === null) {
			self::$_descriptor = new ModelDescriptor(__CLASS__, array(
				'id' => array(DataType::NUMBER, 'getID', 'id'),
				'plugin' => array(Plugin::getDescriptor(), 'getPlugin', 'pluginID'),
				'className' => array(DataType::STRING, 'getClassName', 'class'),
				'scope' => array(DataType::NUMBER, 'getScope',
					"CASE !scope! WHEN 'organization' THEN 0 WHEN 'projects' THEN 1 ".
					"ELSE 2 END")),
				'Premanager', 'Trees', array(__CLASS__, 'getByID'), false);
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
	 * Gets the scope
	 *
	 * @return int the scope (enum Premanager\Models\Scope)
	 */
	public function getScope() {
		$this->checkDisposed();
			
		if ($this->_scope === null)
			$this->load();
		return $this->_scope;	
	}   

	/**
	 * Creates a new instance of this tree class
	 * 
	 * @param Premanager\Execution\StructurePageNode $parent the parent page node
	 * @param Premanager\Models\StructureNode the structure node that embeds this
	 *   tree class
	 * @return Premanager\Execution\PageNode
	 */
	public function createInstance(StructurePageNode $parent,
		StructureNode $structureNode)  {
		if (!class_exists($this->getclassName()))
			throw new CorruptDataException('The class of tree class '.$this->_id.
				' ('.$this->getclassName().') does not exist');
		$instance = new $this->_className($parent, $structureNode);
		if (!($instance instanceof PageNode))
			throw new CorruptDataException('The class of tree class '.$this->_id.
				' ('.$this->getclassName().') does not inherit from '.
				'Premanager\Execution\PageNode');
		return $instance;
	}   
	
	/**
	 * Deletes and disposes this tree class
	 */
	public function delete() {         
		$this->checkDisposed();
			
		// Delete nodes
		$result = DataBase::query(
			"SELECT node.id ".
			"FROM ".DataBase::formTableName('Premanger', 'Nodes')." AS node ".
			"WHERE node.treeID = '$this->_id'");
		// Change node type to SIMPLE because otherwise we could not delete 
		// them
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanger', 'Nodes')." ".
			"SET id = 0 ".
			"WHERE treeID = '$this->_id'");
		while ($result->next()) {
			$node = StructureNode::getFromID($result->get('nodeID'));
			$node->delete();
		}
			
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanger', 'Trees')." ".
			"WHERE tree.id = '$this->_id'");
			
		if (self::$_count !== null)
			self::$_count--;	
	
		$this->_id = null;
	}           

	// ===========================================================================   
	
	private function load() {
		$result = DataBase::query(
			"SELECT tree.pluginID, tree.class, tree.scope, tree.key ".    
			"FROM ".DataBase::formTableName('Premanager', 'Trees')." AS tree ".
			"WHERE tree.id = '$this->_id'");
		
		if (!$result->next())
			return false;
		
		$this->_pluginID = $result->get('pluginID');
		$this->_className = $result->get('class');
		$this->_key = $result->get('key');
		
		$dbScope = $result->get('scope');
		switch ($dbScope) {
			case 'organization':
				$this->_scope = Scope::ORGANIZATION;
				break;
			case 'projects':
				$this->_scope = Scope::PROJECTS;
				break;
			default:
				$this->_scope = Scope::BOTH;
		}
		
		return true;
	}      
}

?>
