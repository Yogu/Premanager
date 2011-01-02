<?php                 
namespace Premanager\Models;

use Premanager\Execution\Environment;
use Premanager\NameConflictException;
use Premanager\IO\DataBase\DataBaseHelper;
use Premanager\Module;
use Premanager\Model;
use Premanager\DateTime;
use Premanager\Types;
use Premanager\Strings;
use Premanager\ArgumentException;
use Premanager\ArgumentNullException;
use Premanager\InvalidOperationException;
use Premanager\IO\CorruptDataException;
use Premanager\IO\DataBase\DataBase;
use Premanager\Debug\Debug;
use Premanager\Debug\AssertionFailedException;
use Premanager\QueryList\QueryList;
use Premanager\QueryList\ModelDescriptor;
use Premanager\QueryList\DataType;

/**
 * A user group
 */
final class Group extends Model {
	private $_id;
	private $_projectID;
	private $_project;
	private $_name;
	private $_title;  
	private $_color;
	private $_text;
	private $_priority;
	private $_autoJoin;
	private $_loginConfirmationRequired;
	private $_rights;
	private $_simpleRightList;
	private $_creator;   
	private $_creatorID;
	private $_createTime;
	private $_editor;    
	private $_editorID;
	private $_editTime; 
	
	private static $_instances = array();
	private static $_count;
	private static $_descriptor;
	private static $_queryList;

	// ===========================================================================  
	
	protected function __construct() {
		parent::__construct();	
	}
	
	private static function createFromID($id, $name = null, $title = null,
		$color = null, $priority = null, $autoJoin = null, $projectID = null) {
		
		if ($name !== null)
			$name = \trim($name);
		if ($title !== null)
			$title = \trim($title);
		if ($color !== null)
			$color = \trim($color);  
		if ($autoJoin !== null)
			$autoJoin = !!$autoJoin;      
				
		if ($priority !== null && (!Types::isInteger($priority) || $priority < 0))
			throw new ArgumentException(
				'$priority must be a positive integer value or null', 'priority');  
		
		if (array_key_exists($id, self::$_instances)) {
			$instance = self::$_instances[$id]; 

			if ($instance->_name === null)
				$instance->_name = $name;
			if ($instance->_title === null)
				$instance->_title = $title;
			if ($instance->_color === null)
				$instance->_color = $color;
			if ($instance->_priority === null)             
				$instance->_priority = $priority;
			if ($instance->_autoJoin === null)
				$instance->_hasAvatar = $autoJoin;
			if ($instance->_projectID === null)
				$instance->_projectID = $projectID;
				
			return $instance;
		}

		if (!Types::isInteger($id) || $id < 0)
			throw new ArgumentException(
				'$id must be a nonnegative integer value', 'id');
				
		$instance = new self();
		$instance->_id = $id;
		$instance->_name = $name;
		$instance->_title = $title;	
		$instance->_color = $color;    
		$instance->_priority = $priority;
		$instance->_autoJoin = $autoJoin;	 
		$instance->_projectID = $projectID;
		self::$_instances[$id] = $instance;
		return $instance;
	} 

	// =========================================================================== 
	
	/**
	 * Gets a group class using its id
	 * 
	 * @param int $id
	 * @return Premanager\Models\Group
	 */
	public static function getByID($id) {
		if (!Types::isInteger($id) || $id < 0)
			throw new ArgumentException(
				'$id must be a nonnegative integer value', 'id');
			
		if (array_key_exists($id, self::$_instances)) {
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
	 * Gets a group using its name and the project it is contained by
	 *
	 * Returns null if $name is not found
	 *
	 * @param Premanager\Models\Project $project the project the group is
	 *   contained by
	 * @param string $name name of user
	 * @return Premanager\Models\Group  
	 */
	public static function getByName(Project $project, $name) {
		$result = DataBase::query(
			"SELECT name.id ".            
			"FROM ".DataBase::formTableName('Premanager', 'GroupsName')." AS name ".
			"INNER JOIN ".DataBase::formTableName('Premanager', 'Groups').
				" AS grp ON grp.id = name.id ".
			"WHERE grp.parentID = '".$project->getID()."' AND ".
				"name.name = '".DataBase::escape(Strings::unitize($name))."'");
		if ($result->next()) {
			$user = self::createFromID($result->get('id'));
			return $user;
		}
		return null;
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
		Project $project, $autoJoin = false, $loginConfirmationRequired = false) {
		$name = Strings::normalize($name);
		$title = \trim($title);
		$color = \trim($color);
		$text = \trim($text);      
		$autoJoin = !!$autoJoin;  
		$loginConfirmationRequired = !!$loginConfirmationRequired;
		
		if (!$name)
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');
		if (!self::isValidName($name))
			throw new ArgumentException('$name must not contain slashes', 'name');
		if (!self::isNameAvailable($name, $project))
			throw new NameConflictException('This name is already assigned to a '.
				'group in the same project', $name);
		if (!$title)
			throw new ArgumentException(
				'$title is an empty string or contains only whitespaces', 'title');  
		if (!$text)
			throw new ArgumentException(
				'$text is an empty string or contains only whitespaces', 'text');
		if (!$color)
			throw new ArgumentException(
				'$color is an empty string or contains only whitespaces', 'color');
		if (!self::isValidColor($color))
			throw new FormatException(
				'$color is not a valid hexadecimal RRGGBB color', 'color');    
		if ($project->getID())
			$priority = 0;  
		else if (!Types::isInteger($priority) || $priority < 0)
			throw new ArgumentException(
				'$priority must be a nonnegative integer value', 'priority');

		$id = DataBaseHelper::insert('Premanager', 'Groups',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::IS_TREE /* for project */, $name,
			array(
				'color' => $color,
				'priority' => $priority,
				'autoJoin' => $autoJoin,
				'loginConfirmationRequired' => $loginConfirmationRequired),
			array(
				'name' => $name,
				'title' => $title,
				'text' => $text),
			$project->getID()
		);
		
		$group = self::createFromID($id, $name, $title, $color, $priority,
			$autoJoin, $project->getID());
		$group->_loginConfirmationRequired = $loginConfirmationRequired;
		$group->_creator = Environment::getCurrent()->getUser();
		$group->_createTime = new DateTime();
		$group->_editor = Environment::getCurrent()->getUser();
		$group->_editTime = new DateTime();

		if (self::$_count !== null)
			self::$_count++;
		
		return $group;
	}      

	/**
	 * Checks whether the name is a valid group name
	 * 
	 * Note: this does NOT check whether the name is available
	 * (see isNameAvailable())
	 * 
	 * @param string $name the name to check
	 * @return bool true, if the name is valid
	 */
	public static function isValidName($name) {
		return $name && strpos($name, '/') === false;
	}
	
	/**
	 * Checks whether a string is a valid group color identifier
	 * 
	 * @param string $color the color string to check
	 * @return bool true, if the color string is valid
	 */
	public static function isValidColor($color) {
		return preg_match('/[0-9a-f]{6}/i', $color) == 1;
	}

	/**
	 * Checks if a name is not already assigned to a group
	 * 
	 * Note: this does NOT check whether the name is valid (see isValidName())
	 *
	 * @param $name name to check 
	 * @param Premanager\Models\Project $project the project whose groups to scan
	 * @param Premanager\Models\Group|null $ignoreThis a group which may have
	 *   the name; it is excluded
	 * @return bool true, if $name is available
	 */
	public static function isNameAvailable($name, Project $project,
		$ignoreThis = null)
	{
		return DataBaseHelper::isNameAvailable('Premanager', 'Groups',
			DataBaseHelper::IS_TREE, /* for project id */ $name,
			($ignoreThis instanceof Group ? $ignoreThis->_id : null),
			$project->getID());
	}
	    
	/**
	 * Gets the count of groups
	 *
	 * @return int
	 */
	public static function getCount() {
		if (self::$_count === null) {
			$result = DataBase::query(
				"SELECT COUNT(grp.id) AS count ".
				"FROM ".DataBase::formTableName('Premanager', 'Groups')." AS grp");
			self::$_count = $result->get('count');
		}
		return self::$_count;
	}  

	/**
	 * Gets a list of groups
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getGroups() {
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
				'project' => array(Project::getDescriptor(), 'getProject', 'parentID'),
				'name' => array(DataType::STRING, 'getName', '*name'),
				'title' => array(DataType::STRING, 'getTitle', '*title'),
				'color' => array(DataType::STRING, 'getColor', 'color'),
				'text' => array(DataType::STRING, 'getText', '*text'),
				'priority' => array(DataType::NUMBER, 'getPriority', 'priority'),
				'autoJoin' => array(DataType::BOOLEAN, 'getAutoJoin', 'autoJoin'),
				'creator' => array(User::getDescriptor(), 'getCreator', 'creatorID'),
				'createTime' => array(DataType::DATE_TIME, 'getCreateTime',
					'createTime'),
				'editor' => array(User::getDescriptor(), 'getEditor', 'editorID'),
				'editTime' => array(DataType::DATE_TIME, 'getEditTime', 'editTime')),
				'Premanager', 'Groups', array(__CLASS__, 'getByID'));
		}
		return self::$_descriptor;
	}

	// ===========================================================================
	
	/**
	 * Gets the id of this group
	 *
	 * @return int
	 */
	public function getID() {
		$this->checkDisposed();
	
		return $this->_id;
	}

	/**
	 * Gets the project that contains this group
	 *
	 * @return Premanager\Models\Project
	 */
	public function getProject() {
		$this->checkDisposed();
			
		if ($this->_project === null) {
			if ($this->_projectID == null)
				$this->load();
			$this->_project = Project::getByID($this->_projectID);
			if (!$this->_project)
				throw new CorruptDataException('The project assigned to group '.
					$this->_id.' (project id: '.$this->_projectID.') does not exist');
		}
		$this->load();
		return $this->_project;	
	}    

	/**
	 * Gets the name of this group
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
	 * Gets the title members of this group have
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
	 * Gets the color members of this group have
	 *
	 * @return string
	 */
	public function getColor() {
		$this->checkDisposed();
			
		if ($this->_color === null)
			$this->load();
		return $this->_color;	
	}          

	/**
	 * Gets a description
	 *
	 * @return string
	 */
	public function getText() {
		$this->checkDisposed();
			
		if ($this->_text === null) {
			$result = DataBase::query(
				"SELECT translation.text ".
				"FROM ".DataBase::formTableName('Premanager', 'Groups')." AS grp ",
				/* translating */
				"WHERE grp.id = '$this->_id'");
			$this->_text = $result->get('text');
		}
		return $this->_text;	
	}                

	/**
	 * Gets the priority of this group
	 *
	 * The group with the highest priority defines the title and color of a user. 
	 *
	 * @return int
	 */
	public function getPriority() {
		$this->checkDisposed();
			
		if ($this->_priority === null)
			$this->load();
		return $this->_priority;	
	}                       

	/**
	 * Gets the true, if newly registered users join automatically this group 
	 *
	 * @return bool
	 */
	public function getAutoJoin() {
		$this->checkDisposed();
			
		if ($this->_autoJoin === null)
			$this->load();
		return $this->_autoJoin;	
	}                         

	/**
	 * Checks whether users have to re-enter their password if a right of this
	 * group is needed 
	 *
	 * @return bool
	 */
	public function getLoginConfirmationRequired() {
		$this->checkDisposed();
			
		if ($this->_loginConfirmationRequired === null)
			$this->load();
		return $this->_loginConfirmationRequired;	
	}                            

	/**
	 * Gets all the rights of this group
	 *
	 * @see hasRight()
	 *
	 * @return array an array of Premanager\Models\Right objects
	 */
	public function getRights() {
		$this->checkDisposed();
			
		if ($this->_rights === null)
			$this->loadRights();
		return $this->_rights;	
	}                           

	/**
	 * Gets all the rights of this group
	 *
	 * Returns an array of plugins which contain each an array of string with
	 * the right names. Example: getRights()['Premanager']['register'] is true,
	 * if the right 'register' of the plugin 'Premanager' is set.
	 *
	 * Warning: getRights[plugin] might be null if there are no rights of this
	 * plugins. Accessing it as an array will generate a warning by php.
	 *
	 * If you change the returned array, this property will not be affected.
	 *
	 * @see hasRight()
	 *
	 * @return array
	 */
	public function getSimpleRightList() {
		$this->checkDisposed();
			
		if ($this->_simpleRightList === null)
			$this->loadRights();
		return $this->_simpleRightList;	
	}     
	                        
	/**
	 * Checks if this group has the specified right
	 *
	 * @param string $plugin the plugin that registered the right - or -
	 *   an instance of Premanager\Models\Right
	 * @param string $right the right's name, if the first argument is a string
	 * @return bool true, if this group has the specified right
	 */
	public function hasRight($plugin, $right = null) {      
		$this->checkDisposed();
			
		if ($plugin instanceof Right)
			return array_search($plugin, $this->getRights()) !== false;
		else {
			if ($this->_simpleRightList === null)
				$this->loadRights();
			return Types::isArray($this->_simpleRightList[$plugin]) &&
				$this->_simpleRightList[$plugin][$right];
		}
	}               

	/**
	 * Gets the user that has created this group
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
	 * Gets a list of members in a specified range
	 *
	 * @param int $start index of first user
	 * @param int $count count of users to return
	 * @return array
	 */
	public function getMembers($start = null, $count = null) {  
		$this->checkDisposed();
		
		//TODO: implement this with QueryList (problem: queries do not support 
		// a CONTIANS operator yet)
		
		if (($start !== null && $count === null) ||
			($count !== null && $start === null))
			throw new ArgumentException('Either both $start and $count must '.
				'be specified or none of them');
				
		if ($start !== null || $count !== null) {
			if (!Types::isInteger($start) || $start < 0)
				throw new ArgumentException(
					'$start must be a positive integer value or null');
			if (!Types::isInteger($count) || $count < 0)
				throw new ArgumentException(
					'$count must be a positive integer value or null');		
		}  
	
		$list = array();
		$result = DataBase::query(
			"SELECT user.id ".
			"FROM ".DataBase::formTableName('Premanager', 'Users')." AS user ".
			"INNER JOIN ".DataBase::formTableName('Premanager', 'UserGroup')." ".
				"AS userGroup ".
				"ON userGroup.groupID = '$this->_id' ".
				"AND userGroup.userID = user.id ".
			"ORDER BY LOWER(user.name) ASC ".
			($start !== null ? "LIMIT $start, $count" : ''));
		$list = array();
		while ($result->next()) {
			$user = User::getByID($result->get('id'));
			$list[] = $user;
		}
		return $list;
	}      
	
	/**
	 * Gets the count of members
	 *
	 * This value is not cached, because group membership can change without
	 * this object would notice
	 *
	 * @return int
	 */
	public function getMemberCount() {
		$result = DataBase::query(
			"SELECT COUNT(userGroup.userID) AS count ".
			"FROM ".DataBase::formTableName('Premanager', 'UserGroup')." AS userGroup ".
			"WHERE userGroup.groupID = '$this->_id'");
		return $result->get('count');
	}   
	      
	/**
	 * Changes various properties
	 * 
	 * This values will be changed in data base and in this object.
	 *
	 * @param string $name the name of this group
	 * @param string $title the title for group members
	 * @param string $color a hexadecimal RRGGBB color for members of this group   
	 * @param string $text a description of this group
	 * @param int $priority the priority of this group
	 * @param bool $autoJoin specifies wheater new users automatically join this
	 *   group
	 * @param bool $loginConfirmationRequired specifies whether users have to
	 *   re-enter their password if a right of this group is needed 
	 */
	public function setValues($name, $title, $color, $text, $priority = null,
		$autoJoin = null, $loginConfirmationRequired) {
		$this->checkDisposed();
			
		$name = Strings::normalize($name);
		$title = \trim($title);
		$color = \trim($color);
		$text = \trim($text);
		if ($this->getProject()->getID())
			$priority = 0;
		else if ($priority === null)
			$priority = $this->getPriority();
		if ($autoJoin === null)
			$autoJoin = $this->getAutoJoin();
		else			
			$autoJoin = !!$autoJoin;
		if ($loginConfirmationRequired === null)
			$loginConfirmationRequired = $this->getLoginConfirmationRequired();
		else			
			$loginConfirmationRequired = !!$loginConfirmationRequired;
		
		
		if (!$name)
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');
		if (!self::isValidName($name))
			throw new ArgumentException('$name must not contain slashes', 'name');
		if (!self::isNameAvailable($name, $this->getProject(), $this))
			throw new NameConflictException('This name is already assigned to a '.
				'group of the same project', $name);
		if (!$title)
			throw new ArgumentException(
				'$title is an empty string or contains only whitespaces', 'title');
		if (!$text)
			throw new ArgumentException(
				'$text is an empty string or contains only whitespaces', 'text');
		if (!$color)
			throw new ArgumentException(
				'$color is an empty string or contains only whitespaces', 'color');
		if (!self::isValidColor($color))
			throw new FormatException(
				'$color is not a valid hexadecimal RRGGBB color', 'color');
		if (!Types::isInteger($priority) || $priority < 0)
			throw new ArgumentException(
				'$priority must be a nonnegative integer value', 'priority');
			
		DataBaseHelper::update('Premanager', 'Groups', 
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS,
			$this->_id, $name,
			array(
				'color' => $color,
				'priority' => $priority,
				'autoJoin' => $autoJoin,
				'loginConfirmationRequired' => $loginConfirmationRequired),
			array(
				'name' => $name,
				'title' => $title,
				'text' => $text),
			$this->getProject()->getID()
		);
		
		$this->_name = $name;
		$this->_title = $title;	
		$this->_color = $color;   
		$this->_text = $text;     
		$this->_priority = $priority;	
		$this->_autoJoin = $autoJoin;
		$this->_loginConfirmationRequired = $loginConfirmationRequired;
		$this->_editTime = new DateTime();
		$this->_editor = Environment::getCurrent()->getUser();
		
		// User's color and title might have changed
		User::clearAllCache();
	}     
	   
	/**
	 * Changes the group's rights
	 * 
	 * This values will be changed in data base and in this object.
	 *
	 * @param array $rights an array of Premanager\Models\Right objects
	 */
	public function setRights(array $rights) {
		$this->checkDisposed();
			
		// Delete all existing rights
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'GroupRight')." ".
			"WHERE groupID = '$this->_id'");
		
		$this->_simpleRightList = array();
		foreach ($rights as $right) {
			DataBase::query(
				"INSERT INTO ".DataBase::formTableName('Premanager', 'GroupRight')." ".
				"(groupID, rightID) ".
				"VALUES ('$this->_id', '".$right->getID()."')");

			$plugin = $right->getPlugin()->getName();
			if (!Types::isArray($this->_simpleRightList[$plugin]))
				$this->_simpleRightList[$plugin] = array();
			$this->_simpleRightList[$plugin][$right->getName()] = true;
		}
	  $this->_rights = $rights;
	}  
	
	/**
	 * Deletes this group
	 *
	 * This object will afterwards "seem to be deleted", its methods will not 
	 * work.
	 */
	public function delete() {         
		$this->checkDisposed();
			
		DataBaseHelper::delete('Premanager', 'Groups', 0, $this->_id);      
			    
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'UserGroup')." ".
			"WHERE groupID = '$this->_id'");     
			    
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'NodeGroup')." ".
			"WHERE groupID = '$this->_id'");   
			
		// User's color and title might have changed
		User::clearAllCache();

		unset(self::$_instances[$this->_id]);
		if (self::$_count !== null)
			self::$_count--;
		foreach (self::$_instances as $instance)
			$instance::$_index = null;		
	
		$this->dispose();
	}  

	// ===========================================================================       
	
	private function load() {
		$result = DataBase::query(
			"SELECT translation.name, translation.title, grp.color, grp.priority, ".
				"grp.autoJoin, grp.parentID, grp.creatorID, ".
				"grp.editorID, grp.createTime, grp.editTime, ".
				"grp.loginConfirmationRequired ".
			"FROM ".DataBase::formTableName('Premanager', 'Groups')." AS grp ",
			/* translating */
			"WHERE grp.id = '$this->_id'");
		
		if (!$result->next())
			return false;
		
		$this->_name = $result->get('name');
		$this->_title = $result->get('title');
		$this->_color = $result->get('color');
		$this->_priority = $result->get('priority');
		$this->_autoJoin = !!$result->get('autoJoin');
		$this->_loginConfirmationRequired =
			!!$result->get('loginConfirmationRequired');
		$this->_projectID = $result->get('parentID');
		$this->_creatorID = $result->get('creatorID');
		$this->_editorID = $result->get('editorID');
		$this->_createTime = new DateTime($result->get('createTime'));
		$this->_editTime = new DateTime($result->get('editTime'));
		
		return true;
	}      
	
	private function loadRights() {
		$this->_rights = array();
		$this->_simpleRightList = array();
		
		$result = DataBase::query(
			"SELECT rght.id, rght.name, plugin.name AS pluginName ".
			"FROM ".DataBase::formTableName('Premanager', 'Rights')." AS rght ". 
			"INNER JOIN ".DataBase::formTableName('Premanager', 'Plugins')." ".
				"AS plugin ".
				"ON plugin.id = rght.pluginID ". 
			"INNER JOIN ".DataBase::formTableName('Premanager', 'GroupRight')." ".
				"AS groupRight ".
				"ON groupRight.groupID = '$this->_id' ".
				"AND groupRight.rightID = rght.id ".
			"GROUP BY rght.id ".
			"ORDER BY plugin.name ASC, ".
				"rght.name ASC");
		while ($result->next()) {
			$plugin = $result->get('pluginName');
			$right = $result->get('name');
			$id = $result->get('id');

			if (!Types::isArray($this->_simpleRightList[$plugin]))
				$this->_simpleRightList[$plugin] = array();
			$this->_simpleRightList[$plugin][$right] = true;
			
			$this->_rights[] = Right::getByID($id);
		}
	}
}

?>
