<?php           
namespace Premanager\Models;

use Premanager\Module;
use Premanager\Model;
use Premanager\DateTime;
use Premanager\Strings;
use Premanager\Types;
use Premanager\ArgumentException;
use Premanager\ArgumentNullException;
use Premanager\InvalidOperationException;
use Premanager\NameConflictException;
use Premanager\Debug\Debug;
use Premanager\Debug\AssertionFailedException;
use Premanager\IO\CorruptDataException;
use Premanager\IO\DataBase\DataBase;
use Premanager\Modeling\QueryList;
use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\DataType;
           
/**
 * A language
 */
final class Language extends Model {
	private $_id;
	private $_name;
	private $_title;
	private $_englishTitle;
	private $_shortDateFormat;
	private $_shortTimeFormat;
	private $_longDateFormat;
	private $_longTimeFormat;
	private $_dateTimePhraseFormat;
	private $_creatorID;
	private $_createTime;
	private $_editor;
	private $_editorID;
	private $_editTime;
	
	private static $_default;
	private static $_internationalLanguage;
	private static $_instances = array();
	private static $_count;   
	private static $_descriptor;   
	private static $_queryList;
	
	// ===========================================================================
	
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Creates a language that exists already in data base
	 * 
	 * @param int $id
	 * @param string|null $name
	 * @param string|null $title
	 * @param string|null $englishTitle
	 */
	private static function createFromID($id, $name = null, $title = null,
		$englishTitle = null) {
		
		if ($name !== null)
			$name = \trim($name);
		if ($title !== null)
			$title = \trim($title);
		if ($englishTitle !== null)
			$englishTitle = \trim($englishTitle);    
		
		if (\array_key_exists($id, self::$_instances)) {
			$instance = self::$_instances[$id]; 
			if ($instance->_name === null)
				$instance->_name = $name;         
			if ($instance->_title === null)
				$instance->_title = $title;       
			if ($instance->_englishTitle === null)
				$instance->_englishTitle = $englishTitle;

			return $instance;
		}

		if (!Types::isInteger($id) || $id < 0)
			throw new ArgumentException(
				'$id must be a nonnegative integer value', 'id');
		
		$instance = new self();
		$instance->_id = $id;
		$instance->_name = $name;
		$instance->_title = $title;
		$instance->_englishTitle = $englishTitle;    
		self::$_instances[$id] = $instance;
		if (self::$_count !== null)
			self::$_count++;
		return $instance;
	}    
	
	// ===========================================================================  
	
	/**
	 * Gets a language using its id
	 *
	 * Returns null if $id is not found
	 * 
	 * @param int $id the id of the language
	 * @return Premanager\Models\Language
	 */
	public static function getByID($id) {
		$id = (int)$id;
			
		if (!Types::isInteger($id) || $id < 0)
			return null;
		else if (\array_key_exists($id, self::$_instances)) {
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
	 * Gets a language using its name
	 *
	 * Returns null if $name is not found
	 *
	 * @param string $name language name
	 * @return Premanager\Models\Language  
	 */
	public static function getByName($name) {
		$result = DataBase::query(
			"SELECT language.id, language.name, language.title, ".
				"language.englishTitle ".            
			"FROM ".DataBase::formTableName('Premanager', 'Languages')." AS language ".
			"WHERE language.name = '".DataBase::escape(Strings::unitize($name))."'");
		if ($result->next()) {
			$language = Language::createFromID($result->get('id',
				$result->get('name'), $result->get('title'),
				$result->get('englishTitle')));
			return $language;
		}
		return null;
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
	
		DataBase::query(
			"INSERT INTO ".DataBase::formTableName('Premanager', 'Languages')." ".
			"(name, title, englishTitle) ".
			"VALUES ('".DataBase::escape($name)."', '".DataBase::escape($title)."', ".
				"'".DataBase::escape($englishTitle)."')");
		
		return Language::createFromID($id, $name, $title, $englishTitle);
	}           
	    
	/**
	 * Gets the count of languages
	 *
	 * @return int
	 */
	public static function getCount() {
		if (self::$_count === null) {
			$result = DataBase::query(
				"SELECT COUNT(language.id) AS count ".
				"FROM ".DataBase::formTableName('Premanager', 'Languages')." AS language");
			self::$_count = $result->get('count');
		}
		return self::$_count;
	}  
	
	/**
	 * Gets a list of languages
	 * 
	 * @return Premanager\Modeling\QueryList
	 */
	public static function getLanguages() {
		if (!self::$_queryList)
			self::$_queryList = new QueryList(self::getDescriptor());
		return self::$_queryList;
	}     

	/**
	 * Checks if a name is available
	 *
	 * Checks, if $name is not already assigned to a language.
	 *
	 * @param $name language name to check 
	 * @return bool true, if $name is available
	 */
	public static function staticIsNameAvailable($name) { 
		$result = DataBase::query(
			"SELECT language.id ".
			"FROM ".DataBase::formTableName('Premanager', 'Languages')." AS language ".
			"WHERE language.name = '".DataBase::escape(Strings::unitize($name)."'"));
		return !$result->next();
	}
	
	/**
	 * Gets the language that is specified as "default"
	 *
	 * @return Premanage\Objects\Language
	 */
	public static function getDefault() {
		if (!self::$_default) {
			$result = DataBase::query(
				"SELECT language.id, language.name, language.title, ".
					"language.englishTitle ".            
				"FROM ".DataBase::formTableName('Premanager', 'Languages')." AS language ".
				"WHERE language.isDefault = '1'");
			if ($result->next()) {
				self::$_default = Language::createFromID($result->get('id',
					$result->get('name'), $result->get('title'),
					$result->get('englishTitle')));
			} else
				throw new CorruptDataException('No default language specified');
		}
		return self::$_default;	
	}    
	
	/**
	 * Sets the default langauge
	 * 
	 * @param Premanager\Models\Language $language the new default language
	 */
	public static function setDefault(Language $language) {
		if (!$language)
			throw new ArgumentNullException('language');
			
		if ($language == self::$_default)
			return;
		
		// Remove former default language
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Language')." AS language ".
			"SET language.isDefault = '0' ".
			"WHERE language.isDefault = '1'");
		
		// Set the new default language
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Languages')." AS language ".
			"SET language.isDefault = '1' ".
			"WHERE language.id = '$langauge->getid()'");
		
		self::$_default = $language;
	}
	
	/**
	 * Gets the language that is specified as "international"
	 *
	 * @return Premanager\Models\Language
	 */
	public static function getInternationalLanguage() {
		if (!self::$_internationalLanguage) {
			$result = DataBase::query(
				"SELECT language.id, language.name, language.title, ".
					"language.englishTitle ".            
				"FROM ".DataBase::formTableName('Premanager', 'Languages')." AS language ".
				"WHERE language.isInternational = '1'");
			if ($result->next()) {
				self::$_internationalLanguage =
					Language::createFromID($result->get('id',
						$result->get('name'), $result->get('title'),
						$result->get('englishTitle')));
			} else
				throw new CorruptDataException('No international language specified');
		}
		return self::$_internationalLanguage;	
	}
	
	/**
	 * Sets the international langauge
	 * 
	 * @param Premanager\Models\Language $language the new international language
	 */
	public static function setInternationalLanguage(Language $language) {
		if (!$language)
			throw new ArgumentNullException('language');
			
		if ($language == self::$_internationalLanguage)
			return;
		
		// Remove former international language
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Language')." AS language ".
			"SET language.isInternational = '0' ".
			"WHERE language.isInternational = '1'");
		
		// Set the new intenrational language
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Languages')." AS language ".
			"SET language.isInternational = '1' ".
			"WHERE language.id = '$langauge->getid()'");
		
		self::$_internationalLanguage = $language;
	}

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Modeling\ModelDescriptor
	 */
	public static function getDescriptor() {
		if (self::$_descriptor === null) {
			self::$_descriptor = new ModelDescriptor(__CLASS__, array(
				'id' => array(DataType::NUMBER, 'getID', 'id'),
				'name' => array(DataType::STRING, 'getName', 'name'),
				'title' => array(DataType::STRING, 'getTitle', 'title'),
				'englishTitle' => array(DataType::STRING, 'getEnglishTitle',
					'englishTitle'),
				'shortDateTimeFormat' => array(DataType::STRING,
					'getShortDateTimeFormat'),
				'shortDateFormat' => array(DataType::STRING, 'getShortDateFormat',
					'shortDateFormat'),
				'shortTimeFormat' => array(DataType::STRING, 'getShortTimeFormat',
					'shortTimeFormat'),
				'longDateTimeFormat' => array(DataType::STRING,
					'getLongDateTimeFormat'),
				'longDateFormat' => array(DataType::STRING, 'getLongDateFormat',
					'longDateFormat'),
				'longTimeFormat' => array(DataType::STRING, 'getLongTimeFormat',
					'longTimeFormat'),
				'dateTimePhraseFormat' => array(DataType::STRING,
					'getDateTimePhraseFormat', 'dateTimePhraseFormat'),
				'creator' => array(User::getDescriptor(), 'getCreator', 'creatorID'),
				'createTime' => array(DataType::DATE_TIME, 'getCreateTime',
					'createTime'),
				'editor' => array(User::getDescriptor(), 'getEditor', 'editorID'),
				'editTime' => array(DataType::DATE_TIME, 'getEditTime', 'editTime')),
				'Premanager', 'Languages', array(__CLASS__, 'getByID'));
		}
		return self::$_descriptor;
	}

	// ===========================================================================
	
	/**
	 * Gets the id of this language
	 *
	 * @return int
	 */
	public function getID() {
		$this->checkDisposed();
	
		return $this->_id;
	}

	/**
	 * Gets the language code of this language
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
	 * Gets the translated name of this language
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
	 * Gets the english name of this language
	 *
	 * @return string
	 */
	public function getEnglishTitle() {
		$this->checkDisposed();
			
		if ($this->_englishTitle === null)
			$this->load();
		return $this->_englishTitle;	
	}   

	/**
	 * Gets the short date time format string
	 *
	 * @return string
	 */
	public function getShortDateTimeFormat() {
		$this->checkDisposed();
			
		if ($this->_shortDateFormat === null || $this->_shortTimeFormat === null)
			$this->load();
		return $this->_shortDateFormat . ' ' . $this->_shortTimeFormat;	
	}  

	/**
	 * Gets the short date format string
	 *
	 * @return string
	 */
	public function getShortDateFormat() {
		$this->checkDisposed();
			
		if ($this->_shortDateFormat === null)
			$this->load();
		return $this->_shortDateFormat;	
	}    

	/**
	 * Gets the short time format string
	 *
	 * @return string
	 */
	public function getShortTimeFormat() {
		$this->checkDisposed();
			
		if ($this->_shortTimeFormat === null)
			$this->loadDateTimeFormats();
		return $this->_shortTimeFormat;	
	}   

	/**
	 * Gets the long date time format string
	 *
	 * @return string
	 */
	public function getLongDateTimeFormat() {
		$this->checkDisposed();
			
		if ($this->_longDateFormat === null || $this->_longTimeFormat === null)
			$this->load();
		return $this->_longDateFormat . ' ' . $this->_longTimeFormat;	
	}  

	/**
	 * Gets the long date format string
	 *
	 * @return string
	 */
	public function getLongDateFormat() {
		$this->checkDisposed();
			
		if ($this->_longDateFormat === null)
			$this->load();
		return $this->_longDateFormat;	
	}    

	/**
	 * Gets the long time format string
	 *
	 * @return string
	 */
	public function getLongTimeFormat() {
		$this->checkDisposed();
			
		if ($this->_longTimeFormat === null)
			$this->load();
		return $this->_longTimeFormat;	
	}       

	/**
	 * Gets the date/time phrase format string
	 *
	 * @return string
	 */
	public function getDateTimePhraseFormat() {
		$this->checkDisposed();
			
		if ($this->_dateTimePhraseFormat === null)
			$this->load();
		return $this->_dateTimePhraseFormat;	
	}              

	/**
	 * Gets the user that has created this language
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
	 * Gets the time when this language has been created
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
	 * Gets the user that has edited this language the last time
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
	 * Gets the time when this language has been edited the last time
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
	 * Deletes and disposes this language
	 * 
	 * If this is the default or international language, any other language will
	 * replace that position.
	 * 
	 * Throws Premanager\InvalidOperationException if this is the last language
	 *
	 * The strings DataBase::formTableName will not be modified.
	 */
	public function delete() {
		$this->checkDisposed();
		
		// Check if this is the last language
		if (self::getCount() == 1)
			throw new InvalidOperationException('Last language can not be deleted');
			
		// If this was the default langauge, select another default language
		if (self::getDefault() == $this) {
			$arr = self::getLanguages(0, 1);
			self::setDefault($arr[0]);
		}
			
		// If this was international langauge, select another international language
		if (self::getInternationalLanguage() == $this) {
			$arr = self::getLanguages(0, 1);
			self::setInternationalLanguage($arr[0]);
		}

		DataBaseHelper::delete('Premanager', 'Languages', 0, $this->_id);
			
		unset(self::$_instances[$this->_id]);
		self::$_count = 0;
		$this->dispose();
	}
	
	/**
	 * Changes various properties
	 * 
	 * This values will be changed in data base and in this object.
	 * 
	 * Throws Premanager\NameConflictException if there is already a language with
	 * this name.
	 *
	 * @param string $name language code
	 * @param string $title translated languae name
	 * @param string $englishTitle english language name
	 */
	public function setValues($name, $title, $englishTitle) {
		$this->checkDisposed();
			  
		$name = \trim($name);
		$title = \trim($title);
		$englishTitle = \trim($englishTitle);
		
		if (!$name)
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');   
		if (!$this->isNameAvailable($name))
			throw new NameConflictException('Thre is already a language with this '.
				'name', $name);
		if (!$title)
			throw new ArgumentException(
				'$title is an empty string or contains only whitespaces', 'title');   
		if (!$englishTitle)
			throw new ArgumentException('$englishTitle is an empty string or '.
				'contains only whitespaces', 'englishTitle');  
		
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Languages')." ".
			"SET name = '".DataBase::escape($name)."', ".
				"title = '".DataBase::escape($title)."', ".
				"englishTitle = '".DataBase::escape($englishTitle)."' ".
			"WHERE id = '$this->_id'");
		
		$this->_name = $name;
		$this->_title = $title;	
		$this->_englishTitle = $englishTitle;
		
		$this->_editor = Environment::getCurrent()->getuser();
		$this->_editorID = $this->_editor->getid();
		$this->_editTime = new DateTime();
	}           
	
	/**
	 * Changes date / time format strings
	 * 
	 * This values will be changed in data base and in this object.
	 *
	 * @param string $shortDateeFormat a datetime format string
	 * @param string $shortTimeFormat a datetime format string    
	 * @param string $longDateeFormat a datetime format string
	 * @param string $longTimeFormat a datetime format string
	 */
	public function setFormatStrings($shortDateFormat, $shortTimeFormat,
		$longDateFormat, $longTimeFormat) {
		$this->checkDisposed(); 
			  
		$shortDateFormat = \trim($shortDateFormat);
		$shortTimeFormat = \trim($shortTimeFormat);    
		$longDateFormat = \trim($longDateFormat);
		$longTimeFormat = \trim($longTimeFormat);  
		                       
		if (!$shortDateFormat)
			throw new ArgumentException('$shortDateFormat is an empty string or '.
				'contains only whitespaces', 'shortDateFormat');            
		if (!$shortTimeFormat)
			throw new ArgumentException('$shortTimeFormat is an empty string or '.
				'contains only whitespaces', 'shortTimeFormat');         
		if (!$longDateFormat)
			throw new ArgumentException('$longDateFormat is an empty string or '.
				'contains only whitespaces', 'longDateFormat');            
		if (!$longTimeFormat)
			throw new ArgumentException('$longTimeFormat is an empty string or '.
				'contains only whitespaces', 'longTimeFormat');         
			
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Languages')." ".
			"SET shortDateFormat = '".DataBase::escape($shortDateFormat)."', ".
				"shortTimeFormat = '".DataBase::escape($shortTimeFormat)."', ".
				"longDateFormat = '".DataBase::escape($longDateFormat)."', ".   
				"longTimeFormat = '".DataBase::escape($longTimeFormat)."' ".
			"WHERE id = '$this->_id'");
		
		$this->_shortDateFormat = $shortDateFormat;
		$this->_shortTimeFormat = $shortTimeFormat;
		$this->_longDateFormat = $longDateFormat;
		$this->_longTimeFormat = $longTimeFormat;
		
		$this->_editor = Environment::getCurrent()->getuser();
		$this->_editorID = $this->_editor->getid();
		$this->_editTime = new DateTime();
	}   

	/**
	 * Checks if a name is available
	 *
	 * Checks, if $name is not already assigned to a language. This language's
	 * names are excluded, they are available.
	 *
	 * @param $name name to check 
	 * @return bool true, if $name is available
	 */
	public function isNameAvailable($title) {   
		if ($this->_id === null)
			 	
		$result = DataBase::query(
			"SELECT language.id ".
			"FROM ".DataBase::formTableName('Premanager', 'Languages')." AS language ".
			"WHERE language.name = '".DataBase::escape(Strings::unitize($name)."' ").
				"AND language.id != '$this->_id'");
		return !$result->next();
	}   

	// ===========================================================================
	
	private function load() {
		$result = DataBase::query(
			"SELECT language.name, language.title, language.englishTitle, ".
				"language.longDateFormat, language.longTimeFormat, ".
				"language.shortDateFormat, language.shortTimeFormat, ".
				"language.dateTimePhraseFormat, language.createTime, ".
				"language.editTime, language.creatorID, language.editorID ".
			"FROM ".DataBase::formTableName('Premanager', 'Languages')." AS language ".
			"WHERE language.id = '$this->_id'");

		if (!$result->next())
			return false;

		$this->_name = $result->get('name');
		$this->_title = $result->get('title');
		$this->_longDateFormat = $result->get('longDateFormat');
		$this->_longTimeFormat = $result->get('longTimeFormat');
		$this->_shortDateFormat = $result->get('shortDateFormat');
		$this->_shortTimeFormat = $result->get('shortTimeFormat');
		$this->_dateTimePhraseFormat = $result->get('dateTimePhraseFormat');
		$this->_englishTitle = $result->get('englishTitle');       
		$this->_createTime = new DateTime($result->get('createTime'));
		$this->_creatorID = $result->get('creatorID');
		$this->_editTime = new DateTime($result->get('editTime'));
		$this->_editorID = $result->get('editorID');   	
		
		return true;
	}
}

?>
