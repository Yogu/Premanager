<?php
namespace Premanager\Models;

use Premanager\Strings;

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
 * Provides a model descriptor for language models
 */
class LanguageModel extends ModelDescriptor {
	private static $_instance;
	
	// ===========================================================================
	
	/**
	 * Loads the members calling addProperty()
	 */
	protected function loadMembers() {
		parent::loadMembers();
		
		$this->addProperty('name', DataType::STRING, 'getName', 'name');
		$this->addProperty('title', DataType::STRING, 'getTitle', 'title');
		$this->addProperty('englishTitle', DataType::STRING, 'getEnglishTitle',
			'englishTitle');
		
		$this->addProperty('shortDateTimeFormat', DataType::STRING,
			'getShortDateTimeFormat');
		$this->addProperty('shortDateFormat', DataType::STRING, 'getShortDateFormat',
			'shortDateFormat');
		$this->addProperty('shortTimeFormat', DataType::STRING, 'getShortTimeFormat',
			'shortTimeFormat');
		$this->addProperty('longDateTimeFormat', DataType::STRING,
			'getLongDateTimeFormat');
		$this->addProperty('longDateFormat', DataType::STRING, 'getLongDateFormat',
			'longDateFormat');
		$this->addProperty('longTimeFormat', DataType::STRING, 'getLongTimeFormat',
			'longTimeFormat');
		$this->addProperty('dateTimePhraseFormat', DataType::STRING,
			'getDateTimePhraseFormat', 'dateTimePhraseFormat');
	}
	
	// ===========================================================================
	
	/**
	 * Gets the single instance of Premanager\Models\ProjectModel
	 * 
	 * @return Premanager\Models\ProjectModel the single instance of this class
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
		return 'Premanager\Models\Language';
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
		return 'Languages';
	}
	
	/**
	 * Gets flags set for this model descriptor 
	 * 
	 * @return int (enum set Premanager\Modeling\ModelFlags)
	 */
	public function getFlags() {
		return ModelFlags::CREATOR_FIELDS | ModelFlags::EDITOR_FIELDS |
		  ModelFlags::NO_NAME;
	}
	
	/**
	 * Gets an SQL expression that determines the name group for an item (alias
	 * for item table is 'item')
	 * 
	 * @return string an SQL expression
	 */
	public function getNameGroupSQL() {
		return '';
	}
	
	/**
	 * Creates a new language and inserts it into data base.
	 * 
	 * Throws Premanager\NameConflictException if there is already a language with
	 * this name
	 *
	 * @param string $name 2-letter language code (e.g. 'en' or 'fr'), may be
	 *   followed by '-xx', where xx is a country code (e.g. 'en-us' or 'de-at')
	 * @param string $title the localized language name
	 * @param string $englishTitle the language name in English
	 * @return Premanage\Objects\Language
	 */
	public static function createNew($name, $title, $englishTitle) {
		$name = Strings::toLower(Strings::normalize($name));
		$title = \trim($title);
		$englishTitle = \trim($englishTitle);   
                                                                    
		if (!$name)   
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');         
		if (Strings::indexOf($name, '/') !== false)
			throw new ArgumentException('$name must not contain slashes', 'name');                 
		if (!self::isNameAvailable($name))
			throw new NameConflictException('There is already a language with this '.
				'name', $name);
		if (!$title)
			throw new ArgumentException(
				'$title is an empty string or contains only whitespaces', 'title');
		if (!$englishTitle)
			throw new ArgumentException('$englishTitle is an empty string or '.
				'contains only whitespaces', 'englishTitle'); 
		
		return $this->createNewBase(
			array(
				'name' => $name,
				'title' => $title,
				'englishTitle' => $englishTitle
			),
			array()
		);
	}
                               
	/**
	 * Gets a project using its name
	 *
	 * Returns null if $name is not found
	 *
	 * @param string $name name of the language
	 * @return Premanager\Models\Language
	 */
	public function getByName($name) {
		$result = DataBase::query(
			"SELECT id ".
			"FROM ".DataBase::formTableName('Premanager', 'Languages')." ".
			"WHERE LOWER(name) = '".Strings::unitize($name)."'");
		if ($result->next())
			return $this->getByID($result->get('id'));
	}

	/**
	 * Checks if a name is not already assigned to a language
	 * 
	 * Note: this does NOT check whether the name is valid (see isValidName())
	 *
	 * @param $name name to check 
	 * @param Premanager\Models\Language|null $ignoreThis a language which may
	 *  have the name; it is excluded
	 * @return bool true, if $name is available
	 */
	public function isNameAvailable($name, Language $ignoreThis = null) {
		$model = $this->getByName($name);
		return !$model || $model === $ignoreThis;
	}
}

