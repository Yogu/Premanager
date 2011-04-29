<?php           
namespace Premanager\Models;

use Premanager\Module;
use Premanager\Modeling\Model;
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
	private $_name;
	private $_title;
	private $_englishTitle;
	private $_shortDateFormat;
	private $_shortTimeFormat;
	private $_longDateFormat;
	private $_longTimeFormat;
	private $_dateTimePhraseFormat;
	
	private static $_default;
	private static $_internationalLanguage;
	private static $_descriptor;   
	
	// ===========================================================================

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Models\LanguageModel
	 */
	public static function getDescriptor() {
		return LanguageModel::getInstance();
	}
	
	/**
	 * Gets a language using its id
	 * 
	 * @param int $id
	 * @return Premanager\Models\Language
	 */
	public static function getByID($id) {
		return self::getDescriptor()->getByID($id);
	}
                               
	/**
	 * Gets a language using its name (the language code)
	 *
	 * Returns null if $name is not found
	 *
	 * @param string $name name of the language
	 * @return Premanager\Models\Language
	 */
	public static function getByName($name) {
		return self::getDescriptor()->getByName($name);
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
		return self::getDescriptor()->createNew($name, $title, $englishTitle);
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
				"SELECT language.id ".            
				"FROM ".DataBase::formTableName('Premanager', 'Languages')." AS language ".
				"WHERE language.isDefault = '1'");
			if ($result->next()) {
				self::$_default = self::getByID($result->get('id'));
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
				"SELECT language.id ".            
				"FROM ".DataBase::formTableName('Premanager', 'Languages')." AS language ".
				"WHERE language.isInternational = '1'");
			if ($result->next()) {
				self::$_internationalLanguage = self::getByID($result->get('id'));
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

	// ===========================================================================

	/**
	 * Gets a boulde of information about the Language model
	 *
	 * @return Premanager\Models\LanguageModel
	 */
	public function getModelDescriptor() {
		return LanguageModel::getInstance();
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
		return parent::getCreator();
	}                        

	/**
	 * Gets the time when this language has been created
	 *
	 * @return Premanager\DateTime
	 */
	public function getCreateTime() {
		return parent::getCreateTime();
	}                               

	/**
	 * Gets the user that has edited this language the last time
	 *
	 * @return Premanager\Models\User
	 */
	public function getEditor() {
		return parent::getEditor();
	}                        

	/**
	 * Gets the time when this language has been edited the last time
	 *
	 * @return Premanager\DateTime
	 */
	public function getEditTime() {
		return parent::getEditTime();
	}   
	
	/**
	 * Gets the count of times this language has been edited
	 * 
	 * @return Premanager\DateTime the count of edit times
	 */
	protected function getEditTimes() {
		return parent::getEditTimes();
	}
	
	/**
	 * Deletes and disposes this language
	 * 
	 * If this is the default or international language, any other language will
	 * replace that position.
	 * 
	 * Throws Premanager\InvalidOperationException if this is the last language
	 *
	 * The strings table will not be modified.
	 */
	public function delete() {
		$this->checkDisposed();
		
		// Check if this is the last language
		if (self::getCount() == 1)
			throw new InvalidOperationException('Last language can not be deleted');
			
		// If this was the default langauge, select another default language
		if (self::getDefault() === $this) {
			self::setDefault(self::getLanguages()->get(0));
		}
			
		// If this was international langauge, select another international language
		if (self::getInternationalLanguage() == $this) {
			self::setInternationalLanguage(self::getLanguages()->get(0));
		}
		
		parent::delete();
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
			
		$this->update(
			array(
				'name' => $name,
				'title' => $title,
				'englishTitle' => $englishTitle
			)
		);
		
		$this->_name = $name;
		$this->_title = $title;
		$this->_englishTitle = $englishTitle;
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
	 * @param string $dataTimePhraseFormat a datetime format string
	 */
	public function setFormatStrings($shortDateFormat, $shortTimeFormat,
		$longDateFormat, $longTimeFormat, $dateTimePhraseFormat) {
		$this->checkDisposed(); 
			  
		$shortDateFormat = \trim($shortDateFormat);
		$shortTimeFormat = \trim($shortTimeFormat);    
		$longDateFormat = \trim($longDateFormat);
		$longTimeFormat = \trim($longTimeFormat);  
		$dateTimePhraseFormat = \trim($dateTimePhraseFormat);  
		                       
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
		if (!$dateTimePhraseFormat)
			throw new ArgumentException('$$dateTimePhraseFormat is an empty string '.
				'or contains only whitespaces', 'longTimeFormat');   
			
		$this->update(
			array(
				'shortDateFormat' => $shortDateFormat,
				'shortTimeFormat' => $title,
				'longDateFormat' => $longDateFormat,
				'longTimeFormat' => $longTimeFormat,
				'dateTimePhraseFormat' => $dateTimePhraseFormat
			),
			array()
		);    
		
		$this->_shortDateFormat = $shortDateFormat;
		$this->_shortTimeFormat = $shortTimeFormat;
		$this->_longDateFormat = $longDateFormat;
		$this->_longTimeFormat = $longTimeFormat;
		$this->_dateTimePhraseFormat = $dateTimePhraseFormat;
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
		$fields[] = 'name';
		$fields[] = 'title';
		$fields[] = 'englishTitle';
		$fields[] = 'longDateFormat';
		$fields[] = 'longTimeFormat';
		$fields[] = 'shortDateFormat';
		$fields[] = 'shortTimeFormat';
		$fields[] = 'dateTimePhraseFormat';
		
		if ($values = parent::load($fields)) {
			$this->_name = $values['name'];
			$this->_title = $values['title'];
			$this->_englishTitle = $values['englishTitle'];
			$this->_longDateFormat = $values['longDateFormat'];
			$this->_longTimeFormat = $values['longTimeFormat'];
			$this->_shortDateFormat = $values['shortDateFormat'];
			$this->_shortTimeFormat = $values['shortTimeFormat'];
			$this->_dateTimePhraseFormat = $values['dateTimePhraseFormat'];
		}
		
		return $values;
	}
}

?>
