<?php                 
namespace Premanager\Models;

use Premanager\Execution\Environment;
use Premanager\IO\DataBase\DataBaseHelper;
use Premanager\Modeling\QueryOperation;
use Premanager\Modeling\QueryExpression;
use Premanager\Module;
use Premanager\Modeling\Model;
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
use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\QueryList;
use Premanager\Modeling\DataType;
use Premanager\IO\DataBase\DataBase;

/**
 * A structure node
 */
final class StructureNode extends Model {
	private $_title;  
	private $_parent = false;
	private $_parentID;
	private $_project = false;
	private $_projectID;
	private $_treeClass = false;
	private $_treeID;
	private $_noAccessRestriction;
	private $_url;
	private $_authorizedGroups;
	private $_children;
	
	private static $_descriptor;          

	// =========================================================================== 

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Models\StructureNodeModel
	 */
	public static function getDescriptor() {
		return StructureNodeModel::getInstance();
	}
	
	/**
	 * Gets a structure node using its id
	 * 
	 * @param int $id
	 * @return Premanager\Models\StructureNode
	 */
	public static function getByID($id) {
		return self::getDescriptor()->getByID($id);
	}

	/**
	 * Gets a list of structure nodes
	 * 
	 * @return Premanager\Modeling\QueryList
	 */
	public static function getStructureNodes() {
		return self::getDescriptor()->getQueryList();
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
	 * Gets a boulde of information about the StructureNode model
	 *
	 * @return Premanager\Models\StructureNodeModel
	 */
	public function getModelDescriptor() {
		return StructureNodeModel::getInstance();
	}

	/**
	 * Gets the name used in urls
	 *
	 * @return string
	 */
	public function getName() {
		return parent::getName();
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
				$this->_parent = StructureNode::getByID($this->_parentID, false);
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
	 * Gets the tree node class of this node
	 *
	 * @return Premanager\Models\TreeClass the tree class or null
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
		return parent::getCreator();
	}                        

	/**
	 * Gets the time when this group has been created
	 *
	 * @return Premanager\DateTime
	 */
	public function getCreateTime() {
		return parent::getCreateTime();
	}                               

	/**
	 * Gets the user that has edited this group the last time
	 *
	 * @return Premanager\Models\User
	 */
	public function getEditor() {
		return parent::getEditor();
	}                        

	/**
	 * Gets the time when this group has been edited the last time
	 *
	 * @return Premanager\DateTime
	 */
	public function getEditTime() {
		return parent::getEditTime();
	}

	/**
	 * Gets the count this structure node has been edited
	 *
	 * @return int the count of edits
	 */
	public function getEditTimes() {
		return parent::getEditTimes();
	}

	/**
	 * Creates a structure node that exists already in data base, using its name
	 *
	 * Returns null if $name is not found
	 *                                           
	 * @param string $name name of structure node
	 * @return Premanager\Models\StructureNode  
	 */
	public function getChildByName($name) {	
		return self::getDescriptor()->getByName($this, $name);
	}      
	
	/**
	 * Gets a list of child nodes
	 * 
	 * @return Premanager\Modeling\QueryList
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
		
		if ($this->getParent()) {
			$parentURL = $this->getParent()->getURL();
			if ($parentURL)
				$url = $parentURL . '/';
			$url .= htmlspecialchars($this->getName());
		} else
			$url = htmlspecialchars($this->getProject()->getName());
			
		return $url;
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
	 * @return Premanager\Modeling\QueryList
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
	 * @throws Premanager\NameConflictException $name is not available
	 */
	public function setValues($name, $title, $noAccessRestriction = null) {
		$this->checkDisposed();
			
		$name = Strings::normalize($name);
		$title = \trim($title);
		if ($noAccessRestriction === null)
			$noAccessRestriction = $this->getnoAccessRestriction();
		else
			$noAccessRestriction = !!$noAccessRestriction;
			
		if (!$this->getParent())
			$name = '';
		
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
		
		$this->update(
			array(
				'noAccessRestriction' => $noAccessRestriction,
				'hasPanel' => $hasPanel),
			array(
				'title' => $title),
			$nameChanged ? $name : null,
			$this->getParent() ? $this->getParent()->getID() : 0
		);
		
		if ($nameChanged)
			$this->_url = null;
		
		$this->_title = $title;	
		$this->_noAccessRestriction = $noAccessRestriction;
		$this->_hasPanel = $hasPanel;
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
			
		if ($parent === $this->getParent())
			return;
			
		if (!$this->getParent())
			throw new InvalidOperationException('Root node can not be moved');
		if (!$parent)
			throw new ArgumentNullException('parent');
		if ($parent === $this)
			throw new ArgumentException('A node can not become its own parent',
				'parent');
		if ($parent->isChildOf($this))
			throw new ArgumentException('$parent is a child of this', 'parent');
		if ($parent->getproject() != $this->_project)
			throw new ArgumentException('$parent\'s project must be the same '.
				'project like this node\'s project', 'parent');
		if ($parent->getTreeClass())
			throw new ArgumentException('$parent\'s type is TREE, so it '.
				'can not contain children', 'parent');
		if (!$parent->areNamesAvailable($this))
			throw new NameConflictException('Can not move this node into $parent '.
				'because some of the names of this node are not available in $parent.');
			
		// Now update parent id
		$this->update(
			array(
				'parentID' => $parent->getID()
			)
		);
		
		// Old names have to be removed because we don't know whether they are
		// available in the new parent - and old urls will be invalid, anyway
		$this->deleteUnusedNames();
		
		$this->_parentID = $parent->getID();
		$this->_parent = $parent;
		
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
		
		$this->update(
			array(
				'noAccessRestriction' => $noAccessRestriction),
			array()
		);
		
		$this->_noAccessRestriction = $noAccessRestriction;
	} 

	/**
	 * Creates a new structure node and inserts it into data base
	 *
	 * @param string $name group name
	 * @param string $title user title
	 * @return Premanager\Models\StructureNode
	 * @throws Premanager\NameConflictException $name is not available
	 */
	public function createChild($name, $title) {   
		$this->checkDisposed();
		
		return self::getDescriptor()->createNew($this, $name, $title);
	}  
	
	/**
	 * Deletes and disposes this node
	 */
	public function delete() {         
		$this->checkDisposed();
			
		// First check if there are any children linked to tree classes, because
		// they can not be deleted
		if (!$this->canDelete())
			throw new InvalidOperationException('This node can not be deleted '.
				'because itself or one of its descendants is linked to a tree class');
			
		// Delete children
		foreach ($this->getChildren() as $child) {
			$child->delete();
		}      
			    
		// Delete group permissions
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'NodeGroup')." ".
			"WHERE nodeID = '$this->_id'");

		// Delete this node
		$this->delete();
	}
	
	/**
	 * Checks if this node can be deleted.
	 * 
	 * Nodes linked to tree classes can not be deleted, as well as nodes with a
	 * child that can not be deleted 
	 * 
	 * @return bool true, if this node can be deleted
	 */
	public function canDelete() {   
		$this->checkDisposed();
		
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
		
		return self::getDescriptor()->isNameAvailable($name, $this, $ignoreThis);
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
			if (!$this->isNameAvailable($name))
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
			($this->getParent() === $node || $this->getParent()->isChildOf($node));
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
		$fields[] = 'parentID';
		$fields[] = 'projectID';
		$fields[] = 'noAccessRestriction';
		$fields[] = 'treeID';
		$fields[] = 'translation.title';
		
		if ($values = parent::load($fields)) {
			$this->_parentID = $values['parentID'];
			$this->_projectID = $values['projectID'];
			$this->_noAccessRestriction = $values['noAccessRestriction'];
			$this->_treeID = $values['treeID'];
			$this->_title = $values['title'];
		}
		
		return $values;
	}
}

?>
