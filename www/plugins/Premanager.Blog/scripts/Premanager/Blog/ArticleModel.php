<?php
namespace Premanager\Blog;

use Premanager\Strings;

use Premanager\DateTime;

use Premanager\Models\Project;

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
 * Provides a model descriptor for Article models
 */
class ArticleModel extends ModelDescriptor {
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
	}
	
	// ===========================================================================
	
	/**
	 * Gets the single instance of Premanager\Blog\ArticleModel
	 * 
	 * @return Premanager\Blog\ArticleModel the single instance of this class
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
		return 'Premanager\Blog\Article';
	}
	
	/**
	 * Gets the name of the plugin containing the models
	 * 
	 * @return string
	 */
	public function getPluginName() {
		return 'Premanager.Blog';
	}
	
	/**
	 * Gets the name of the model's table
	 * 
	 * @return string
	 */
	public function getTable() {
		return 'Articles';
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
		return "CONCAT(item.projectID, '_', YEAR(item.createTime),
			MONTH(item.createTime))";
	}
	
	/**
	 * Creates a new article and inserts it into data base
	 *
	 * @param Premanager\Models\Project $project the project to post the article in
	 * @param string $title the article's title 
	 * @return Premanager\Blog\Article
	 */
	public static function createNew(Project $project, $title) {
		$title = trim($title);
		
		if (!$title)
			throw new ArgumentException(
				'$title is an empty string or contains only whitespaces', 'title');
			
		$name = self::getAvailableName(Strings::simplify($title), $project);
		
		return $this->createNewBase(
			array(
				'projectID' => $project->getID()
			),
			array(
				'title' => $title
			),
			$name,
			$project->getID().'_'.date('Ym')
		);
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
		return $this->getByNameBase($name,
			self::getNameGroup($project, $year, $month));
	}

	/**
	 * Checks if a name is not already assigned to a group
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
		$model = $this->getByNameBase($name,
			self::getNameGroup($project, $year, $month), $inUse);
		return !$model || !$inUse || $model === $ignoreThis;
	}

	/**
	 * Gets an available name adding a number to the preferred name
	 * 
	 * Note: this does NOT check whether the name is valid
	 *
	 * @param $preferredName the name trunk 
	 * @param Premanager\Models\Project $project the project whose groups to scan
	 * @param int $year the year or null to chose the current year
	 * @param int $month the month or null to chose the current month
	 * @param Premanager\Blog\Article|null $ignoreThis an article which may have
	 *   the name; it is excluded
	 * @return bool true, if $name is available
	 */
	public function getAvailableName($preferredName, Project $project,
		$year = null, $month = null, Article $ignoreThis = null)
	{
		if ($this->isNameAvailable($preferredName, $project, $year, $month,
			$ignoreThis))
			return $preferredName;
		else {
			$index = 2;
			while (!$this->isNameAvailable($preferredName.$index, $project, $year,
				$month, $ignoreThis))
			{
				$index++;
			}
			return $preferredName.$index;		
		}
	}
	
	/**
	 * Gets the name group identifier
	 * 
	 * @param Premanager\Models\Project $project
	 * @param int $year
	 * @param int $month
	 */
	public static function getNameGroup(Project $project, $year, $month) {
		if ($year === null)
			$year = date('Y');
		if ($month === null)
			$month = date('m');
		return $project->getID().'_'.
			str_pad($year, 4, '0', STR_PAD_LEFT).
			str_pad($month, 2, '0', STR_PAD_LEFT);
	}
}

