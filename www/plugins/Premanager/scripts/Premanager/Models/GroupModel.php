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

class GroupModel extends ModelDescriptor {
	private static $_instance;
	
	// ===========================================================================
	
	/**
	 * Loads the members calling addProperty()
	 */
	protected function loadMembers() {
		parent::loadMembers();
	
		$this->addProperty('project', Project::getDescriptor(), 'getProject',
			'projectID');
		$this->addProperty('title', DataType::STRING, 'getTitle', '*title');
		$this->addProperty('color', DataType::STRING, 'getColor', 'color');
		$this->addProperty('text', DataType::STRING, 'getText', '*text');
		$this->addProperty('priority', DataType::NUMBER, 'getPriority', 'priority');
		$this->addProperty('autoJoin', DataType::BOOLEAN, 'getAutoJoin', 'autoJoin');
	}
	
	// ===========================================================================
	
	/**
	 * Gets the single instance of Premanager\Models\GroupModel
	 * 
	 * @return Premanager\Models\GroupModel the single instance of this class
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
		return 'Premanager\Models\Group';
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
		return 'Groups';
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
		return 'item.projectID';
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
	public function createNew($name, $title, $color, $text, $priority,
		Project $project, $autoJoin = false, $loginConfirmationRequired = false) {
		$name = Strings::normalize($name);
		$title = trim($title);
		$color = trim($color);
		$text = trim($text);      
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
			
		return $this->createNewBase(
			array(
				'color' => $color,
				'priority' => $priority,
				'autoJoin' => $autoJoin,
				'loginConfirmationRequired' => $loginConfirmationRequired),
			array(
				'name' => $name,
				'title' => $title,
				'text' => $text),
			$name,
			$project->getID()
		);
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
	public function getByName(Project $project, $name) {
		return $this->getByNameBase($name, $project->getID());
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
	public function isNameAvailable($name, Project $project,
		Group $ignoreThis = null)
	{
		$model = $this->getByNameBase($name, $project->getID(), $inUse);
		return !$model || !$inUse || $model == $ignoreThis;
	}
}

