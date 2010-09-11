<?php                 
namespace Premanager\Models;

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
	private $_name;
	private $_title;  
	private $_color;
	private $_text;
	private $_priority;
	private $_autoJoin;
	private $_isLocked;
	private $_rights;
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

	/**
	 * The id of this group
	 *
	 * Ths property is read-only.
	 * 
	 * @var int
	 */
	public $id = Module::PROPERTY_GET;      

	/**
	 * The name of this group
	 *
	 * Ths property is read-only.
	 * 
	 * @see setValues()
	 * 
	 * @var string
	 */
	public $name = Module::PROPERTY_GET;    

	/**
	 * A user title
	 *
	 * Ths property is read-only.     
	 * 
	 * @see setValues()
	 * 
	 * @var string
	 */
	public $title = Module::PROPERTY_GET; 

	/**
	 * A hexadecimal RRGGBB color
	 *
	 * Ths property is read-only.  
	 * 
	 * @see setValues()
	 * 
	 * @var string
	 */
	public $color = Module::PROPERTY_GET; 

	/**
	 * A description
	 *
	 * Ths property is read-only.  
	 * 
	 * @see setValues()
	 * 
	 * @var string
	 */
	public $text = Module::PROPERTY_GET; 

	/**
	 * The priority of this group.
	 *
	 * The group with the highest priority defines the title and color of a user. 
	 *
	 * Ths property is read-only.  
	 * 
	 * @see setValues()
	 * 
	 * @var int
	 */
	public $priority = Module::PROPERTY_GET;    

	/**
	 * Specifies whether new users automatically join this group
	 *
	 * Ths property is read-only. 
	 * 
	 * @see setValues()
	 * 
	 * @var bool
	 */
	public $autoJoin = Module::PROPERTY_GET;    

	/**
	 * Specifies whether this group can only be managed by users with the
	 * 'lockGroups' right  
	 * 
	 * @var bool
	 */
	public $isLocked = Module::PROPERTY_GET_SET;    

	/**
	 * Provides all the rights members of this group have as an array
	 *
	 * Gets or sets an array of plugins which contain each an array of string with
	 * the right names. Example: getRights()['Premanager']['register'] is true,
	 * if the right 'register' of the plugin 'Premanager' is set.
	 *
	 * Warning: $rights[plugin] might be null if there are no rights of this
	 * plugins. Accessing it as an array will generate a warning by php. 
	 *
	 * If you change the returned array, this property will not be affected.
	 * Instead, set it explicitly ($rights = array(...)).
	 *
	 * @see hasRight()
	 * 
	 * @var array
	 */
	public $rights = Module::PROPERTY_GET_SET; 

	/**
	 * Provides the user that has created this group
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
	 * Provides the user that has edited this group the last time
	 *
	 * Ths property is read-only.
	 * 
	 * @var Premanager\Models\User
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
	
	private static function createFromID($id, $name = null, $title = null,
		$color = null, $priority = null, $autoJoin = null, $isLocked = null) {
		
		if ($name !== null)
			$name = \trim($name);
		if ($title !== null)
			$title = \trim($title);
		if ($color !== null)
			$color = \trim($color);  
		if ($autoJoin !== null)
			$autoJoin = !!$autoJoin;   
		if ($isLocked !== null)
			$isLocked = !!$isLocked;      
				
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
			if ($instance->_isLocked === null)
				$instance->_isLocked = $isLocked;
				
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
		$instance->_isLocked = $isLocked;
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
		$id = (int) $id;
		
		if ($id < 0)
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
	 * Gets a group using its name
	 *
	 * Returns null if $name is not found
	 *
	 * @param string $name name of user
	 * @return Premanager\Models\Group  
	 */
	public static function getByName($name) {
		$result = DataBase::query(
			"SELECT name.id ".            
			"FROM ".DataBase::formTableName('Premanager_GroupsName')." AS name ".
			"WHERE name.name = '".DataBase::escape(Strings::unitize($name)."'"));
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
	 * @param bool $autoJoin
	 * @param bool $isLocked
	 * @return Premanager\Models\Group
	 */
	public static function createNew($name, $title, $color, $text, $priority,
		$autoJoin = false, $isLocked = false) {
		$name = Strings::normalize($name);
		$title = \trim($title);
		$color = \trim($color);
		$text = \trim($text);      
		$autoJoin = !!$autoJoin;  
		$isLocked = !!$isLocked;
		
		if (!$name)
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');
		if (Strings::indexOf($name, '/') !== false)
			throw new ArgumentException('$name must not contain slashes', 'name');
		if (!$title)
			throw new ArgumentException(
				'$title is an empty string or contains only whitespaces', 'title');  
		if (!$text)
			throw new ArgumentException(
				'$text is an empty string or contains only whitespaces', 'text');
		if (!$color)
			throw new ArgumentException(
				'$color is an empty string or contains only whitespaces', 'color');
		if (\preg_match('/[0-9a-f]{6}/i', $color))
			throw new ArgumentException(
				'$color is not a valid hexadecimal RRGGBB color', 'color');      
		if (!Types::isInteger($priority) || $priority < 0)
			throw new ArgumentException(
				'$priority must be a positive integer value', 'priority');

		$id = DataBaseHelper::insert('Premanager_Groups',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS, $name,
			array(
				'color' => $color,
				'priority' => $priority,
				'autoJoin' => $autoJoin,
				'isLocked' => $isLocked),
			array(
				'name' => $name,
				'title' => $title,
				'text' => $text)
		);
		
		$group = self::createFromID($id, $name, $title, $color, $priority,
			$autoJoin, $isLocked);
		$group->_creator = Premanager::$me;
		$group->_createTime = new DateTime();
		$group->_editor = Premanager::$me;
		$group->_editTime = new DateTime();

		if (self::$_count !== null)
			self::$_count++;
		foreach (self::$_instances as $instance)
			$instance::$_index = null;	
		
		return $group;
	}

	/**
	 * Checks if a name is available
	 *
	 * Checks, if $name is not already assigned to a group.
	 *
	 * @param $name name to check 
	 * @return bool true, if $name is available
	 */
	public static function staticIsNameAvailable($name) {    	
		return DataBaseHelper::isNameAvailable('Premanager_Group', (string) $name);
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
				"FROM ".DataBase::formTableName('Premanager_Groups')." AS grp");
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
				'id' => DataType::NUMBER,
				'name' => DataType::STRING,
				'title' => DataType::STRING,
				'color' => DataType::STRING,
				'text' => DataType::STRING,
				'priority' => DataType::NUMBER,
				'autoJoin' => DataType::BOOLEAN,
				'isLocked' => DataType::BOOLEAN,
				'creator' => User::getDescriptor(),
				'createTime' => DataType::DATE_TIME,
				'editor' => User::getDescriptor(),
				'editTime' => DataType::DATE_TIME),
				'Premanager_Groups', array(__CLASS__, 'getByID'));
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
				"SELECT grp.text ".
				"FROM ".DataBase::formTableName('Premanager_Groups')." AS grp ".
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
	 * Gets the true, if this group can only be edited by users with the
	 * 'lockGrous' right
	 *
	 * @return bool
	 */
	public function getIsLocked() {
		$this->checkDisposed();
			
		if ($this->_isLocked === null)
			$this->load();
		return $this->_isLocked;	
	}                          

	/**
	 * Gets all the rights members of this group have
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
	public function getRights() {
		$this->checkDisposed();
			
		if ($this->_rights === null)
			$this->loadRights();
		return $this->_rights;	
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
	 * Gets the index of this group in data base using default order
	 *
	 * @return int
	 */
	public function getIndex() {            
		$this->checkDisposed();
			
		if ($this->_index === null) {
			$result = DataBase::query(
				"SELECT COUNT(grp.id) AS count ".
				"FROM ".DataBase::formTableName('Premanager_Groups')." AS grp ".
				/* translating */
				"WHERE LOWER(translation.name) < '".
					DataBase::escape(Strings::unitize($this->name))."' ".
				"ORDER BY LOWER(translation.name) ASC");
			$this->_index = $result->get('count');
		}
		return $this->_index;
	}    
	
	/**
	 * Gets a list of members in a specified range
	 *
	 * @param int $start index of first user
	 * @param int $count count of users to return
	 * @return array
	 */
	public function getMembers($start = null, $count = null) {
		$start = $start ? $start : 0;
		$count = $count ? $count : 0;
		
		if (($start !== null && $count == null) ||
			($count !== null && $start === null))
			throw new ArgumentException('Either both $start and $count must '.
				'be specified or none of them');
				
		if ($start === null || $count === null) {
			if (!is_int($start) || $start < 0)
				throw new ArgumentException(
					'$start must be a positive integer value or null', 'start');
			if (!is_int($count) || $count < 0)
				throw new ArgumentException(
					'$count must be a positive integer value or null', 'count');
		}  
	
		$list = array();
		$result = DataBase::query(
			"SELECT user.userID ".
			"FROM ".DataBase::formTableName('Premanager_Users')." AS user ".
			"ORDER BY LOWER(user.name) ASC ".
			($start !== null ? "LIMIT $start, $count" : ''));
		$list = '';
		while ($result->next()) {
			$user = User::getByID($result->get('userID'));
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
			"FROM ".DataBase::formTableName('Premanager_UsersGroup')." AS userGroup ".
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
	 * @param string $isLocked specifies wheater only users with the 'lockGroups'
	 *   right can edit this group
	 */
	public function setValues($name, $title, $color, $text, $priority = null,
		$autoJoin = null, $isLocked = null) {
		$this->checkDisposed();
			
		$name = Strings::normalize($name);
		$title = \trim($title);
		$color = \trim($color);
		$text = \trim($text);
		if ($priority === null)
			$priority = $this->priority;  
		if ($autoJoin === null)
			$autoJoin = $this->autoJoin;
		else			
			$autoJoin = !!$autoJoin;
		if ($isLocked === null)
			$isLocked = $this->isLocked;
		else
			$isLocked = !!$isLocked;
		
		if (!$name)
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');
		if (strpos('/', $name) !== false)
			throw new ArgumentException('$name must not contain slashes', 'name');
		if (!$title)
			throw new ArgumentException(
				'$title is an empty string or contains only whitespaces', 'title');
		if (!$text)
			throw new ArgumentException(
				'$text is an empty string or contains only whitespaces', 'text');
		if (!$color)
			throw new ArgumentException(
				'$color is an empty string or contains only whitespaces', 'color');
		if (!preg_match('/[0-9a-f]{6}/i', $color))
			throw new FormatException(
				'$color is not a valid hexadecimal RRGGBB color');   
		if (!Types::isInteger($priority) || $priority < 0)
			throw new ArgumentException(
				'$priority must be a positive integer value', 'priority');
			
		DataBaseHelper::update('Premanager_Groups', 
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS,
			$this->_id, $name,
			array(
				'color' => $color,
				'priority' => $priority,
				'autoJoin' => $autoJoin,
				'isLocked' => $isLocked),
			array(
				'name' => $name,
				'title' => $title,
				'text' => $text)
		);           
		
		$this->_name = $name;
		$this->_title = $title;	
		$this->_color = $color;   
		$this->_text = $text;     
		$this->_priority = $priority;	
		$this->_autoJoin = $autoJoin;
		$this->_isLocked = $isLocked;  
		
		$this->_editTime = new DateTime();
		$this->_editor = Premanager::$me;
	}     
	
	/**
	 * Sets wheater this group is locked or not
	 * 
	 * This value will be changed in data base and in this object.
	 *
	 * @param string $isLocked specifies wheater only users with the 'lockGroups'
	 *   right can edit this group
	 */
	public function setIsLocked($isLocked) {
		$this->checkDisposed();
			
		$isLocked = !!$isLocked;
			
		DataBaseHelper::update('Premanager_Groups',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS,
			$this->_id, null,
			array(
				'isLocked' => $isLocked),
			array()
		);           
		
		$this->_isLocked = $isLocked;
		
		$this->_editTime = new DateTime();
		$this->_editor = Premanager::$me;
	}   
	   
	/**
	 * Changes the group's rights
	 * 
	 * This values will be changed in data base and in this object.
	 *
	 * Expects an array of plugins which contain each an array of string with
	 * the right names. Example: $rights['Premanager']['register'] must be true,
	 * if the right 'register' of the plugin 'Premanager' should be set.
	 *
	 * @param array $rights an array with plugin names as keys and arrays as
	 *   values that contain the right names
	 */
	public function setRights(array $rights) {
		$this->checkDisposed();
			
		// Delete all existing rights
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager_GroupRight')." ".
			"WHERE groupID = '$this->_id'");
	    
	  // Insert selected rights
	  $result = DataBase::query(
	  	"SELECT plugin.name AS pluginName, rght.rightID, rght.name ".
	  	"FROM ".DataBase::formTableName('Premanager_Rights')." AS rght ".
	  	"INNER JOIN ".DataBase::formTableName('Premanager_Plugins')." AS plugin ".
	  		"ON rght.pluginID = plugin.pluginID");
	  while ($result->next()) {
	  	$id = $result->get('rightID');
	  	$plugin = $result->get('pluginName');
	  	$right = $result->get('name');
	  	if (is_array($rights[$plugin]) && $rights[$plugin][$right]) {
				DataBase::query(
					"INSERT INTO ".DataBase::formTableName('Premanager_GroupRight')." ".
					"(groupID, rightID) ".
					"VALUES ('$this->_id', '$id')");	  	
	  	}
	  }
	  
	  // We can not be sure that the array was correct; it could contain rights
	  // that don't exist.
	  $this->_rights = null;
	}  
	
	/**
	 * Deletes this group
	 *
	 * This object will afterwards "seem to be deleted", its methods will not 
	 * work.
	 */
	public function delete() {         
		$this->checkDisposed();
			
		DataBaseHelper::delete('Premanager_Groups', 0, $this->_id);      
			    
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager_UserGroup')." ".
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

	/**
	 * Checks if a name is available
	 *
	 * Checks, if $name is not already assigned to a group. This group's name
	 * names are excluded, they are available.
	 *
	 * @param $name name to check 
	 * @return bool true, if $name is available
	 */
	public function isNameAvailable($name) {   
		$this->checkDisposed();
			 	
		DataBaseHelper::isNameAvailable('Premanager_Groups',
			DataBaseHelper::IGNORE_THIS, (string) $name, $this->_id);
	}  

	// ===========================================================================       
	
	private function load() {
		$result = DataBase::query(
			"SELECT translation.name, translation.title, grp.color, grp.priority, ".
				"grp.autoJoin, grp.isLocked, grp.creatorID, grp.editorID, ".
				"grp.createTime, grp.editTime ".
			"FROM ".DataBase::formTableName('Premanager_Groups')." AS grp ",
			/* translating */
			"WHERE grp.id = '$this->_id'");
		
		if (!$result->next())
			return false;
		
		$this->_name = $result->get('name');
		$this->_title = $result->get('title');
		$this->_color = $result->get('color');
		$this->_priority = $result->get('priority');
		$this->_autoJoin = !!$result->get('autoJoin');
		$this->_isLocked = !!$result->get('isLocked');
		$this->_creatorID = $result->get('creatorID');
		$this->_editorID = $result->get('editorID');
		$this->_createTime = new DateTime($result->get('createTime'));
		$this->_editTime = new DateTime($result->get('editTime'));
		
		return true;
	}      
	
	private function loadRights() {
		$this->_rights = array();
		
		$result = DataBase::query(
			"SELECT rght.name, plugin.name AS pluginName ".
			"FROM ".DataBase::formTableName('Premanager_Rights')." AS rght ". 
			"INNER JOIN ".DataBase::formTableName('Premanager_Plugins')." AS plugin ".
				"ON plugin.pluginID = rght.pluginID ". 
			"INNER JOIN ".DataBase::formTableName('Premanager_GroupRight')." AS groupRight ".
				"ON groupRight.groupI = '$this->_id' ".
			"GROUP BY rght.rightID ".
			"ORDER BY plugin.name ASC, ".
				"rght.name ASC");
		while ($result->next()) {
			$plugin = $result->get('pluginName');
			$right = $result->get('name');

			if (!Types::isArray($this->_rights[$plugin]))
				$this->_rights[$plugin] = array();

			$this->_rights[$plugin][$right] = true;
		}
	}
}

?>
