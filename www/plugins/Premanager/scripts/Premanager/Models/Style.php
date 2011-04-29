<?php             
namespace Premanager\Models;

use Premanager\Models\StylesheetInfo;
use Premanager\IO\CorruptDataException;
use Premanager\IO\FileNotFoundException;
use Premanager\IO\File;
use Premanager\IO\Config;
use Premanager\IO\DataBase\DataBaseHelper;
use Premanager\IO\DataBase\DataBase;
use Premanager\Module;
use Premanager\Modeling\Model;
use Premanager\ArgumentException;
use Premanager\ArgumentNullException;
use Premanager\InvalidOperationException;
use Premanager\Strings;
use Premanager\Types;
use Premanager\IO\CorrputDataException;
use Premanager\Debug\Debug;
use Premanager\Debug\AssertionFailedException;
use Premanager\Modeling\QueryList;
use Premanager\Modeling\DataType;
use Premanager\Modeling\ModelDescriptor;
              
/**
 * A style
 */
final class Style extends Model {
	private $_pluginID;
	private $_plugin;
	private $_path;
	private $_isEnabled;
	private $_title;
	private $_description;
	private $_author;
	private $_stylesheets;
	private $_instance;
	
	private static $_default;
	private static $_descriptor;

	// ===========================================================================

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Models\StyleModel
	 */
	public static function getDescriptor() {
		return StyleModel::getInstance();
	}
	
	/**
	 * Gets a widget using its id
	 * 
	 * @param int $id
	 * @return Premanager\Models\Style
	 */
	public static function getByID($id) {
		return self::getDescriptor()->getByID($id);
	}
	
	/**
	 * Creates a new style class and inserts it into data base
	 *
	 * @param Plugin $plugin the plugin that registers this style class             
	 * @param string $path the path to style.xml file, relative to the plugin's
	 *   static directory
	 * @param string $title a display title
	 * @param string $description a short description
	 * @param string $author the style's author(s)
	 * @return Premanager\Models\Style
	 */
	public static function createNew(Plugin $plugin, $path, $title, $description,
		$author)
	{
		return self::getDescriptor()->createNew($plugin, $path, $title,
			$description, $author);
	}        
	    
	/**
	 * Gets the count of styles
	 *
	 * @return int
	 */
	public static function getCount() {
		$result = DataBase::query(
			"SELECT COUNT(style.id) AS count ".
			"FROM ".DataBase::formTableName('Premanager', 'Styles')." AS style");
		return $result->get('count');
	}  

	/**
	 * Gets the default style class
	 * 
	 * @return Premanager\Models\Style
	 */
	public static function getDefault() {
		if (self::$_default === null) {
			$result = DataBase::query(
				"SELECT style.id, style.pluginID, style.path ".
				"FROM ".DataBase::formTableName('Premanager', 'Styles')." AS style ".
				"WHERE style.isDefault = '1'");
			if ($result->next()) {
				self::$_default = self::getByID($result->get('id'),
					$result->get('pluginID'), $result->get('path'));
			} else
				throw new CorruptDataException('No default style found');
		}
		return self::$_default;
	}
	
	/**
	 * Sets the default style class
	 * 
	 * @param Premanager\Models\Style $style the new default style class
	 * @throws Premanager\ArgumentException if $style is disabled
	 */
	public static function setDefault(Style $style) {
		if (!$style)
			throw new ArgumentNullException('style');
			
		if (!$style->isEnabled())
			throw new ArgumentException('The specified style is not enabled');
		
		// Remove former default style
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Styles')." AS style ".
			"SET style.isDefault = '0' ".
			"WHERE style.isDefault = '1'");
		
		// Set the new default style
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Styles')." AS style ".
			"SET style.isDefault = '1' ".
			"WHERE style.id = '".$style->getID()."'");
		
		self::$_default = $style;
	}

	/**
	 * Gets a list of style classes
	 * 
	 * @return Premanager\Modeling\QueryList
	 */
	public static function getStyles() {
		return self::getDescriptor()->getStyles();
	}           

	// ===========================================================================

	/**
	 * Gets a boulde of information about the Style model
	 *
	 * @return Premanager\Models\StyleModel
	 */
	public function getModelDescriptor() {
		return StyleModel::getInstance();
	}

	/**
	 * Gets the plugin that has registered this style class
	 *
	 * @return Premanager\Models\Plugin
	 */
	public function getPlugin() {
		$this->checkDisposed();
			
		if ($this->_plugin === null) {
			if ($this->_pluginID === null)
				$this->load();
			$this->_plugin = Plugin::getByID($this->_pluginID);
		}
		return $this->_plugin;	
	}        

	/**
	 * Gets the path to style.xml file, relative to the plugin's static directory
	 *
	 * @return string
	 */
	public function getPath() {
		$this->checkDisposed();
			
		if ($this->_path === null)
			$this->load();
		return $this->_path;	
	}
	
	/**
	 * Gets the absolute path to style.xml
	 */
	public function getFileName() {
		$this->checkDisposed();
		
		return Config::getStaticPath() . '/' . $this->getPlugin()->getName() .
			'/' . $this->_path;
	}

	/**
	 * Gets the translated title
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
	 * Gets a short description
	 *
	 * @return string
	 */
	public function getDescription() {
		$this->checkDisposed();

		if ($this->_description === null)
			$this->load();
		return $this->_description;
	}          

	/**
	 * Gets the style's author(s)
	 *
	 * @return string
	 */
	public function getAuthor() {
		$this->checkDisposed();

		if ($this->_author === null)
			$this->load();
		return $this->_author;
	}     
	
	/**
	 * Checks whether this style is enabled
	 * 
	 * @return bool true, if this style is enabled
	 */
	public function isEnabled() {
		$this->checkDisposed();

		if ($this->_isEnabled === null)
			$this->load();
		return $this->_isEnabled;
	} 
	
	/**
	 * Enables or disables this style
	 * 
	 * @param $enable true to enable or false to disable this style
	 * @throws Premanager\InvalidOperationException tried to disable the default
	 *   style when there is not another enabled style
	 */
	public function setIsEnabled($enable) {
		$this->checkDisposed();
		
		if ($this->isDefault() && !$enable) {
			$l = self::getStyles();
			$l = $l->filter($l->exprMember('isEnabled'));
			if ($l->getCount())
				self::setDefault($l[0]);
			else
				throw new InvalidOperationException('The default style can not be '.
					'disabled when there is not another enabled style');
		}

		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Styles')." ".
			"SET isEnabled = ".($enable ? '1' : '0')." ".
			"WHERE id = ".$this->_id);
		$this->_isEnabled = !!$enable;
	}
	
	/**
	 * Checks whether this style is the default style
	 * 
	 * @return bool true, if this style is the default style
	 */
	public function isDefault() {
		return $this === self::getDefault();
	}

	/**
	 * Creates a list of stylesheets used by this style
	 * 
	 * @param string $doctype specify to a value not equal to 'all' to select only
	 *   those stylesheets for a specific doctype (e.g. 'page' or 'mail')
	 * @return Premanager\Models\Style
	 */
	public function getStylesheets($doctype = 'all')  {
		$this->checkDisposed();
		
		if (!$doctype)
			$doctype = 'all';
		
		if ($this->_stylesheets[$doctype] === null) {
			$fileName = $this->getFileName();
			if (!File::exists($fileName))
				throw new CorruptDataException('Referred style file does not exist ('.
					'id: '.$this->_id.', file name: '.$fileName);
				
			$xml = new \DOMDocument();
			if (!$xml->load($fileName))
				throw new CorruptDataException('Referred style file is invalid ('.
					'id: '.$this->_id.', file name: '.$fileName);
			$this->_stylesheets = array();
			foreach ($xml->getElementsByTagName('stylesheet') as $stylesheet) {
				$dt = $stylesheet->getAttribute('doctype');
				if ($dt && $dt != 'all' && $doctype != 'all' && $dt != $doctype)
					continue;
					 
				$src = $stylesheet->getAttribute('src');
				$type = $stylesheet->getAttribute('type');
				$media = $stylesheet->getAttribute('media');
				$this->_stylesheets[$doctype][] =
					StylesheetInfo::simpleCreate($this->getPlugin()->getName(),
					dirname($this->getPath()) . '/' . $src,
					$media, $type);
			}
		}
		
		return $this->_stylesheets[$doctype];
	}   
	
	/**
	 * Deletes and disposes this style class
	 * 
	 * If this style was the default style, any other style will get the new
	 * default style.
	 * 
	 * @throws Premanager\InvalidOperationException this is the last style
	 */
	public function delete() {         
		$this->checkDisposed();
		
		// Check if this is the last style
		if (self::getCount() == 1)
			throw new InvalidOperationException('Last style can not be deleted');
			
		// If this was the default style, select another default style
		if (self::getDefault() === $this) {
			$l = self::getStyles();
			$l = $l->filter($l->exprMember('isEnabled'));
			if ($l->getCount())
				self::setDefault($l[0]);
			else {
				$l = self::getStyles();
				$style = $l[0];
				$style->setIsEnabled(true);
				self::setDefault($style);
			}
		}
		
		$this->delete();
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
		$fields[] = 'pluginID';
		$fields[] = 'path';
		$fields[] = 'author';
		$fields[] = 'isEnabled';
		$fields[] = 'translation.title';
		$fields[] = 'translation.description';
		
		if ($values = parent::load($fields)) {
			$this->_pluginID = $values['pluginID'];
			$this->_path = $values['path'];
			$this->_isEnabled = $values['isEnabled'];
			$this->_title = $values['title'];
			$this->_description = $values['description'];
			$this->_author = $values['author'];
		}
		
		return $values;
	}       
}

?>