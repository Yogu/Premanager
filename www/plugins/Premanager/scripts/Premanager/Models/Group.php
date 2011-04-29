<?php                 
namespace Premanager\Models;

use Premanager\Modeling\Model;
use Premanager\Execution\Environment;
use Premanager\NameConflictException;
use Premanager\IO\DataBase\DataBaseHelper;
use Premanager\Module;
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
use Premanager\Modeling\QueryList;
use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\DataType;

/**
 * A user group
 */
final class Group extends Model {
	private $_projectID;
	private $_project;
	private $_title;  
	private $_color;
	private $_text;
	private $_priority;
	private $_autoJoin;
	private $_loginConfirmationRequired;
	private $_rights;
	private $_simpleRightList;
	private $_members;
	
	/**
	 * @var Premanager\Models\GroupModel
	 */
	private static $_descriptor;
	
	// ===========================================================================

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Models\GroupModel
	 */
	public static function getDescriptor() {
		return GroupModel::getInstance();
	}
	
	/**
	 * Gets a group class using its id
	 * 
	 * @param int $id
	 * @return Premanager\Models\Group
	 */
	public static function getByID($id) {
		return self::getDescriptor()->getByID($id);
	}
                               
	/**
	 * Gets a group using its name and the project it is contained by
	 *
	 * Returns null if $name is not found
	 *
	 * @param Premanager\Models\Project $project the project the group is
	 *   contained by
	 * @param string $name name of the group
	 * @return Premanager\Models\Group  
	 */
	public static function getByName(Project $project, $name) {
		return self::getDescriptor()->getByName($project, $name);
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
		Group $ignoreThis = null)
	{
		return $this->getDescriptor()->isNameAvailable($name, $project, $ignoreThis);
	}
	    
	/**
	 * Gets the count of groups
	 *
	 * @return int
	 */
	public static function getCount() {
		$result = DataBase::query(
			"SELECT COUNT(grp.id) AS count ".
			"FROM ".DataBase::formTableName('Premanager', 'Groups')." AS grp");
		return $result->get('count');
	}

	/**
	 * Gets a list of groups
	 * 
	 * @return Premanager\Modeling\QueryList
	 */
	public static function getGroups() {
		return self::getDescriptor()->getQueryList();
	}     

	// ==========================================================================
	
	/**
	 * Gets the Group model descriptor
	 * 
	 * @return Premanager\Models\GroupModel the Group model descriptor
	 */
	public function getModelDescriptor() {
		return self::getDescriptor();
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
		return parent::getName();	
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
	 * Gets the list of group members
	 * 
	 * @return Premanager\Modeling\QueryList
	 */
	public function getMembers() {
		$this->checkDisposed();
		
		if ($this->_members === null)
			$this->_members = User::getUsers()->joinFilter('Premanager', 'UserGroup',
				"item.id = [join].userID AND [join].groupID = $this->_id");
		return $this->_members;
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
			
		$this->update(
			array(
				'color' => $color,
				'priority' => $priority,
				'autoJoin' => $autoJoin,
				'loginConfirmationRequired' => $loginConfirmationRequired),
			array(
				'title' => $title,
				'text' => $text),
			$name,
			$this->getProject()->getID()
		);
		
		$this->_title = $title;	
		$this->_color = $color;   
		$this->_text = $text;     
		$this->_priority = $priority;	
		$this->_autoJoin = $autoJoin;
		$this->_loginConfirmationRequired = $loginConfirmationRequired;
		
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
			    
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'UserGroup')." ".
			"WHERE groupID = '$this->_id'");     
			    
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'NodeGroup')." ".
			"WHERE groupID = '$this->_id'");   
			
		// User's color and title might have changed
		User::clearAllCache();
	
		parent::delete();
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
		$fields[] = 'color';
		$fields[] = 'priority';
		$fields[] = 'autoJoin';
		$fields[] = 'projectID';
		$fields[] = 'loginConfirmationRequired';
		
		if ($values = parent::load($fields)) {
			$this->_title = $values['title'];
			$this->_color = $values['color'];
			$this->_priority = $values['priority'];
			$this->_autoJoin = !!$values['autoJoin'];
			$this->_loginConfirmationRequired = !!$values['loginConfirmationRequired'];
			$this->_projectID = $values['projectID'];
		}
		
		return $values;
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
