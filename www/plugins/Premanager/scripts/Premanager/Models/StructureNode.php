<?php                 
namespace Premanager\Models;

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
use Premangaer\Objects\Groups;
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
	private $_authorizedGroupsCount;
	private $_childCount;
	private $_children;
	
	private static $_instances = array();
	private static $_descriptor;
	private static $_queryList;

	// ===========================================================================  

	/**
	 * The id of this structure node
	 *
	 * Ths property is read-only.
	 * 
	 * @var int
	 */
	public $id = Module::PROPERTY_GET;      

	/**
	 * The name that is used in urls
	 *
	 * Ths property is read-only.
	 * 
	 * @see setValues()
	 * 
	 * @var string
	 */
	public $name = Module::PROPERTY_GET;    

	/**
	 * The title that is used in navigations (and it may be used in page titles)
	 *
	 * Ths property is read-only.     
	 * 
	 * @see setValues()
	 * 
	 * @var string
	 */
	public $title = Module::PROPERTY_GET; 

	/**
	 * The parent structure node. Is null, if this node is the root node of a
	 * project
	 * 
	 * @var Premanager\Models\StructureNode
	 */
	public $parent = Module::PROPERTY_GET_SET; 

	/**
	 * The project that owns this structure node
	 *
	 * Ths property is read-only.
	 *
	 * @var Premanager\Models\Project
	 */
	public $project = Module::PROPERTY_GET; 

	/**
	 * Specifies if all users can access this page or if they need a special right
	 *
	 * Ths property is read-only. 
	 * 
	 * @see setValues()
	 * 
	 * @var bool
	 */
	public $noAccessRestriction = Module::PROPERTY_GET; 

	/**
	 * Specifies if this node is only a list of subnodes, has a panel or is linked
	 * to a tree node. (enum Premanager\Models\StructureNodeType) 
	 * 
	 * @see setValues()
	 * 
	 * @var int
	 */
	public $type = Module::PROPERTY_GET_SET;

	/**
	 * The tree node assigned to this structure node.
	 * 
	 * This property is only available if $type is TREE.
	 *
	 * Ths property is read-only. 
	 * 
	 * @var Premanager\Models\TreeClass
	 */
	public $treeClass = Module::PROPERTY_GET; 

	/**
	 * The user that has created this group
	 *
	 * Ths property is read-only.  
	 * 
	 * @see setValues()
	 * 
	 * @var Premanager\Models\User
	 */
	public $creator = Module::PROPERTY_GET;    

	/**
	 * The time when this group has been created
	 *
	 * Ths property is read-only.
	 * 
	 * @var Premanager\DateTime
	 */
	public $createTime = Module::PROPERTY_GET;   

	/**
	 * The user that has edited this group the last time
	 *
	 * Ths property is read-only.
	 * 
	 * @var Premanager\Models\
	 */
	public $editor = Module::PROPERTY_GET;      

	/**
	 * The time when this group has been edited the last time
	 *
	 * Ths property is read-only.
	 * 
	 * @var Premanager\DateTime
	 */
	public $editTime = Module::PROPERTY_GET;                 

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
		$hasPanel = !! $hasPanel;
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
				$instance->_noAccessRestriction = (bool) $noAccessRestriction;
			if ($instance->_treeClass === false)
				$instance->_treeClass = $tree;
			if ($instance->_hasPanel === null)
				$instance->_hasPanel = (bool) $hasPanel;

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
		$instance->_noAccessRestriction = (bool) $noAccessRestriction;
		$instance->_treeClass = $treeClass;
		$instance->_hasPanel = (bool) $hasPanel;
		self::$_instances[$id] = $instance;
		return $instance;
	} 
	
	/**
	 * Gets a structure node using its id
	 * 
	 * @param int $id the id of the session
	 * @return Premanager\Models\Session
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
				'id' => DataType::NUMBER,
				'name' => DataType::STRING,
				'parent' => 'this',
				'tree' => TreeClass::getDescriptor(),
				'creator' => User::getDescriptor(),
				'createTime' => DataType::DATE_TIME,
				'editor' => User::getDescriptor(),
				'editTime' => DataType::DATE_TIME),
				'Premanager_Nodes', array(__CLASS__, 'getByID'));
		}
		return self::$_descriptor;
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
	 * @return Premangaer\Objects\Project
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
			"FROM ".DataBase::formTableName('Premanager_NodesName')." AS name ".
			"INNER JOIN ".DataBase::formTableName('Premanager_Nodes')." AS node ".
				"ON name.id = node.id ".
			"WHERE node.parentID = '$this->id' ". 
				"AND name.name = '".DataBase::escape(Strings::unitize($name))."'");
		if ($result->next()) {
			$user = self::createFromID($result->get('id'), $this);
			return $user;
		}
		return null;
	}      
	    
	/**
	 * Gets the count of subnodes
	 *
	 * @return int
	 */
	public function getChildCount() {        
		$this->checkDisposed();
			
		if ($this->_childCount === null) {
			$result = DataBase::query(
				"SELECT COUNT(node.id) AS count ".
				"FROM ".DataBase::formTableName('Premanager_Nodes')." AS node ".
				"WHERE node.parentID = ".$this->_id);
			$this->_childCount = $result->get('count');
		}
		return $this->_childCount;
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
				$l->filter($l->exprAnd(
					$l->expr(QueryOperation::NOT,
						$l->expr(QueryOperation::IS_NULL, $l->exprMember('parent'))),
					$l->exprEqual(
						$l->exprMember($l->exprMember('parent'), 'id'),
						$this->_id)));
		}
		return $this->_children;
	}
	
	/**
	 * Determines if the specified user can access this node
	 *
	 * @param User user whose permission to be checked
	 * @return bool
	 */
	public function canAccess(User $user) {
		$this->checkDisposed();
			
		if (!$user)
			throw new ArgumentNullException('user');
			
		if ($this->_noAccessRestriction)
			return true;
		else {
			// If you need a special access permission, check whether there is a group
			// of which the user is a member and which has access permission
			$result = DataBase::query(
				"SELECT group.groupID ".
				"FROM ".DataBase::formTableName('Premanager_Groups')." AS group ".
				"INNER JOIN ".DataBase::formTableName('Premanager_UserGroup')." AS userGroup ".
					"ON userGroup.userID = '$user->id' ".
					"AND userGroup.groupID = group.groupID ".
				"INNER JOIN ".DataBase::formTableName('Premanager_NodeGroup')." AS nodeGroup ".
					"ON nodeGroup.groupID = group.groupID ".
					"AND nodeGroup.nodeID = '$this->_id'");
			return $result->next();
		}
	}
	    
	/**
	 * Gets the count of groups that have a special access right
	 *
	 * @return int
	 */
	public function getAuthorizedGroupsCount() {     
		$this->checkDisposed();
			
		if ($this->_authorizedGroupsCount === null) {
			$result = DataBase::query(
				"SELECT COUNT(node.nodeID) AS count ".
				"FROM ".DataBase::formTableName('Premanager_Nodes')." AS node ".
				"WHERE node.parentID = ".$this->_id);
			$this->_authorizedGroupsCount = $result->get('count');
		}
		return $this->_authorizedGroupsCount;
	}  
	
	/**
	 * Gets a list of groups that have a special access right
	 *
	 * @param int $start index of first group
	 * @param int $count count of groups to return
	 * @return array
	 */
	public function getAuthorizedGroups($start = null, $count = null) {
		$this->checkDisposed();
			
		$start = $start ? $start : 0;
		$count = $count ? $count : 0;
		
		if (($start !== null && $count == null) ||
			($count !== null && $start === null))
			throw new ArgumentException('Either both $start and $count must '.
				'be specified or none of them');
				
		if ($start === null || $count === null) {
			if (!Types::isInteger($start) || $start < 0)
				throw new ArgumentException(
					'$start must be a positive integer value or null', 'start');
			if (!Types::isInteger($count) || $count < 0)
				throw new ArgumentException(
					'$count must be a positive integer value or null', 'count');		
		}  
	
		$list = array();
		$result = DataBase::query(
			"SELECT grp.id, translation.name, translation.title, grp.color ".
			"FROM ".DataBase::formTableName('Premanager_Groups')." AS grp ".
			"INNER JOIN ".DataBase::formTableName('Premanager_NodeGroup')." ".
				"AS nodeGroup ".
				"ON nodeGroup.groupID = group.id ",
			/* translating */ 
			"WHERE nodeGroup.nodeID = '$this->_id' ".
			"ORDER BY LOWER(translation.name) ASC ".
			($start !== null ? "LIMIT $start, $count" : ''));
		$list = '';
		while ($result->next()) {
			$group = Group::getByID($result->get('groupID'),
				$result->get('name'), $result->get('title'),
				$result->get('color'));
			$list[] = $group;
		}
		return $list;
	}
	
	/**
	 * Adds the specified group to the list of groups with access right
	 * 
	 * If the specified group has already the access right, nothing is done.
	 * 
	 * @param Premanager\Models\Group $group the group to add 
	 */
	public function addAuthorizedGroup(Group $group) {
		$this->checkDisposed();
			
		if (!$group)
			throw new ArgumentNullException('group');

		// If this group does already have the access right, just add 0 to 0
		// (do "nothing")
		DataBase::query(
			"INSERT INTO ".DataBase::formTableName('Premanager_NodeGroup')." ".
			"(nodeID, groupID) ".
			"VALUES ('$this->_id', '$group->id') ".
			"ON DUPLICATE KEY UPDATE 0+0");
			
		if (DataBase::getAffectedRows() && $this->_authorizedGroupsCount !== null)
			$this->_authorizedGroupsCount++;
	}
	
	/**
	 * Removes the specified group off the list of groups with access right
	 * 
	 * If the specified group is not in list, nothing is done.
	 * 
	 * @param Premanage\Objects\Group $group the group to remove
	 */
	public function removeAuthorizedGroup(Group $group) {
		$this->checkDisposed();
			
		if (!$group)
			throw new ArgumentNullException('group');

		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager_NodeGroup')." ".
			"WHERE nodeID = '$this->_id' ".
				"AND groupID = '$group->id'");   
			
		if (DataBase::getAffectedRows() && $this->_authorizedGroupsCount !== null)
			$this->_authorizedGroupsCount--;
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
	 */
	public function setValues($name, $title, $noAccessRestriction = null,
		$type = null) {
		$this->checkDisposed();
			
		$name = Strings::normalize($name);
		$title = \trim($title);
		if ($noAccessRestriction === null)
			$noAccessRestriction = $this->noAccessRestriction;
		else
			$noAccessRestriction = !!$noAccessRestriction;
		
		if (!$name)
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');
		if (strpos('/', $name) !== false)
			throw new ArgumentException('$name must not contain slashes', 'name');
		if (!isNameAvailable($name))
			throw new ArgumentException('There is already a structure node with that'.
				'name', 'name');
		if (!$title)
			throw new ArgumentException(
				'$title is an empty string or contains only whitespaces', 'title');
			
		if ($type !== null) {
			switch ($type)  {
				case StructureNodeType::SIMPLE:
					if ($this->tree)
						throw new InvalidOperationException('The type of a node with type '.
							'TREE can not be changed');
					$hasPanel = false;
					break;
				case StructureNodeType::PANEL:
					if ($this->tree)
						throw new InvalidOperationException('The type of a node with type '.
							'TREE can not be changed');
					$hasPanel = true;
					break;
				case StructureNodeType::TREE:
					if (!$this->tree)
						throw new InvalidOperationException('The type of a node can not '.
							'be changed to TREE');
					$hasPanel = false;
					break;
				default:
					throw new InvalidEnumArgumentException('type', $type,
						'Premanage\Objects\StructureNodeType'); 
			}
		} else
			$hasPanel = $this->hasPanel;
			
		DataBaseHelper::update('Premanager_StructureNodes', 'nodeID',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::IS_TREE,
			$this->_id, $name,
			array(
				'noAccessRestriction' => $noAccessRestriction,
				'hasPanel' => $hasPanel),
			array(
				'name' => $name,
				'title' => $title)
		);
		
		$this->_name = $name;
		$this->_title = $title;	
		$this->_noAccessRestriction = $noAccessRestriction;
		$this->_hasPanel = $hasPanel;
		
		$this->_editTime = new DateTime();
		$this->_editor = Environment::getCurrent()->user;
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
			
		if ($parent == $this->parent)
			return;
			
		if (!$this->parent)
			throw new InvalidOperationException('Root node can not be moved');
		if (!$parent)
			throw new ArgumentNullException('parent');
		if ($parent == $this)
			throw new ArgumentException('A node can not become its own parent',
				'parent');
		if ($parent->isChildOf($this))
			throw new ArgumentException('$parent is a child of this', 'parent');
		if ($parent->project != $this->_project)
			throw new ArgumentException('$parent\'s project must be the same '.
				'project like this node\'s project', 'parent');
		if ($parent->type == StructureNodeType::TREE)
			throw new ArgumentException('$parent\'s type is TREE, so it '.
				'can not contain children', 'parent');
		if (!$parent->areNamesAvailable($this))
			throw new NameConflictException('Can not move this node into $parent '.
				'because some of the names of this node are not available in $parent.');
			
		// All names have to be deleted because otherwise they would be moved to the
		// new parent, too
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager_NodesName')." ".
			"WHERE nodeID = '$this->_id'");
			
		// Re-insert up-to-date names
		DataBaseHelper::rebuildNameTable('Premanager_Nodes', 'nodeID', 
			DataBaseHelper::IS_TREE, $this->_id);
			
		// Now update parent id
		DataBaseHelper::update('Premanager_StructureNode', 'nodeID',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::IS_TREE , $this->_id, null,
			array(
				'parentID' => $parent->id),
			array()
		);           
		
		// Update child count
		if ($this->parent->_childCount !== null)
			$this->parent->_childCount--;
		if ($parent->_childCount !== null)	
			$parent->_childCount++;
		
		$this->_parentID = $parent->id;
		$this->_parent = $parent;
		
		$this->_editTime = new DateTime();
		$this->_editor = Environment::getCurrent()->user;
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
			
		DataBaseHelper::update('Premanager_StructureNodes', 'nodeID',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::IS_TREE,
			$this->_id, null,
			array(
				'noAccessRestriction' => $noAccessRestriction),
			array()
		);
		
		$this->_noAccessRestriction = $noAccessRestriction;
		
		$this->_editTime = new DateTime();
		$this->_editor = Environment::getCurrent()->user;
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
				if ($this->tree)
					throw new InvalidOperationException('The type of a node with type '.
						'TREE can not be changed');
				$hasPanel = false;
				break;
			case StructureNodeType::TYPE_PANEL:
				if ($this->tree)
					throw new InvalidOperationException('The type of a node with type '.
						'TREE can not be changed');
				$hasPanel = true;
				break;
			case StructureNodeType::TYPE_TREE:
				if (!$this->tree)
					throw new InvalidOperationException('The type of a node can not '.
						'be changed to TREE');
				$hasPanel = false;
				break;
			default:
				throw new InvalidEnumArgumentException('type', $type,
					'Premanager\Models\StructureNodeType');
		}
		
		if ($hasPanel == $this->hasPanel)
			return;
			
		DataBaseHelper::update('Premanager_StructureNodes', 'nodeID',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::IS_TREE,
			$this->_id, null,
			array(
				'hasPanel' => $hasPanel),
			array()
		);
			
		$this->_hasPanel = $hasPanel;
		
		$this->_editTime = new DateTime();
		$this->_editor = Environment::getCurrent()->user;
	}     

	/**
	 * Creates a new structure node and inserts it into data base
	 * 
	 * The type StructureNodeType::TREE is not allowed here because those nodes
	 * are created automatically.
	 *
	 * @param string $name group name
	 * @param string $title user title
	 * @param bool $noAccessRestriction specifies if all users can access the page
	 * @param int $type content type (StructureNodeType::SIMPLE or ~::PANEL) 
	 * @param StructureTree $tree if $type is TYPE_TREE, the structure tree
	 * @return StructureNode
	 */
	public function createChild($name, $title, $noAccessRestriction, $type) {
		$name = Strings::normalize($name);
		$title = \trim($title);
		$noAccessRestriction = !!$noAccessRestriction;
		
		if (!$name)
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');
		if (strpos('/', $name) !== false)
			throw new ArgumentException('$name must not contain slashes', 'name');
		if (!$this->isNameAvailable($name))
			throw new NameConflictException('There is already a structure node with '.
				'the same name', $name);
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
					'Premanage\Objects\StructureNodeType');
		}
	
		$id = DataBaseHelper::insert('Premanager_Nodes', 'nodeID',
			DataBaseHelepr::CREATOR_FIELDS | DataBaseHelepr::EDITOR_FIELDS, $name,
			array(
				'noAccessRestriction' => $noAccessRestriction,
				'parentID' => $parent->id,
				'hasPanel' => $type == self::TYPE_PANEL,
				'treeID' => 0),
			array(
				'title' => $title)
		);
		
		$instance = self::createFromID($id, $name, $title, $parent);
		$instance->_creator = Environment::getCurrent()->user;
		$instance->_createTime = new DateTime();
		$instance->_editor = Environment::getCurrent()->user;
		$instance->_editTime = new DateTime();

		// Now parent node contains one child more
		if ($parent->_childCount !== null)
			$parent->_childCount++;
			
		// Other children of this node's parent might have moved
		foreach (self::$_instances as $instance)
			if ($instance->_index !== null && $instance->parent == $parent)
				$instance::$_index = null;	
		
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
		DataBaseHelper::delete('Premanager_StructureNodes', 'nodeID',
			DataBaseHelper::IS_TREE,
			$this->_id);      
			    
		// Delete group permissions
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager_NodeGroup')." ".
			"WHERE nodeID = '$this->_id'");
		
		if ($this->parent && $this->parent->_childCount !== null)
			$this->parent->_childCount--;
			
		unset(self::$_instance[$this->_id]);

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
		if ($this->tree)
			return false;
		else
			foreach ($this->children as $child) {
				if (!$child->canDelete())
					return false;
			}
		return true;
	}

	/**
	 * Checks if a name is available
	 *
	 * Checks, if $name is not already assigned to a group. If $node is specified,
	 * the names of $node are excluded.
	 *
	 * @param $name name to check
	 * @param Premanager\Models\StructureNode $node a nodes whose names to be
	 *   excluded 
	 * @return bool true, if $name is available
	 */
	public function isNameAvailable($name, StructureNode $node = null) {   
		$this->checkDisposed();

		DataBaseHelper::isNameAvailable('Premanager_Nodes', 'nodeID',
			($node ? DataBaseHelper::IGNORE_THIS : 0) | DataBaseHelper::IS_TREE,
			\trim($name), $node ? $node->_id : null, $this->_id);
	}  

	/**
	 * Checks if all active names of the specified node are available in this node
	 *
	 * @param Premanager\Models\StructureNode $node node whose names to be
	 *   checked 
	 * @return bool true, if all names are available
	 */
	public function areNamesAvailalbe($name, StructureNode $node = null) {   
		$this->checkDisposed();

		$result = DataBase::query(
			"SELECT name.name ".
			"FROM ".DataBase::formTableName('Premanger_NodesName')." AS name ".
			"WHERE name.nodeID = '$this->_id' ".
				"AND name.inUse = '1'");
		while ($result->next()) {
			if (!DataBaseHelper::isNameAvailable('Premanager_Nodes', 'nodeID',
				DataBaseHelper::IS_TREE,
				\trim($result->get($name)), null, $this->_id))
					return false;
		}
		return true;
	}
	
	/**
	 * Checks if this is a child of the specified node
	 *
	 * @param Premanager\Models\StructureNode $node node to be tested 
	 * @return bool true, if this node is a child of $node
	 */
	public function isChildOf(StructureNode $node) {
		return $this->parent == $node || $this->isChildOf($node->parent);
	}

	// ===========================================================================       
	
	private function load() {
		$result = DataBase::query(
			"SELECT node.parentID, node.projectID, node.noAccessRestriction, ".
				"node.hasPanel, node.treeID, translation.name, translation.title, ". 
				"node.creatorID, node.editorID, node.createTime, node.editTime ".    
			"FROM ".DataBase::formTableName('Premanager_Nodes')." AS node ",
			/* translating */
			"WHERE node.id = '$this->_id'");
		
		if (!$result->next())
			return false;
		
		$this->_name = $result->get('name');
		$this->_title = $result->get('title');
		$this->_parentID = (int) $result->get('parentID');
		$this->_projectID = (int) $result->get('projectID');
		$this->_noAccessRestriction = (bool) $result->get('noAccessRestriction');
		$this->_hasPanel = (bool) $result->get('hasPanel');
		$this->_treeID = (int) $result->get('treeID');
		$this->_creatorID = (int) $result->get('creatorID');
		$this->_editorID = (int) $result->get('editorID');
		$this->_createTime = new DateTime($result->get('createTime'));
		$this->_editTime = new DateTime($result->get('editTime'));
		
		return true;
	}      
}

?>
