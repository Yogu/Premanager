<?php
namespace Premanager\Models;

use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\DataType;
use Premanager\IO\DataBase\QueryBuilder;
use Premanager\NotSupportedException;
use Premanager\IO\DataBase\DataBase;
use Premanager\Pages\AddGroupHomePage;
use Premanager\Models\User;
use Premanager\Modeling\ModelFlags;
use Premanager\InvalidOperationException;
use Premanager\ArgumentException;
use Premanager\Module;
use Premanager\Strings;

/**
 * Provides a model descriptor for structure node models
 */
class StructureNodeModel extends ModelDescriptor {
	private static $_instance;
	
	// ===========================================================================
	
	/**
	 * Loads the members calling addProperty()
	 */
	protected function loadMembers() {
		parent::loadMembers();
		
		$this->addProperty('title', DataType::STRING, 'getTitle', '*title');
		$this->addProperty('parent', $this, 'getParent', 'parentID');
		$this->addProperty('tree', TreeClass::getDescriptor(), 'getTree', 'treeID');
	}
	
	// ===========================================================================
	
	/**
	 * Gets the single instance of Premanager\Models\StructureNodeModel
	 * 
	 * @return Premanager\Models\StructureNodeModel the single instance of this class
	 */
	public static function getInstance() {
		if (self::$_instance === null)
			self::$_instance = new self();
		return self::$_instance;
	}
	
	/**
	 * Gets the name of the class this descriptor describes
	 * 
	 * @return string
	 */
	public function getClassName() {
		return 'Premanager\Models\StructureNode';
	}
	
	/**
	 * Gets the name of the plugin containing the models
	 * 
	 * @return string
	 */
	public function getPluginName() {
		return 'Premanager';
	}
	
	/**
	 * Gets the name of the model's table
	 * 
	 * @return string
	 */
	public function getTable() {
		return 'Nodes';
	}
	
	/**
	 * Gets flags set for this model descriptor 
	 * 
	 * @return int (enum set Premanager\Modeling\ModelFlags)
	 */
	public function getFlags() {
		return ModelFlags::CREATOR_FIELDS | ModelFlags::EDITOR_FIELDS |
		  ModelFlags::HAS_TRANSLATION | ModelFlags::TRANSLATED_NAME;
	}
	
	/**
	 * Gets an SQL expression that determines the name group for an item (alias
	 * for item table is 'item')
	 * 
	 * @return string an SQL expression
	 */
	public function getNameGroupSQL() {
		return 'item.parentID';
	}
	
	/**
	 * Creates a new structure node and inserts it into data base
	 *
	 * @param string $name group name
	 * @param string $title user title
	 * @return Premanager\Models\StructureNode
	 * @throws Premanager\NameConflictException $name is not available
	 */
	public function createNew(StructureNode $parent, $name, $title) {
		$name = Strings::normalize($name);
		$title = trim($title);
		
		if ($parent->getTreeClass())
			throw new ArgumentException('Structure nodes linked to tree classes '.
				'may not have structure nodes as child nodes');
		if (!$name)
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');
		if (!StructureNode::isValidName($name))
			throw new ArgumentException('$name must not contain slashes and must '.
				'not begin with a plus sign', 'name');
		if (!$this->isNameAvailable($name, $parent))
			throw new NameConflictException('There is already a child node with the '.
				'same name', $name);
		if (!$title)
			throw new ArgumentException(
				'$title is an empty string or contains only whitespaces', 'title');
			
		return $this->createNewBase(
			array(
				'noAccessRestriction' => true,
				'parentID' => $parent->getID(),
				'projectID' => $parent->getProject()->getID(),
				'treeID' => 0),
			array(
				'title' => $title),
			$name,
			$parent->getID()
		);
	}    
                               
	/**
	 * Gets a structure node using its name
	 *
	 * Returns null if $name is not found
	 *
	 * @param Premanager\StructureNode $parent the parent structure node
	 * @param string $name name of the project
	 * @return Premanager\Models\StructureNode
	 */
	public function getByName(StructureNode $parent, $name) {
		return $this->getByNameBase($name, $parent->getID());
	}

	/**
	 * Checks if a name is not already assigned to a structure node with the 
	 * specified parent
	 * 
	 * Note: this does NOT check whether the name is valid (see isValidName())
	 *
	 * @param $name name to check
	 * @param Premanager\Models\StructureNode $parent the parent node 
	 * @param Premanager\Models\StructureNode|null $ignoreThis a project which may
	 *  have the name; it is excluded
	 * @return bool true, if $name is available
	 */
	public function isNameAvailable($name, StructureNode $parent,
		StructureNode $ignoreThis = null)
	{
		$model = $this->getByNameBase($name, $parent->getID(), $inUse);
		return !$model || !$inUse || $model === $ignoreThis;
	}
}

