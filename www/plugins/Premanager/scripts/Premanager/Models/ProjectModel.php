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

/**
 * Provides a model descriptor for project models
 */
class ProjectModel extends ModelDescriptor {
	private static $_instance;
	
	// ===========================================================================
	
	/**
	 * Loads the members calling addProperty()
	 */
	protected function loadMembers() {
		parent::loadMembers();
	
		$this->addProperty('title', DataType::STRING, 'getTitle', '*title');
		$this->addProperty('subTitle', DataType::STRING, 'getSubTitle',
			'*subTitle');
		$this->addProperty('author', DataType::STRING, 'getAuthor', '*author');
		$this->addProperty('copyright', DataType::STRING, 'getCopyright',
			'*copyright');
		$this->addProperty('description', DataType::STRING, 'getDescription',
			'*description');
		$this->addProperty('keywords', DataType::STRING, 'getKeywords',
			'*keywords');
		$this->addProperty('rootNode', StructureNode::getDescriptor(),
			'getRootNode');
	}
	
	// ===========================================================================
	
	/**
	 * Gets the single instance of Premanager\Models\ProjectModel
	 * 
	 * @return Premanager\Models\ProjectModel the single instance of this class
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
		return 'Premanager\Models\Project';
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
		return 'Projects';
	}
	
	/**
	 * Gets flags set for this model descriptor 
	 * 
	 * @return int (enum set Premanager\Modeling\ModelFlags)
	 */
	public function getFlags() {
		return ModelFlags::CREATOR_FIELDS | ModelFlags::EDITOR_FIELDS |
		  ModelFlags::HAS_TRANSLATION | ModelFlags::UNTRANSLATED_NAME;
	}
	
	/**
	 * Gets an SQL expression that determines the name group for an item (alias
	 * for item table is 'item')
	 * 
	 * @return string an SQL expression
	 */
	public function getNameGroupSQL() {
		return '';
	}
	
	/**
	 * Creates a new project and inserts it into data base
	 *
	 * @param string $name projects's name
	 * @param string $title project's title
	 * @param string $subTitle project's subtitle (optional)  
	 * @param string $author the main author(s) of the project
	 * @param string $copyright copyright notice
	 * @param string $description a short description in a few sentences
	 * @param string $keywords a list of keywords (optional)
	 * @return Premanage\Models\Project
	 */
	public static function createNew($name, $title, $subTitle, $author, 
		$copyright, $description, $keywords)
	{
		$name = Strings::normalize($name);
		$title = trim($title);
		$subTitle = trim($subTitle);
		$author = trim($author);
		$copyright = trim($copyright);
		$description = trim($description);
		$keywords = trim($keywords);

		if (!$name)
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');
		if (!self::isValidName($name))
			throw new ArgumentException('$name is not a valid project name', 'name');
		if (!self::isNameAvailable($name))
			throw new NameConflictException('This name is already in use', $name);
		if (!$title)
			throw new ArgumentException(
				'$title is an empty string or contains only whitespaces', 'title');
		if (!$author)
			throw new ArgumentException(
				'$author is an empty string or contains only whitespaces', 'author');  
		if (!$copyright)
			throw new ArgumentException('$copyright is an empty string or contains '.
				'only whitespaces', 'copyright');  
		if (!$description)
			throw new ArgumentException('$description is an empty string or '.
				'contains only whitespaces', 'description');
			
		$project = $this->createNewBase(
			array(),
			array(
				'title' => $title,
				'subTitle' => $subTitle,
				'author' => $author,    
				'copyright' => $copyright,    
				'description' => $description,    
				'keywords' => $keywords),
			$name
		);
		$id = $project->getID();
		
		// Insert root node (we can't use StructureNode because that class does not
		// provide a createNew method (it does not because one can use createChild
		// instead for common node creation)
		$rootNodeID = DataBaseHelper::insert('Premanager', 'Nodes',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS, '',
			array(
				'noAccessRestriction' => true,
				'parentID' => 0,
				'hasPanel' => 0,
				'projectID' => $id,
				'treeID' => 0),
			array(
				'title' => Translation::defaultGet('Premanager', 'home'))
		);
		$rootNode = StructureNode::getByID($rootNodeID);
		
		// Insert nodes for trees with the scope 'projects' or 'both'
		$list = TreeClass::getTreeClasses();
		$list = $list->filter(
			$list->exprUnEqual(
				$list->exprMember('scope'),
				Scope::ORGANIZATION));
					
		foreach ($list as $treeClass) {
			$nodeTitle = $treeClass->getPlugin()->getName() . '-' . 
				preg_replace('/[^A-Za-z0-9]+/', '-', $treeClass->getClassName());
			$nodeName = DataBaseHelper::getAvailableName(
				array($rootNode, 'isNameAvailable'), Strings::toLower($nodeTitle));
			
			// Now we can't use createChild because we want to create a TREE node
			DataBaseHelper::insert('Premanager', 'Nodes',
				DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS,
				$nodeName,
				array(
					'noAccessRestriction' => true,
					'parentID' => $rootNodeID,
					'projectID' => $id,
					'hasPanel' => 0,
					'treeID' => $treeClass->getID()),
				array(
					'title' => $nodeTitle)
			);
		}
		
		return $project;
	}    
                               
	/**
	 * Gets a project using its name
	 *
	 * Returns null if $name is not found
	 *
	 * @param string $name name of the project
	 * @return Premanager\Models\Project
	 */
	public function getByName($name) {
		return $this->getByNameBase($name);
	}

	/**
	 * Checks if a name is not already assigned to a project
	 * 
	 * Note: this does NOT check whether the name is valid (see isValidName())
	 *
	 * @param $name name to check 
	 * @param Premanager\Models\Project|null $ignoreThis a project which may have
	 *   the name; it is excluded
	 * @return bool true, if $name is available
	 */
	public function isNameAvailable($name, Project $ignoreThis = null)
	{
		$model = $this->getByNameBase($name, '', $inUse);
		return !$model || !$inUse || $model == $ignoreThis;
	}
}

