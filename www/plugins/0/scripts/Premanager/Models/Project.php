<?php                      
namespace Premanager\Models;

use Premanager\Execution\Environment;
use Premanager\Execution\Translation;
use Premanager\IO\DataBase\DataBaseHelper;
use Premanager\IO\DataBase\DataBase;
use Premanager\NameConflictException;
use Premanager\Module;
use Premanager\Model;
use Premanager\DateTime;
use Premanager\Strings;
use Premanager\Types;
use Premanager\ArgumentException;
use Premanager\IO\CorruptDataException;
use Premanager\Debug\Debug;
use Premanager\Debug\AssertionFailedException;
use Premanager\Models\User;
use Premanager\QueryList\ModelDescriptor;
use Premanager\QueryList\QueryList;
use Premanager\QueryList\DataType;
                 
/**
 * A project
 */
final class Project extends Model {
	private $_id;
	private $_name;
	private $_title;
	private $_subTitle;
	private $_author;
	private $_copyright;
	private $_description;
	private $_keywords;
	private $_rootNode;
	private $_creator;
	private $_creatorID;
	private $_createTime;
	private $_editor;
	private $_editorID;
	private $_editTime;
	
	private static $_organization;
	private static $_instances = array();
	private static $_count;
	private static $_descriptor;
	private static $_queryList;          
	
	const NAME_REGEXP = '/^[0-9a-z](?:[0-9a-z-]*[0-9a-z])?$/';

	// ===========================================================================  
	
	protected function __construct() {
		parent::__construct();	
	}
	
	private static function createFromID($id, $name = null, $title = null,
		$subTitle = null, $author = null, $copyright = null, $description = null,
		$keywords = null) {
		if ($name !== null)
			$name = \trim($name);
		if ($title !== null)
			$title = \trim($title);
		if ($subTitle !== null)
			$subTitle = \trim($subTitle);
		if ($author !== null)
			$author = \trim($author);    
		if ($copyright !== null)
			$copyright = \trim($copyright);
		if ($description !== null)
			$description = \trim($description);
		if ($keywords !== null)
			$keywords = \trim($keywords);        
			 
		if (\array_key_exists($id, self::$_instances)) {
			$instance = self::$_instances[$id];
			
			if ($name !== null)
				$instance->_name = $name;
			if ($title !== null)
				$instance->_title = $title;
			if ($subTitle !== null)
				$instance->_subTitle = $subTitle;     
			if ($author !== null)
				$instance->_author = $author; 
			if ($copyright !== null)
				$instance->_copyright = $copyright;   
			if ($description !== null)
				$instance->_description = $description;       
			if ($keywords !== null)
				$instance->_keywords = $keywords;
			
			return $instance;
		} 
		
		if (!Types::isInteger($id) || $id < 0)
			throw new ArgumentException(
				'$id must be a nonnegative integer value', 'id');
		
		$instance = new self();
		$instance->_id = $id;
		$instance->_name = $name;
		$instance->_title = $title;	
		$instance->_subTitle = $subTitle;    
		$instance->_author = $author;	
		$instance->_copyright = $copyright;
		$instance->_description = $description;
		$instance->_keywords = $keywords;	    
		self::$_instances[$id] = $instance;
		return $instance;
	}
	
	// ===========================================================================
	
	/**
	 * Gets a project using its id
	 *
	 * Returns null if $id is not found.
	 * 
	 * @param int $id the id of the project
	 * @return Premanager\Models\Project
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
	 * Creates a project that exists already in data base, using its name
	 *
	 * Returns null if $name is not found
	 *
	 * @param string $name name of project
	 * @return Premanager\Models\Project  
	 */
	public static function getByName($name) {
		$result = DataBase::query(
			"SELECT name.id ".            
			"FROM ".DataBase::formTableName('Premanager', 'ProjectsName')." AS name ".
			"WHERE name.name = '".DataBase::escape(Strings::unitize($name))."'");
		if ($result->next()) {
			$project = self::createFromID($result->get('id'));
			return $project;
		}
		return null;
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
	 * @return Project
	 */
	public static function createNew($name, $title, $subTitle, $author, 
		$copyright, $description, $keywords) {
		$name = Strings::normalize($name);
		$title = \trim($title);
		$subTitle = \trim($subTitle);
		$author = \trim($author);
		$copyright = \trim($copyright);
		$description = \trim($description);
		$keywords = \trim($keywords);

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
	
		$id = DataBaseHelper::insert('Premanager', 'Projects',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::UNTRANSLATED_NAME, $name,
			array(), 
			array(
				'title' => $title,
				'subTitle' => $subTitle,
				'author' => $author,    
				'copyright' => $copyright,    
				'description' => $description,    
				'keywords' => $keywords)
		);        
		
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
				TreeClassScope::ORGANIZATION));
					
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
		
		return Project::createFromID($id, $name, $title, $subTitle, $author,
			$copyright, $description, $keywords);
	}      
			 
	/**
	 * Gets a Project representing the organization
	 *
	 * @return Project
	 */
	public static function getOrganization() {
		if (self::$_organization === null)
			self::$_organization = Project::createFromID(0);
		return self::$_organization;
	}          
	    
	/**
	 * Gets the count of projects (including organization project)
	 *
	 * @return int
	 */
	public static function getCount() {
		if (self::_count === null) {
			$result = DataBase::query(
				"SELECT COUNT(project.projectID) AS count ".
				"FROM ".DataBase::formTableName('Premanager', 'Projects').
					" AS project");
			self::$_count = $result->get('count');
		}
		return self::$_count;
	}  

	/**
	 * Gets a list of projects
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getProjects() {
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
				'name' => array(DataType::STRING, 'getName', 'name'),
				'title' => array(DataType::STRING, 'getTitle', '*title'),
				'subTitle' => array(DataType::STRING, 'getSubTitle', '*subTitle'),
				'author' => array(DataType::STRING, 'getAuthor', '*author'),
				'copyright' => array(DataType::STRING, 'getCopyright', '*copyright'),
				'description' => array(DataType::STRING, 'getDescription', '*description'),
				'keywords' => array(DataType::STRING, 'getKeywords', '*keywords'),
				'rootNode' => array(StructureNode::getDescriptor(), 'getRootNode'),
				'creator' => array(User::getDescriptor(), 'getCreator', 'creatorID'),
				'createTime' => array(DataType::DATE_TIME, 'getCreateTime',
					'createTime'),
				'editor' => array(User::getDescriptor(), 'getEditor', 'editorID'),
				'editTime' => array(DataType::DATE_TIME, 'getEditTime', 'editTime')),
				'Premanager', 'Projects', array(__CLASS__, 'getByID'));
		}
		return self::$_descriptor;
	}            

	/**
	 * Checks whether the name is a valid project name
	 * 
	 * Note: this does NOT check whether the name is available
	 * (see isNameAvailable())
	 * 
	 * @param string $name the name to check
	 * @return bool true, if the name is valid
	 */
	public static function isValidName($name) {
		$name = Strings::normalize($name);
		return $name && preg_match(self::NAME_REGEXP, $name);
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
	public static function isNameAvailable($name, $ignoreThis = null) {
		return DataBaseHelper::isNameAvailable('Premanager', 'Projects', 0, $name,
			($ignoreThis instanceof Project ? $ignoreThis->_id : null));
	}

	// ===========================================================================
	
	/**
	 * Gets the id of this project
	 *
	 * @return int
	 */
	public function getID() {
		$this->checkDisposed();
	
		return $this->_id;
	}

	/**
	 * Gets the name of this project
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
	 * Gets the title of this project
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
	 * Gets the subtitle of this project
	 *
	 * @return string
	 */
	public function getSubTitle() {
		$this->checkDisposed();
			
		if ($this->_subTitle === null)
			$this->load();
		return $this->_subTitle;	
	} 

	/**
	 * Gets the author of this project
	 *
	 * @return string
	 */
	public function getAuthor() {
		$this->checkDisposed();
			
		if ($this->_author === null)
			$this->load();
		return $this->_author;	
	}        

	/**
	 * Gets the copyright of this project
	 *
	 * @return string
	 */
	public function getCopyright() {
		$this->checkDisposed();
			
		if ($this->_copyright === null)
			$this->load();
		return $this->_copyright;	
	}           

	/**
	 * Gets the description of this project
	 *
	 * @return string
	 */
	public function getDescription() {
		$this->checkDisposed();
			
		if ($this->_description === null)
			$this->load();
		return $this->_description;	
	} 

	/**
	 * Gets the keywords of this project
	 *
	 * @return string
	 */
	public function getKeywords() {
		$this->checkDisposed();
			
		if ($this->_keywords === null)
			$this->load();
		return $this->_keywords;	
	}     

	/**
	 * Gets the StructureNode that is the root of this project
	 * 
	 * @return Premanager\Models\StructureNode
	 */
	public function getRootNode() {
		if (!$this->_rootNode) {
			$result = DataBase::query(
				"SELECT node.id ".
				"FROM ".DataBase::formTableName('Premanager', 'Nodes')." AS node ".
				"WHERE node.parentID = '0' ".
					"AND node.projectID = '$this->_id'");
			if (!$result->next())
				throw new CorruptDataException('There is no root node for the project '.
					$this->getname().' (id: '.$this->_id.')');
					
			$this->_rootNode = StructureNode::getByID($result->get('id'));
		}
		return $this->_rootNode;
	}                   

	/**
	 * Gets the user that has created this project
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
	 * Gets the time when this project has been created
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
	 * Gets the user that has edited this project the last time
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
	 * Gets the time when this project has been edited the last time
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
	 * Deletes and disposes this project
	 */
	public function delete() {         
		$this->checkDisposed();
			
		DataBaseHelper::delete('Premanager', 'Projects', 0, $this->_id);
			    
		// Delete project-specified options
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'ProjectOptions')." ".
			"WHERE projectID = '$this->_id'");
			
		// Delete structure
		$result = DataBase::query(
			"SELECT node.id ".
			"FROM ".DataBase::formTableName('Premanager', 'Nodes')." AS node ".
			"WHERE node.projectID = '$this->_id'");
		while ($result->next()) {
			// Delete this node
			DataBaseHelper::delete('Premanager', 'Nodes',
				DataBaseHelper::IS_TREE,
				$result->get('id'));      
				    
			// Delete group permissions
			DataBase::query(
				"DELETE FROM ".DataBase::formTableName('Premanager', 'NodeGroup')." ".
				"WHERE nodeID = '".$result->get('id')."'");
		}
		
		// Delete groups
		$list = Group::getGroups();
		$list = $list->filter(
			$list->exprEqual(
				$list->exprMember('project'),
				$this));
		foreach ($list as $group) {
			$group->delete();
		}

		unset(self::$_instances[$this->_id]);
		if (self::$_count !== null)
			self::$_count--;	
			
		$this->dispose();
	}
	
	/**
	 * Changes various properties
	 * 
	 * This values will be changed in data base and in this object.
	 *
	 * @param string $name projects's name
	 * @param string $title project's title
	 * @param string $subTitle project's subtitle (optional)  
	 * @param string $author the main author(s) of the project
	 * @param string $copyright copyright notice
	 * @param string $description a short description in a few sentences
	 * @param string $keywords a list of keywords (optional)
	 */
	public function setValues($name, $title, $subTitle, $author, $copyright,
		$description, $keywords) {     
		$this->checkDisposed();
			
		$title = \trim($title);
		$subTitle = \trim($subTitle);
		$author = \trim($author);
		$copyright = \trim($copyright);
		$description = \trim($description);
		$keywords = \trim($keywords);
		
		if ($this->_id) {
			$name = Strings::normalize($name);
			if (!$name)
				throw new ArgumentException(
					'$name is an empty string or contains only whitespaces', 'name');
			if (!self::isValidName($name))
				throw new ArgumentException('$name is not a valid project name', 'name');
			if (!self::isNameAvailable($name, $this))
				throw new NameConflictException('This name is already in use', $name);
		} else
			$name = '';
		
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
			
		DataBaseHelper::update('Premanager', 'Projects', 
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::UNTRANSLATED_NAME, $this->_id, $name,
			array(),
			array(
				'title' => $title,
				'subTitle' => $subTitle,
				'author' => $author,
				'copyright' => $copyright,
				'description' => $description,
				'keywords' => $keywords)
		);           
		
		$this->_name = $name;
		$this->_title = $title;	
		$this->_subTitle = $subTitle;    
		$this->_author = $author;	
		$this->_copyright = $copyright;
		$this->_description = $description;
		$this->_keywords = $keywords;
		$this->_editor = Environment::getCurrent()->getuser();
		$this->_editorID = $this->_editor->getid();
		$this->_editTime = new DateTime();
	}     

	// ===========================================================================
	
	private function load() {
		$result = DataBase::query(
			"SELECT project.name, translation.title, translation.subTitle, ".
				"translation.author, translation.copyright, translation.description, ".
				"translation.keywords, project.createTime, project.creatorID, ".
				"project.editorID, project.editTime, project.editTimes ".
			"FROM ".DataBase::formTableName('Premanager', 'Projects')." AS project ",
			/* translating */
			"WHERE project.id = '$this->_id'");
		
		if (!$result->next())
			return false;
		
		$this->_name = $result->get('name');   
		$this->_title = $result->get('title');
		$this->_subTitle = $result->get('subTitle');
		$this->_author = $result->get('author');
		$this->_copyright = $result->get('copyright');
		$this->_description = $result->get('description');
		$this->_keywords = $result->get('keywords');
		$this->_createTime = new DateTime($result->get('createTime'));
		$this->_creatorID = $result->get('creatorID');
		$this->_editTime = new DateTime($result->get('editTime'));
		$this->_editorID = $result->get('editorID');
		
		return true;
	}
}

?>
