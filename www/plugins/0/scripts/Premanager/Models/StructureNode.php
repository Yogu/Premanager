<?php                 
namespace Premanager\Models;

use Premanager\Execution\Environment;
use Premanager\IO\DataBase\DataBaseHelper;
use Premanager\QueryList\QueryOperation;
use Premanager\QueryList\QueryExpression;
use Premanager\Module;
use Premanager\Model;
use Premanager\DateTime;
use Premanager\Types;
use Premanager\Strings;
use Premanager\ArgumentException;
use Premanager\InvalidEnumArgumentException;
use Premanager\InvalidOperationException;
use Premanager\NameConflictException;
use Premanager\IO\CorruptDataException;
use Premanager\Debug\Debug;
use Premanager\Debug\AssertionFailedException;
use Premanager\Models\StructureNode;
use Premanager\Models\TreeClass;
use Premanager\Models\Project;
use Premanaager\Models\Groups;
use Premanager\QueryList\ModelDescriptor;
use Premanager\QueryList\QueryList;
use Premanager\QueryList\DataType;
use Premanager\IO\DataBase\DataBase;

/**
 * A structure node
 */
final class StructureNode extends Model {
	private $_id;
	private $_name;
	private $_title;  
	private $_parent = false;
	private $_parentID;
	private $_project = false;
	private $_projectID;
	private $_treeClass = false;
	private $_treeID;
	private $_noAccessRestriction;
	private $_hasPanel;
	private $_creator;
	private $_creatorID;
	private $_createTime;
	private $_editor;    
	private $_editorID;
	private $_editTime; 
	private $_url;
	private $_authorizedGroups;
	private $_children;
	
	private static $_instances = array();
	private static $_descriptor;
	private static $_queryList;                 

	// ===========================================================================  
	
	protected function __construct() {
		parent::__construct();	
	}
	
	/**
	 * @param int $id
	 * @param Premanager\Models\StructureNode $parent
	 * @param string $name
	 * @param string $title
	 * @param bool $noAccessRestriction
	 * @param Premanager\Models\TreeClass $treeClass
	 * @param bool $hasPanel
	 */
	private static function createFromID($id, $parent = false, $name = null,
		$title = null, $noAccessRestriction = null, $treeClass = false,
		$hasPanel = null) {
		if ($name !== null)
			$name = \trim($name);
		if ($title !== null)
			$title = \trim($title);
		$hasPanel = !!$hasPanel;
		if ($hasPanel && $tree)
			throw new ArgumentException('A node can not have both a tree and a '.
				'panel');

		if (\array_key_exists($id, self::$_instances)) {
			$instance = self::$_instances[$id];

			if ($instance->_parent === false)
				$instance->_parent = $parent;
			if ($instance->_name === null)
				$instance->_name = $name;
			if ($instance->_title === null)
				$instance->_title = $title;
			if ($instance->_noAccessRestriction === null)
				$instance->_noAccessRestriction = $noAccessRestriction;
			if ($instance->_treeClass === false)
				$instance->_treeClass = $tree;
			if ($instance->_hasPanel === null)
				$instance->_hasPanel = $hasPanel;

			return $instance;	
		}

		if (!Types::isInteger($id) || $id <= 0)
			throw new ArgumentException(
				'$id must be a positive integer value', 'id'); 

		$instance = new self();
		$instance->_id = (int) $id;
		$instance->_parent = $parent;
		$instance->_name = $name;
		$instance->_title = $title;
		$instance->_noAccessRestriction = $noAccessRestriction;
		$instance->_treeClass = $treeClass;
		$instance->_hasPanel = $hasPanel;
		self::$_instances[$id] = $instance;
		return $instance;
	} 
	
	/**
	 * Gets a structure node using its id
	 * 
	 * @param int $id the id of the session
	 * @return Premanager\Models\StructureNode
	 */
	public static function getByID($id) {
		if (!Types::isInteger($id) || $id < 0)
			throw new ArgumentException(
				'$id must be a nonnegative integer value', 'id');
		
		// This switch allows not to call load() on models that have already been
		// created
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
	 * Gets a list of structure nodes
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getStructureNodes() {
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
				'id' => array(DataType::NUMBER, 'getID', 'id'),
				'name' => array(DataType::STRING, 'getName', '*name'),
				'title' => array(DataType::STRING, 'getTitle', '*title'),
				'parent' => array('this', 'getParent', 'parentID'),
				'tree' => array(TreeClass::getDescriptor(), 'getTree', 'treeID'),
				'creator' => array(User::getDescriptor(), 'getCreator', 'creatorID'),
				'createTime' => array(DataType::DATE_TIME, 'getCreateTime',
					'createTime'),
				'editor' => array(User::getDescriptor(), 'getEditor', 'editorID'),
				'editTime' => array(DataType::DATE_TIME, 'getEditTime', 'editTime')),
				'Premanager', 'Nodes', array(__CLASS__, 'getByID'), true);
		}
		return self::$_descriptor;
	}

	/**
	 * Checks whether the name is a valid structure node name
	 * 
	 * Note: this does NOT check whether the name is available
	 * (see isNameAvailable())
	 * 
	 * @param string $name the name to check
	 * @return bool true, if the name is valid
	 */
	public static function isValidName($name) {
		return (!strlen($name) || $name[0] != '+') && strpos($name, '/') === false;
	}

	// ===========================================================================
	
	/**
	 * Gets the id of this structure node
	 *
	 * @return int
	 */
	public function getID() {
		$this->checkDisposed();
	
		return $this->_id;
	}

	/**
	 * Gets the name used in urls
	 *
	 * @return string
	 */
	public function getName() {
		$this->checkDisposed();
			
		if ($this->_name === null)
			$this->load();
		return $this->_name;	
	}    

	/**
	 * Gets the displayed title
	 *
	 * @return string
	 */
	public function getTitle() {
		$this->checkDisposed();
			
		if ($this->_title === null)
			$this->load();
		return $this->_title;	
	}     
	
	/**
	 * Gets the parent node or null, if this node is a root node
	 *
	 * @return Premanager\Models\StructureNode
	 */
	public function getParent() {
		$this->checkDisposed();
			
		if ($this->_parent === false) {
			if ($this->_parentID === null)
				$this->load();
			if ($this->_parentID)
				$this->_parent = StructureNode::createFromID($this->_parentID);
			else
				$this->_parent = null;
		}
		return $this->_parent;
	} 
	
	/**
	 * Gets the project that owns this structure node
	 *
	 * @return Premanager\Models\Project
	 */
	public function getProject() {
		$this->checkDisposed();
			
		if ($this->_project === false) {
			if ($this->_projectID === null)
				$this->load();
			$this->_project = Project::getByID($this->_projectID);
		}
		return $this->_project;
	}

	/**
	 * Gets a value that indicates if all users can access this page
	 * 
	 * If false, only members of explicitly specified groups can access this page.
	 * 
	 * To visitors that can not access this page, it seems if this page would not
	 * exist.
	 *
	 * @return bool
	 */
	public function getNoAccessRestriction() {
		$this->checkDisposed();
			
		if ($this->_noAccessRestriction === null)
			$this->load();
		return $this->_noAccessRestriction;	
	}          

	/**
	 * Gets a the content type (enum Premanager\Models\StructureNodeType)
	 * 
	 * SIMPLE means that this node only shows a list of its subpages
	 * PANEL means that this page has  a user-defined panel
	 * TREE means that this node is assigned to a TreeNode which specifies
	 * the contents and subpages
	 *
	 * @return int (enum Premanager\Models\StructureNodeType)
	 */
	public function getType() {
		$this->checkDisposed();
			
		if ($this->_hasPanel === null || $this->_treeID === null)
			$this->load();
		
		if ($this->_hasPanel)
			return StructureNodeType::PANEL;
		else if ($this->_treeID)
			return StructureNodeType::TREE;
		else
			return StructureNodeType::SIMPLE;
	}

	/**
	 * Gets the tree node class of this node, if $type is TREE
	 * 
	 * If $type is not TREE, returns null
	 *
	 * @return Premanager\Models\TreeClass
	 */
	public function getTreeClass() {
		$this->checkDisposed();

		if ($this->_treeClass === false) {
			if ($this->_treeID === null)
				$this->load();
			if ($this->_treeID) {
				if (!$this->_treeClass = TreeClass::getByID($this->_treeID))
					throw new CorruptDataException('Tree class '.$this->_treeID.' which '.
						'is assigned to structure node '.$this->_id.' does not exist');
			} else
				$this->_treeClass = null;
		}
		return $this->_treeClass;
	}

	/**
	 * Gets the user that has created this node
	 *
	 * @return Premanager\Models\User
	 */
	public function getCreator() {
		$this->checkDisposed();
			
		if ($this->_creator === null) {
			if (!$this->_creatorID)
				$this->load();
			$this->_creator = User::getByID($this->_creatorID);
		}
		return $this->_creator;	
	}                        

	/**
	 * Gets the time when this group has been created
	 *
	 * @return Premanager\DateTime
	 */
	public function getCreateTime() {
		$this->checkDisposed();
			
		if ($this->_createTime === null)
			$this->load();
		return $this->_createTime;	
	}                               

	/**
	 * Gets the user that has edited this group the last time
	 *
	 * @return Premanager\Models\User
	 */
	public function getEditor() {
		$this->checkDisposed();
			
		if ($this->_editor === null) {
			if (!$this->_editorID)
				$this->load();
			$this->_editor = User::getByID($this->_editorID);
		}
		return $this->_editor;	
	}                        

	/**
	 * Gets the time when this group has been edited the last time
	 *
	 * @return Premanager\DateTime
	 */
	public function getEditTime() {
		$this->checkDisposed();
			
		if ($this->_editTime === null)
			$this->load();
		return $this->_editTime;	
	}             

	/**
	 * Creates a structure node that exists already in data base, using its name
	 *
	 * Returns null if $name is not found
	 *                                           
	 * @param string $name name of structure node
	 * @return Premanager\Models\StructureNode  
	 */
	public function getChild($name) {	
		$this->checkDisposed();
				
		$result = DataBase::query(
			"SELECT name.id ".            
			"FROM ".DataBase::formTableName('Premanager', 'NodesName')." AS name ".
			"INNER JOIN ".DataBase::formTableName('Premanager', 'Nodes')." AS node ".
				"ON name.id = node.id ".
			"WHERE node.parentID = '$this->_id' ". 
				"AND name.name = '".DataBase::escape(Strings::unitize($name))."'");
		if ($result->next()) {
			$user = self::createFromID($result->get('id'), $this);
			return $user;
		}
		return null;
	}      
	
	/**
	 * Gets a list of child nodes
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public function getChildren() {
		$this->checkDisposed();
		
		if (!$this->_children) {
			$l = self::getStructureNodes();
			$this->_children =
				$l->filter($l->exprEqual(
					$l->exprMember('parent'), $this));
		}
		return $this->_children;
	}
	
	/**
	 * Gets the url to this structure node, relative to the url trunk
	 */
	public function getURL() {
		$this->checkDisposed();
		
		if (!$this->_url) {
			if ($this->getParent()) {
				$parentURL = $this->getParent()->getURL();
				if ($parentURL)
					$this->_url = $parentURL . '/';
				$this->_url .= htmlspecialchars($this->getName());
			} else
				$this->_url = htmlspecialchars($this->getProject()->getName());
		}
		return $this->_url;
	}
	
	/**
	 * Determines if the specified user can access this node
	 *
	 * @param User user whose permission to be checked
	 * @return bool
	 */
	public function canAccess(User $user) {
		$this->checkDisposed();
			
		if ($this->getNoAccessRestriction())
			return true;
		else {
			// If you need a special access permission, check whether there is a group
			// of which the user is a member and which has access permission
			$result = DataBase::query(
				"SELECT grp.id ".
				"FROM ".DataBase::formTableName('Premanager', 'Groups')." AS grp ".
				"INNER JOIN ".DataBase::formTableName('Premanager', 'UserGroup').
					" AS userGroup ".
					"ON userGroup.userID = '".$user->getID()."' ".
					"AND userGroup.groupID = grp.id ".
				"INNER JOIN ".DataBase::formTableName('Premanager', 'NodeGroup').
					" AS nodeGroup ".
					"ON nodeGroup.groupID = grp.id ".
					"AND nodeGroup.nodeID = '$this->_id'");
			return $result->next();
		}
	}
	    
	/**
	 * Gets a list of groups that can access this node
	 *
	 * @return Premanager\QueryList\QueryList
	 */
	public function getAuthorizedGroups() {     
		$this->checkDisposed();
			
		if ($this->_authorizedGroups === null)
			$this->_authorizedGroups =
				Group::getGroups()->joinFilter('Premanager', 'NodeGroup',
				"item.id = [join].groupID AND [join].nodeID = ".$this->_id);
		return $this->_authorizedGroups;
	}  
	
	/**
	 * Changes the list of groups which can access this node. All old permissions
	 * are removed and the new ones are added.
	 * 
	 * @param array $groups the new array of authorized groups 
	 */
	public function setAuthorizedGroups(array $groups) {
		$this->checkDisposed();
		
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'NodeGroup')." ".
			"WHERE nodeID = '$this->_id'");
		$sql = "";
		foreach ($groups as $group) {
			if ($group instanceof Group) {
				if ($sql)
					$sql .= ", ";
				$sql .= "($this->_id, ".$group->getID().")";
			}
		}
		if ($sql) {
			DataBase::query(
				"INSERT INTO ".DataBase::formTableName('Premanager', 'NodeGroup')." ".
				" (nodeID, groupID) ".
				"VALUES $sql ");
		}
	}
	      
	/**
	 * Changes various properties
	 * 
	 * This values will be changed in data base and in this object.
	 *
	 * @param string $name the name of this node
	 * @param string $title the title of this node
	 * @param bool $noAccessRestriction speicifies if all users can access this
	 *   node   
	 * @param int $type TYPE_SIMPLE, TYPE_PANEL or TYPE_TREE
	 * @throws Premanager\NameConflictException $name is not available
	 */
	public function setValues($name, $title, $noAccessRestriction = null,
		$type = null) {
		$this->checkDisposed();
			
		$name = Strings::normalize($name);
		$title = \trim($title);
		if ($noAccessRestriction === null)
			$noAccessRestriction = $this->getnoAccessRestriction();
		else
			$noAccessRestriction = !!$noAccessRestriction;
		
		if (!$name)
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');
		if (!self::isValidName($name))
			throw new ArgumentException('$name must not contain slashes and must '.
				'not begin with a plus sign', 'name');
		if (!$this->isNameAvailable($name, $this))
			throw new NameConflictException('There is already a structure node with '.
				'the same parent and that name', $name);
		if (!$title)
			throw new ArgumentException(
				'$title is an empty string or contains only whitespaces', 'title');
			
		$nameChanged = $name != $this->getName();
			
		if ($type !== null) {
			switch ($type)  {
				case StructureNodeType::SIMPLE:
					if ($this->gettree())
						throw new InvalidOperationException('The type of a node with type '.
							'TREE can not be changed');
					$hasPanel = false;
					break;
				case StructureNodeType::PANEL:
					if ($this->gettree())
						throw new InvalidOperationException('The type of a node with type '.
							'TREE can not be changed');
					$hasPanel = true;
					break;
				case StructureNodeType::TREE:
					if (!$this->gettree())
						throw new InvalidOperationException('The type of a node can not '.
							'be changed to TREE');
					$hasPanel = false;
					break;
				default:
					throw new InvalidEnumArgumentException('type', $type,
						'Premanage\Models\StructureNodeType'); 
			}
		} else
			$hasPanel = $this->getType() == StructureNodeType::PANEL;
			
		DataBaseHelper::update('Premanager', 'Nodes',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::IS_TREE,
			$this->_id, $name,
			array(
				'noAccessRestriction' => $noAccessRestriction,
				'hasPanel' => $hasPanel),
			array(
				'name' => $name,
				'title' => $title),
			$this->getParent() ? $this->getParent()->getID() : 0
		);
		
		if ($nameChanged)
			$this->_url = null;
		
		$this->_name = $name;
		$this->_title = $title;	
		$this->_noAccessRestriction = $noAccessRestriction;
		$this->_hasPanel = $hasPanel;
		
		$this->_editTime = new DateTime();
		$this->_editor = Environment::getCurrent()->getUser();
	}     
	
	/**
	 * Sets wheater this group is locked or not
	 * 
	 * This value will be changed in data base and in this object.
	 * 
	 * Throws Premanager\NameConflictException if not all names of this node are
	 * available in the new parent node
	 *
	 * @param string $isLocked specifies wheater only users with the 'lockGroups'
	 *   right can edit this group
	 */
	public function setParent(StructureNode $parent) {
		$this->checkDisposed();
			
		if ($parent == $this->getParent())
			return;
			
		if (!$this->getParent())
			throw new InvalidOperationException('Root node can not be moved');
		if (!$parent)
			throw new ArgumentNullException('parent');
		if ($parent == $this)
			throw new ArgumentException('A node can not become its own parent',
				'parent');
		if ($parent->isChildOf($this))
			throw new ArgumentException('$parent is a child of this', 'parent');
		if ($parent->getproject() != $this->_project)
			throw new ArgumentException('$parent\'s project must be the same '.
				'project like this node\'s project', 'parent');
		if ($parent->gettype() == StructureNodeType::TREE)
			throw new ArgumentException('$parent\'s type is TREE, so it '.
				'can not contain children', 'parent');
		if (!$parent->areNamesAvailable($this))
			throw new NameConflictException('Can not move this node into $parent '.
				'because some of the names of this node are not available in $parent.');
			
		// All names have to be deleted because otherwise they would be moved to the
		// new parent, too
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'NodesName')." ".
			"WHERE id = '$this->_id'");
			
		// Re-insert up-to-date names
		DataBaseHelper::rebuildNameTable('Premanager', 'Nodes',
			DataBaseHelper::IS_TREE, $this->_id);
			
		// Now update parent id
		DataBaseHelper::update('Premanager', 'Nodes',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::IS_TREE , $this->_id, null,
			array(
				'parentID' => $parent->getid()),
			array()
		);           
		
		// Update child count
		if ($this->getParent()->_childCount !== null)
			$this->getParent()->_childCount--;
		if ($parent->_childCount !== null)	
			$parent->_childCount++;
		
		$this->_parentID = $parent->getID();
		$this->_parent = $parent;
		
		self::clearURLCache();
		
		$this->_editTime = new DateTime();
		$this->_editor = Environment::getCurrent()->getUser();
	}
	
	/**
	 * Changes $noAccessRestriction property
	 * 
	 * This value will be changed in data base and in this object.
	 *
	 * @param bool $noAccessRestriction speicifies if all users can access this
	 *   node
	 */
	public function setNoAccessRestriction($noAccessRestriction) {
		$this->checkDisposed();
			
		$noAccessRestriction = !!$noAccessRestriction;
			
		DataBaseHelper::update('Premanager', 'Nodes',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::IS_TREE,
			$this->_id, null,
			array(
				'noAccessRestriction' => $noAccessRestriction),
			array()
		);
		
		$this->_noAccessRestriction = $noAccessRestriction;
		
		$this->_editTime = new DateTime();
		$this->_editor = Environment::getCurrent()->getuser();
	}   
	
	/**
	 * Changes type property
	 * 
	 * This value will be changed in data base and in this object. 
	 *
	 * @param int $type StructureNodeType::SIMPLE or StructureNodeType::PANEL
	 */
	public function setType($type) {
		$this->checkDisposed();
			
		switch ($type)  {
			case StructureNodeType::SIMPLE:
				if ($this->gettree())
					throw new InvalidOperationException('The type of a node with type '.
						'TREE can not be changed');
				$hasPanel = false;
				break;
			case StructureNodeType::TYPE_PANEL:
				if ($this->gettree())
					throw new InvalidOperationException('The type of a node with type '.
						'TREE can not be changed');
				$hasPanel = true;
				break;
			case StructureNodeType::TYPE_TREE:
				if (!$this->gettree())
					throw new InvalidOperationException('The type of a node can not '.
						'be changed to TREE');
				$hasPanel = false;
				break;
			default:
				throw new InvalidEnumArgumentException('type', $type,
					'Premanager\Models\StructureNodeType');
		}
		
		if ($hasPanel == $this->gethasPanel())
			return;
			
		DataBaseHelper::update('Premanager', 'StructureNodes', 'nodeID',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::IS_TREE,
			$this->_id, null,
			array(
				'hasPanel' => $hasPanel),
			array()
		);
			
		$this->_hasPanel = $hasPanel;
		
		$this->_editTime = new DateTime();
		$this->_editor = Environment::getCurrent()->getuser();
	}     

	/**
	 * Creates a new structure node and inserts it into data base
	 * 
	 * The type StructureNodeType::TREE is not allowed here because those nodes
	 * are created automatically.
	 *
	 * @param string $name group name
	 * @param string $title user title
	 * @param int $type content type (StructureNodeType::SIMPLE or ~::PANEL) 
	 * @param StructureTree $tree if $type is TYPE_TREE, the structure tree
	 * @return StructureNode
	 * @throws Premanager\NameConflictException $name is not available
	 */
	public function createChild($name, $title, $type) {
		$name = Strings::normalize($name);
		$title = trim($title);
		
		if (!$name)
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');
		if (!self::isValidName($name))
			throw new ArgumentException('$name must not contain slashes and must '.
				'not begin with a plus sign', 'name');
		if (!$this->isNameAvailable($name))
			throw new NameConflictException('There is already a child node with the '.
				'same name', $name);
		if (!$title)
			throw new ArgumentException(
				'$title is an empty string or contains only whitespaces', 'title');  
		
		switch ($type) {
			case StructureNodeType::SIMPLE:
			case StructureNodeType::PANEL:
				break;
				
			case StructureNodeType::TREE:
				throw new ArgumentException('Nodes with type TREE can not be created '.
					'manually', 'type');
				break;
			
			default:
				throw new InvalidEnumArgumentException('type', $type,
					'Premanager\Models\StructureNodeType');
		}
	
		$id = DataBaseHelper::insert('Premanager', 'Nodes',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS, $name,
			array(
				'noAccessRestriction' => true,
				'parentID' => $this->_id,
				'projectiD' => $this->getProject()->getID(),
				'hasPanel' => $type == StructureNodeType::PANEL,
				'treeID' => 0),
			array(
				'title' => $title)
		);
		
		$instance = self::createFromID($id, $parent, $name, $title, true);
		$instance->_creator = Environment::getCurrent()->getuser();
		$instance->_createTime = new DateTime();
		$instance->_editor = Environment::getCurrent()->getuser();
		$instance->_editTime = new DateTime();
		
		return $instance;
	}  
	
	/**
	 * Deletes and disposes this node
	 */
	public function delete() {         
		$this->checkDisposed();
			
		// First check if there are any children with TYPE_TREE type, because they
		// can not be deleted
		if (!$this->canDelete())
			throw new InvalidOperationException('This node can not be deleted');
			
		// Delete children
		foreach ($this->getChildren() as $child) {
			$child->delete();
		}

		// Delete this node
		DataBaseHelper::delete('Premanager', 'Nodes',
			DataBaseHelper::IS_TREE,
			$this->_id);      
			    
		// Delete group permissions
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'NodeGroup')." ".
			"WHERE nodeID = '$this->_id'");
		
		if ($this->getparent() && $this->getparent()->_childCount !== null)
			$this->getparent()->_childCount--;

		$this->dispose();
	}
	
	/**
	 * Checks if this node can be deleted.
	 * 
	 * Nodes with TREE type can not be deleted, as well as nodes with a child
	 * that can not be deleted 
	 * 
	 * @return bool true, if this node can be deleted
	 */
	public function canDelete() {
		if ($this->getTreeClass())
			return false;
		else
			foreach ($this->getChildren() as $child) {
				if (!$child->canDelete())
					return false;
			}
		return true;
	} 

	/**
	 * Checks if a name is not already assigned to a child node
	 * 
	 * Note: this does NOT check whether the name is valid (see isValidName())
	 *
	 * @param $name name to check 
	 * @param Premanager\Models\StructureNode|null $ignoreThis a node which may
	 *   have the name; it is excluded
	 * @return bool true, if $name is available
	 */
	public function isNameAvailable($name, $ignoreThis = null) {   
		$this->checkDisposed();

		return DataBaseHelper::isNameAvailable('Premanager', 'Nodes',
			DataBaseHelper::IS_TREE, $name,
			($ignoreThis instanceof StructureNode ? $ignoreThis->_id : null),
			$this->_id);
	}  

	/**
	 * Checks if all active names of the specified node are available in this node
	 *
	 * @param Premanager\Models\StructureNode $node node whose names to be
	 *   checked 
	 * @return bool true, if all names are available
	 */
	public function areNamesAvailable(StructureNode $node) {   
		$this->checkDisposed();

		$result = DataBase::query(
			"SELECT name.name ".
			"FROM ".DataBase::formTableName('Premanager', 'NodesName')." AS name ".
			"WHERE name.id = '$node->_id' ".
				"AND name.inUse = '1'");
		while ($result->next()) {
			if (!DataBaseHelper::isNameAvailable('Premanager', 'Nodes',
				DataBaseHelper::IS_TREE,
				$result->get('name'), null, $this->_id))
					return false;
		}
		return true;
	}
	
	/**
	 * Checks if this is a child or a grandchild or a gread-grandchild and so on
	 * of the specified node
	 *
	 * @param Premanager\Models\StructureNode $node node to be tested 
	 * @return bool true, if this node is a child of $node
	 */
	public function isChildOf(StructureNode $node) {
		return $this->getParent() && 
			($this->getParent() == $node || $this->getParent()->isChildOf($node));
	}

	// ===========================================================================       
	
	private function load() {
		$result = DataBase::query(
			"SELECT node.parentID, node.projectID, node.noAccessRestriction, ".
				"node.hasPanel, node.treeID, translation.name, translation.title, ". 
				"node.creatorID, node.editorID, node.createTime, node.editTime ".    
			"FROM ".DataBase::formTableName('Premanager', 'Nodes')." AS node ",
			/* translating */
			"WHERE node.id = '$this->_id'");
		
		if (!$result->next())
			return false;
		
		$this->_name = $result->get('name');
		$this->_title = $result->get('title');
		$this->_parentID = (int) $result->get('parentID');
		$this->_projectID = (int) $result->get('projectID');
		$this->_noAccessRestriction = !!$result->get('noAccessRestriction');
		$this->_hasPanel = (bool) $result->get('hasPanel');
		$this->_treeID = (int) $result->get('treeID');
		$this->_creatorID = (int) $result->get('creatorID');
		$this->_editorID = (int) $result->get('editorID');
		$this->_createTime = new DateTime($result->get('createTime'));
		$this->_editTime = new DateTime($result->get('editTime'));
		
		return true;
	}

	/**
	 * Deletes the value of $_url properties of all instances
	 */
	private static function clearURLCache() {
		foreach (self::$_instances as $instance) {
			$instance->_url = null;
		}
	}
}

?>
