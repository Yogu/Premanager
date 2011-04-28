<?php                 
namespace Premanager\Blog;

use Premanager\Execution\Environment;
use Premanager\NameConflictException;
use Premanager\IO\DataBase\DataBaseHelper;
use Premanager\Module;
use Premanager\Modeling\Model;
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
use Premanager\Modelling\QueryList;
use Premanager\Modelling\ModelDescriptor;
use Premanager\Modelling\DataType;

/**
 * A blog article
 */
final class Article extends Model {
	private $_id;
	private $_title;
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
	
	private static function createFromID($id, $title = null, $creatorID = null) {
		if ($title !== null)
			$title = \trim($title);  
		
		if (array_key_exists($id, self::$_instances)) {
			$instance = self::$_instances[$id]; 

			if ($instance->_title === null)
				$instance->_title = $title;
			if ($instance->_creatorID === null)
				$instance->_creatorID = $creatorID;
				
			return $instance;
		}

		if (!Types::isInteger($id) || $id < 0)
			throw new ArgumentException(
				'$id must be a nonnegative integer value', 'id');
				
		$instance = new self();
		$instance->_id = $id;
		$instance->_title = $title;	 
		$instance->_creatorID = $creatorID;
		self::$_instances[$id] = $instance;
		return $instance;
	} 

	// =========================================================================== 
	
	/**
	 * Gets an article using its id
	 * 
	 * @param int $id
	 * @return Premanager\Blog\Article
	 */
	public static function getByID($id) {
		$id = (int)$id;
			
		if (!Types::isInteger($id) || $id < 0)
			return null;
		else if (array_key_exists($id, self::$_instances)) {
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
	 * Gets a group using its name and the time it has been created
	 *
	 * Returns null if $name is not found in the specified month
	 *
	 * @param string $name the article's name
	 * @param int $year the creation year
	 * @param int $month the creation month
	 * @return Premanager\Blog\Article
	 */
	public static function getByName($name, $year, $month) {
		$startTime = new DateTime($year, $month, 1);
		$endTime = $startTime->addMonths(1);
			
		$result = DataBase::query(
			"SELECT name.id ".            
			"FROM ".DataBase::formTableName('Premanager.Blog', 'ArticlesName').
				" AS name ".
			"INNER JOIN ".DataBase::formTableName('Premanager.Blog', 'Articles').
				" AS article ON article.id = name.id ".
			"WHERE article.createTime >= '$startTime' ".
				"AND article.createTime < '$endTime' ".
				"AND name.name = '".DataBase::escape(Strings::unitize($name))."'");
		if ($result->next()) {
			$user = self::createFromID($result->get('id'));
			return $user;
		}
		return null;
	}
	
	/**
	 * Creates a new article and inserts it into data base
	 *
	 * @param string $title the article's title 
	 * @return Premanager\Blog\Article
	 */
	public static function createNew($title) {
		$title = trim($title);
		
		if (!$title)
			throw new ArgumentException(
				'$title is an empty string or contains only whitespaces', 'title');
			
		$name = self::getAvailableName($title);

		$id = DataBaseHelper::insert('Premanager.Blog', 'Articles',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::NAME_GROUPED_BY_MONTH, $name,
			array(),
			array(
				'title' => $title)
		);
		
		$group = self::createFromID($id, $name);
		$group->_creator = Environment::getCurrent()->getUser();
		$group->_createTime = new DateTime();
		$group->_editor = Environment::getCurrent()->getUser();
		$group->_editTime = new DateTime();

		if (self::$_count !== null)
			self::$_count++;
		
		return $group;
	}      

	/**
	 * Checks whether the name is a valid article name
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
	 * Checks if a name is not already assigned to an article in the specified
	 * month.
	 * 
	 * If no creation date is specified, the current date is used
	 * 
	 * Note: this does NOT check whether the name is valid (see isValidName())
	 *
	 * @param string $name name to check 
	 * @param Premanager\Blog\Article|null $ignoreThis an article which may have
	 *   the name; it is excluded
	 * @param Premanager\DateTime $createTime the time whose month to be viewed
	 * @return bool true, if $name is available
	 */
	public static function isNameAvailable($name, Article $ignoreThis = null,
		DateTime $createTime = null)
	{
		return DataBaseHelper::isNameAvailable('Premanager.Blog', 'Articles',
			DataBaseHelper::NAME_GROUPED_BY_MONTH, $name,
			($ignoreThis ? $ignoreThis->_id : null),
			$createTime);
	}
	
	/**
	 * Finds an available name for the specified title.
	 * 
	 * The current date is used.
	 *
	 * @param string $title title to convert into a name
	 * @param Premanager\Blog\Article|null $ignoreThis an article which may have
	 *   the name; it is excluded
	 * @return string the name
	 */
	public static function getAvailableName($title, Article $ignoreThis = null) {
		return DataBaseHelper::getAvailableName(
			array(__CLASS__, 'isNameAvailable'), Strings::simplify($title),
			$ignoreThis);
	}
	    
	/**
	 * Gets the count of articles
	 *
	 * @return int
	 */
	public static function getCount() {
		if (self::$_count === null) {
			$result = DataBase::query(
				"SELECT COUNT(article.id) AS count ".
				"FROM ".DataBase::formTableName('Premanager.Blog', 'Articles').
					" AS article");
			self::$_count = $result->get('count');
		}
		return self::$_count;
	}  

	/**
	 * Gets a list of articles
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getArticles() {
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
				'name' => array(DataType::STRING, 'getName', '*name'),
				'title' => array(DataType::STRING, 'getTitle', '*title'),
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
	 * Gets the id of this article
	 *
	 * @return int
	 */
	public function getID() {
		$this->checkDisposed();
	
		return $this->_id;
	}

	/**
	 * Gets the name used in urls
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
	 * Gets the display title
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
	 * Changes the title and generates a new name
	 * 
	 * These values will be changed in data base and in this object.
	 *
	 * @param string $title the new title 
	 */
	public function setTitle($title) {
		$this->checkDisposed();
		
		$title = trim($title);
		if (!$title)
			throw new ArgumentException('$title is an empty string', 'title');
			
		$name = self::getAvailableName($title, $this);
			
		DataBaseHelper::update('Premanager.Blog', 'Articles', 
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS,
			$this->_id, $name,
			array(),
			array(
				'title' => $title),
			$this->getCreateTime()
		);
		
		$this->_name = $name;
		$this->_title = $title;
		$this->_editTime = new DateTime();
		$this->_editor = Environment::getCurrent()->getUser();
	}   
	
	/**
	 * Deletes this article and disposes the object
	 */
	public function delete() {         
		$this->checkDisposed();
			
		DataBaseHelper::delete('Premanager.Blog', 'Articles', 0, $this->_id);

		unset(self::$_instances[$this->_id]);
		if (self::$_count !== null)
			self::$_count--;
	
		$this->dispose();
	}  

	// ===========================================================================       
	
	private function load() {
		$result = DataBase::query(
			"SELECT translation.name, translation.title, article.creatorID, ".
				"article.editorID, article.createTime, article.editTime ".
			"FROM ".DataBase::formTableName('Premanager.Blog', 'Articles').
				" AS article ",
			/* translating */
			"WHERE article.id = '$this->_id'");
		
		if (!$result->next())
			return false;
		
		$this->_name = $result->get('name');
		$this->_title = $result->get('title');
		$this->_creatorID = $result->get('creatorID');
		$this->_editorID = $result->get('editorID');
		$this->_createTime = new DateTime($result->get('createTime'));
		$this->_editTime = new DateTime($result->get('editTime'));
		
		return true;
	}
}

?>
