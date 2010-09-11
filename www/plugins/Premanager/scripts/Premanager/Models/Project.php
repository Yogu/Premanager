<?php                      
namespace Premanager\Models;

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
                 
/**
 * A project
 */
class Project extends Model {
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
	
	// =========================================================================== 	      
	
	/**
	 * The id of this project
	 *
	 * Ths property is read-only.
	 * 
	 * @var int
	 */
	public $id = Module::PROPERTY_GET;
	
	/**
	 * The translated url name of this project
	 *
	 * Ths property is read-only.   
	 *
	 * @see setValues()
	 * 
	 * @var string
	 */
	public $name = Module::PROPERTY_GET;         
	
	/**
	 * The translated title for this project
	 *
	 * Ths property is read-only.   
	 *
	 * @see setValues()
	 * 
	 * @var string
	 */
	public $title = Module::PROPERTY_GET;        
	
	/**
	 * An optional subtitle
	 *
	 * Ths property is read-only.   
	 *
	 * @see setValues()
	 * 
	 * @var string
	 */
	public $subTitle = Module::PROPERTY_GET;        
	
	/**
	 * The author of the contents of this project
	 *
	 * Ths property is read-only.   
	 *
	 * @see setValues()
	 * 
	 * @var string
	 */
	public $author = Module::PROPERTY_GET;         
	
	/**
	 * The copyright for the contents of this project 
	 *
	 * Ths property is read-only.   
	 *
	 * @see setValues()
	 * 
	 * @var string
	 */
	public $copyright = Module::PROPERTY_GET;             
	
	/**
	 * A short plain-text description 
	 *
	 * Ths property is read-only.   
	 *
	 * @see setValues()
	 * 
	 * @var string
	 */
	public $description = Module::PROPERTY_GET;            
	
	/**
	 * A list of keywords, separated by commas
	 *
	 * Ths property is read-only.   
	 *
	 * @see setValues()
	 * 
	 * @var string
	 */
	public $keywords = Module::PROPERTY_GET;     

	/**
	 * The StructureNode that is the root of this project
	 * 
	 * @var Premanager\Models\StructureNode
	 */
	public $rootNode = Module::PROPERTY_GET;
	
	/**
	 * The index of this project in default order
	 *
	 * Ths property is read-only.   
	 *
	 * @see setValues()
	 * 
	 * @var int
	 */
	public $index = Module::PROPERTY_GET;   

	/**
	 * The user that has created this project
	 *
	 * Ths property is read-only.  
	 * 
	 * @see setValues()
	 * 
	 * @var Premanager\Models\User
	 */
	public $creator = Module::PROPERTY_GET;    

	/**
	 * The time when this project has been created
	 *
	 * Ths property is read-only.
	 * 
	 * @var Premanager\DateTime
	 */
	public $createTime = Module::PROPERTY_GET;   

	/**
	 * The user that has edited this project the last time
	 *
	 * Ths property is read-only.
	 * 
	 * @var Premanager\Models\User
	 */
	public $editor = Module::PROPERTY_GET;      

	/**
	 * The time when this project has been edited the last time
	 *
	 * Ths property is read-only.
	 * 
	 * @var Premanager\DateTime
	 */
	public $editTime = Module::PROPERTY_GET;                 

	// ===========================================================================  
	
	private function __construct() {
		parent::__construct();	
	}
	
	private function createFromID($id, $name = null, $title = null,
		$subTitle = null, $author = null, $copyright = null, $description = null,
		$keywords = null) {
		parent::__construct();
		
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
	public function getByID($id) {
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
	 * @return Premanage\Objects\Project  
	 */
	public static function getByName($name) {
		$result = DataBase::query(
			"SELECT name.projectID ".            
			"FROM ".DataBase::formTableName('Premanager_ProjectsName')." AS name ".
			"WHERE name.name = '".DataBase::escape(Strings::unitize($name)."'"));
		if ($result->next()) {
			$project = self::createFromID($result->get('projectID'));
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
		if (Strings.indexOf($name, '/') !== false)
			throw new ArgumentException('$name must not contain slashes', 'name');
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
	
		$id = DataBaseHelper::insert('Premanager_Projects', 'projectID',
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
		$rootNodeID = DataBaseHelper::insert('Premanager_Nodes', 'nodeID',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS, '',
			array(
				'noAccessRestriction' => true,
				'parentID' => 0,
				'hasPanel' => 0,
				'treeID' => 0),
			array(
				'title' => $Strigns::get('Premanager', 'home'))
		);
		
		// Insert nodes for current trees
		foreach (TreeClass::getTreeClasses() as $treeClass) {
			$plugin = $result->get('plugin');
			$class = $result->get('class');
			
			$title = $treeClass->plugin->name . '-' . 
				\preg_replace('/[^A-Za-z0-9]+/', '-', $treeClass->className);
			$name = $rootNode->getAvailableName(Strings::toLower($title));
			
			// Now we can't use createChild because we want to create a TREE node
			$rootNodeID = DataBaseHelper::insert('Premanager_Nodes', 'nodeID',
				DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS, $name,
				array(
					'noAccessRestriction' => true,
					'parentID' => $rootNodeID,
					'hasPanel' => 0,
					'treeID' => $treeClass->id),
				array(
					'title' => $title)
			);
		}
		
		return Project::createFromID($id, $name, $title, $subTitle, $author,
			$copyright, $description, $keywords);
	}      

	/**
	 * Checks if a name is available
	 *
	 * Checks, if $name is not already assigned to a project.
	 *
	 * @param $name name to check 
	 * @return bool true, if $name is available
	 */
	public static function staticIsNameAvailable($name) {    	
		return DataBaseHelper::isNameAvailable('Premanager_Projects', 'projectID',
			(string) $name);
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
				"FROM ".DataBase::formTableName('Premanager_Projects')." AS project");
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
				'id' => DataType::NUMBER,
				'name' => DataType::STRING,
				'title' => DataType::STRING,
				'subTitle' => DataType::STRING,
				'author' => DataType::STRING,
				'copyright' => DataType::STRING,
				'description' => DataType::STRING,
				'keywords' => DataType::STRING,
				'rootNode' => StructureNode::getDescriptor(),
				'creator' => User::getDescriptor(),
				'createTime' => DataType::DATE_TIME,
				'editor' => User::getDescriptor(),
				'editTime' => DataType::DATE_TIME),
				'Premanager_Projects', array(__CLASS__, 'getByID'));
		}
		return self::$_descriptor;
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
	 * Deletes this project
	 *
	 * This object will afterwards "seem to be deleted", its methods will not 
	 * work. Make sure that there are no other instances of Project containing
	 * this deleted object, because they will not be notified.
	 */
	public function delete() {         
		$this->checkDisposed();
			
		DataBaseHelper::delete('Premanager_Projects', 'projectID', 0, $this->_id);
			    
		// Delete project-specified options
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager_ProjectOptions')." ".
			"WHERE projectID = '$this->_id'");
			
		// Delete structure
		$result = DataBase::query(
			"SELECT node.nodeID ".
			"FROM ".DataBase::formTableName('Premanager_Nodes')." AS node ".
			"WHERE node.projectID = '$this->_id'");
		while ($result->next()) {
			// Delete this node
			DataBaseHelper::delete('Premanager_StructureNodes', 'nodeID',
				DataBaseHelper::IS_TREE,
				$result->get('nodeID'));      
				    
			// Delete group permissions
			DataBase::query(
				"DELETE FROM ".DataBase::formTableName('Premanager_NodeGroup')." ".
				"WHERE nodeID = '".$result->get('nodeID')."'");
		}

		unset(self::$_instances[$this->_id]);
		if (self::$_count !== null)
			self::$_count--;	
			
		$this->_dispose();
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
			
		$name = \trim($name);
		$title = \trim($title);
		$subTitle = \trim($subTitle);
		$author = \trim($author);
		$copyright = \trim($copyright);
		$description = \trim($description);
		$keywords = \trim($keywords);
		
		if (!$name)
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');   
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
			
		DataBaseHelper::update('Premanager_Projects', 'projectID',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::UNTRANSLATED_NAME, $this->_id, $name,
			array(),
			array(
				'name' => $name,
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
		$this->_editor = Environment::getCurrent()->me;
		$this->_editorID = $this->_editor->id;
		$this->_editTime = new DateTime();
	}     

	/**
	 * Checks if a name is available
	 *
	 * Checks, if $name is not already assigned to a project. This project's
	 * names are excluded, they are available.
	 *
	 * @param $name name to check 
	 * @return bool true, if $name is available
	 */
	public function isNameAvailable($name) {   
		$this->checkDisposed();
			 	
		DataBaseHelper::isNameAvailable('Premanager_Projects', 'projectID',
			DataBaseHelper::IGNORE_THIS, (string) $name, $this->_id);
	}   

	// ===========================================================================
	
	private function load() {
		$result = DataBase::query(
			"SELECT project.projectID, project.name, translation.title, ".
				"translation.subTitle, translation.author, translation.copyright, ".
				"translation.description, translation.keywords, project.createTime, ".
				"project.creatorID, project.editTime, project.editTimes ".
			"FROM ".DataBase::formTableName('Premanager_Projects')." AS project ",
			/* translator */ 'projectID',
			"WHERE project.projectID = '$this->id'");
		
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
