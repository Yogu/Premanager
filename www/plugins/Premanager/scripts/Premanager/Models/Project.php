<?php                      
namespace Premanager\Models;

use Premanager\Execution\Environment;
use Premanager\Execution\Translation;
use Premanager\IO\DataBase\DataBaseHelper;
use Premanager\IO\DataBase\DataBase;
use Premanager\NameConflictException;
use Premanager\Module;
use Premanager\Modeling\Model;
use Premanager\DateTime;
use Premanager\Strings;
use Premanager\Types;
use Premanager\ArgumentException;
use Premanager\IO\CorruptDataException;
use Premanager\Debug\Debug;
use Premanager\Debug\AssertionFailedException;
use Premanager\Models\User;
use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\QueryList;
use Premanager\Modeling\DataType;
                 
/**
 * A project
 */
final class Project extends Model {
	private $_title;
	private $_subTitle;
	private $_author;
	private $_copyright;
	private $_description;
	private $_keywords;
	private $_rootNode;
	
	/**
	 * @var Premanager\Models\Project
	 */
	private static $_organization;
	/**
	 * @var Premanage\Models\ProjectModel
	 */
	private static $_descriptor;     
	
	const NAME_REGEXP = '/^[0-9a-z](?:[0-9a-z-]*[0-9a-z])?$/';
	
	// ===========================================================================

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Models\ProjectModel
	 */
	public static function getDescriptor() {
		return ProjectModel::getInstance();
	}
	
	/**
	 * Gets a project using its id
	 * 
	 * @param int $id
	 * @return Premanager\Models\Project
	 */
	public static function getByID($id) {
		return self::getDescriptor()->getByID($id);
	}
                               
	/**
	 * Gets a project using its name
	 *
	 * Returns null if $name is not found
	 *
	 * @param string $name name of the project
	 * @return Premanager\Models\Project
	 */
	public static function getByName($name) {
		return self::getDescriptor()->getByName($name);
	}
	
	/**
	 * Creates a new group and inserts it into data base
	 *
	 * @param string $name group name
	 * @param string $title user title
	 * @param string $color hexadecimal RRGGBB 
	 * @param string $text description
	 * @param int $priority the priority
	 * @param Premanager\Models\Project $project the project that contains the
	 *   group
	 * @param bool $autoJoin specifies wheater new users automatically join this
	 *   group
	 * @param bool $loginConfirmationRequired specifies whether users have to
	 *   re-enter their password if a right of this group is needed 
	 * @return Premanager\Models\Group
	 */
	public static function createNew($name, $title, $color, $text, $priority,
		Project $project, $autoJoin = false, $loginConfirmationRequired = false)
	{
		return self::getDescriptor()->createNew($name, $title, $color, $text,
			$priority, $project, $autoJoin, $loginConfirmationRequired);
	}   
	
	/**
	 * Gets a Project representing the organization
	 *
	 * @return Project
	 */
	public static function getOrganization() {
		if (self::$_organization === null)
			self::$_organization = self::getByID(0);
		return self::$_organization;
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
	public static function isNameAvailable($name, Project $ignoreThis = null)
	{
		return $this->getDescriptor()->isNameAvailable($name, $ignoreThis);
	}
	    
	/**
	 * Gets the count of project
	 *
	 * @return int
	 */
	public static function getCount() {
		$result = DataBase::query(
			"SELECT COUNT(project.id) AS count ".
			"FROM ".DataBase::formTableName('Premanager', 'Projects')." AS project");
		return $result->get('count');
	}

	/**
	 * Gets a list of projects
	 * 
	 * @return Premanager\Modeling\QueryList
	 */
	public static function getProjects() {
		return self::getDescriptor()->getQueryList();
	}     

	// ===========================================================================
	
	/**
	 * Gets the Project model descriptor
	 * 
	 * @return Premanager\Models\ProjectModel the Project model descriptor
	 */
	public function getModelDescriptor() {
		return self::getDescriptor();
	}  

	/**
	 * Gets the name of this group
	 *
	 * @return string
	 */
	public function getName() {
		return parent::getName();	
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
	 * Gets the user that has created this group
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
	 * Gets the count of times this group has been edited
	 * 
	 * @return Premanager\DateTime the count of edit times
	 */
	protected function getEditTimes() {
		return parent::getEditTimes();
	}
	
	/**
	 * Deletes and disposes this project
	 */
	public function delete() {         
		$this->checkDisposed();
			    
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
			
		parent::delete();
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
			
		$this->update(
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
		
		$this->_name = $name;
		$this->_title = $title;	
		$this->_subTitle = $subTitle;    
		$this->_author = $author;	
		$this->_copyright = $copyright;
		$this->_description = $description;
		$this->_keywords = $keywords;
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
		$fields[] = 'translation.title';
		$fields[] = 'translation.subTitle';
		$fields[] = 'translation.author';
		$fields[] = 'translation.copyright';
		$fields[] = 'translation.description';
		$fields[] = 'translation.keywords';
		
		if ($values = parent::load($fields)) {
			$this->_title = $values['title'];
			$this->_subTitle = $values['subTitle'];
			$this->_author = $values['author'];
			$this->_copyright = $values['copyright'];
			$this->_description = $values['description'];
			$this->_keywords = $values['keywords'];
		}
		
		return $values;
	}
}

?>
