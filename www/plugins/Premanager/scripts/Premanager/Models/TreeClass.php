<?php             
namespace Premanager\Models;

use Premanager\InvalidEnumArgumentException;
use Premanager\Execution\PageNode;
use Premanager\Execution\StructurePageNode;
use Premanager\IO\CorruptDataException;
use Premanager\Module;
use Premanager\Modeling\Model;
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

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Models\TreeClassModel
	 */
	public static function getDescriptor() {
		return TreeClassModel::getInstance();
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
		return self::getDescriptor()->createNew($plugin, $className, $scope, $key);
	}       

	/**
	 * Gets a list of tree classes
	 * 
	 * @return Premanager\Modeling\QueryList
	 */
	public static function getTreeClasses() {
		return self::getDescriptor()->getQueryList();
	}                                            

	// ===========================================================================

	/**
	 * Gets a boulde of information about the TreeClass model
	 *
	 * @return Premanager\Models\TreeClassModel
	 */
	public function getModelDescriptor() {
		return TreeClassModel::getInstance();
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
		// Unlink the nodes from the tree class because otherwise we could not
		// delete them
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanger', 'Nodes')." ".
			"SET id = 0 ".
			"WHERE treeID = '$this->_id'");
		while ($result->next()) {
			$node = StructureNode::getFromID($result->get('nodeID'));
			$node->delete();
		}
		
		$this->delete();
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
		$fields[] = 'pluginID';
		$fields[] = 'class';
		$fields[] = 'scope';
		$fields[] = 'key';
		
		if ($values = parent::load($fields)) {
			$this->_pluginID = $values['pluginID'];
			$this->_className = $values['class'];
			$this->_key = $values['key'];
		
			$dbScope = $values['scope'];
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
		}
		
		return $values;
	}    
}

?>
