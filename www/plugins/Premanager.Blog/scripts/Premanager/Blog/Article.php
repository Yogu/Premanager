<?php                 
namespace Premanager\Blog;

use Premanager\Models\Project;

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
use Premanager\Modeling\QueryList;
use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\DataType;

/**
 * A blog article
 */
final class Article extends Model {
	private $_title;
	private $_projectID;
	private $_project;
	
	private static $_descriptor;

	// =========================================================================== 

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Blog\ArticleModel
	 */
	public static function getDescriptor() {
		return ArticleModel::getInstance();
	}
	
	/**
	 * Gets an article using its id
	 * 
	 * @param int $id
	 * @return Premanager\Blog\Article
	 */
	public static function getByID($id) {
		return self::getDescriptor()->getByID($id);
	}
                               
	/**
	 * Gets a group using its name and the project it is contained by
	 *
	 * Returns null if $name is not found
	 *
	 * @param Premanager\Models\Project $project the project the article is
	 *   posted in
	 * @param int $year the year the article is posted in
	 * @param int $month the month the article is posted in
	 * @param string $name name of the article
	 * @return Premanager\Blog\Article
	 */
	public function getByName($name, Project $project, $year = null,
		$month = null)
	{
		return self::getDescriptor()->getByName($name, $project, $year, $month);
	}
	
	/**
	 * Creates a new article and inserts it into data base
	 *
	 * @param Premanager\Models\Project $project the project to post the article in
	 * @param string $title the article's title 
	 * @return Premanager\Blog\Article
	 */
	public static function createNew(Project $project, $title) {
		return self::getDescriptor()->createNew($project, $title);
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
	 * Checks if a name is not already assigned to an article
	 * 
	 * Note: this does NOT check whether the name is valid (see isValidName())
	 *
	 * @param $name name to check 
	 * @param Premanager\Models\Project $project the project whose groups to scan
	 * @param int $year the year or null to chose the current year
	 * @param int $month the month or null to chose the current month
	 * @param Premanager\Blog\Article|null $ignoreThis an article which may have
	 *   the name; it is excluded
	 * @return bool true, if $name is available
	 */
	public function isNameAvailable($name, Project $project, $year = null,
		$month = null, Article $ignoreThis = null)
	{
		return self::getDescriptor()->isNameAvailable($name, $project, $year,
			$month, $ignoreThis);
	}
	    
	/**
	 * Gets the count of articles
	 *
	 * @return int
	 */
	public static function getCount() {
		$result = DataBase::query(
			"SELECT COUNT(article.id) AS count ".
			"FROM ".DataBase::formTableName('Premanager.Blog', 'Articles').
				" AS article");
		return $result->get('count');
	}  

	/**
	 * Gets a list of articles
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getArticles() {
		return self::getDescriptor()->getQueryList();
	}  

	// ===========================================================================

	/**
	 * Gets a boulde of information about the Article model
	 *
	 * @return Premanager\Blog\ArticleModel
	 */
	public function getModelDescriptor() {
		return ArticleModel::getInstance();
	}

	/**
	 * Gets the name used in urls
	 *
	 * @return string
	 */
	public function getName() {
		return parent::getName();
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
	 * Gets the project this article is posted in
	 * 
	 * @return Premanager\Models\Project
	 */
	public function getProject() {
		$this->checkDisposed();
		
		if ($this->_project === null) {
			if ($this->_projectID === null)
				$this->load();
			$this->_project = Project::getByID($this->_projectID);
		}
		return $this->_project;
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
	 * Gets the count of edits
	 *
	 * @return int the count of edits
	 */
	public function getEditTimes() {
		return parent::getEditTimes();
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
			
		$name = self::getDescriptor()->getAvailableName(Strings::simplify($title),
			$this->getProject(), null, null, $this);
			
		$this->update(
			array(),
			array(
				'title' => $title
			),
			$name,
			$this->getNameGroup()
		);
		
		$this->_title = $title;
	}   
	
	/**
	 * Deletes this article and disposes the object
	 */
	public function delete() {   
		parent::delete();
	}  
	
	/**
	 * Gets the name group of this article
	 * 
	 * @return string the name group identifier
	 */
	public function getNameGroup() {
		return self::getDescriptor()->getNameGroup($this->getProject(),
			$this->getCreateTime()->getYear(), $this->getCreateTime()->getMonth());	
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
		$fields[] = 'projectID';
		
		if ($values = parent::load($fields)) {
			$this->_title = $values['title'];
			$this->_projectID = $values['projectID'];
		}
		
		return $values;
	}
}

?>
