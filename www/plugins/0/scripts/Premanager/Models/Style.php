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
use Premanager\Model;
use Premanager\ArgumentException;
use Premanager\ArgumentNullException;
use Premanager\InvalidOperationException;
use Premanager\Strings;
use Premanager\Types;
use Premanager\IO\CorrputDataException;
use Premanager\Debug\Debug;
use Premanager\Debug\AssertionFailedException;
use Premanager\QueryList\QueryList;
use Premanager\QueryList\DataType;
use Premanager\QueryList\ModelDescriptor;
              
/**
 * A style
 */
final class Style extends Model {
	private $_id;
	private $_pluginID;
	private $_plugin;
	private $_path;
	private $_title;
	private $_description;
	private $_author;
	private $_stylesheets;
	private $_instance;
	
	private static $_instances = array();
	private static $_count;
	private static $_default;
	private static $_descriptor;
	private static $_queryList;

	// ===========================================================================  
	
	protected function __construct() {
		parent::__construct();	
	}
	
	private static function createFromID($id, $pluginID = null,
		$path = null) {
		
		if (array_key_exists($id, self::$_instances)) {
			$instance = self::$_instances[$id]; 
			if ($instance->_pluginID === null)
				$instance->_pluginID = $pluginID;
			if ($instance->_path === null)
				$instance->_path = $path;
				
			return $instance;
		}

		if (!Types::isInteger($id) || $id < 0)
			throw new ArgumentException(
				'$id must be a nonnegative integer value');
				
		$instance = new self();
		$instance->_id = $id;
		$instance->_pluginID = $pluginID;
		$instance->_path = $path;
		self::$_instances[$id] = $instance;
		return $instance;
	} 

	// ===========================================================================
	
	/**
	 * Gets a style class using its id
	 * 
	 * @param int $id the id of the style class
	 * @return Premanager\Models\StyleClass
	 */
	public static function getByID($id) {
		if (!Types::isInteger($id) || $id < 0)
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
	 * Creates a new style class and inserts it into data base
	 *
	 * @param Plugin $plugin the plugin that registers this style class             
	 * @param string $path the path to style.xml file, relative to the plugin's
	 *   static directory
	 * @param string $title a display title
	 * @param string $description a short description
	 * @param string $author the style's author(s)
	 * @return Premanager\Models\StyleClass
	 */
	public static function createNew(Plugin $plugin, $path, $title, $description,
		$author)
	{
		$path = trim($path);
		
		$fileName = Config::getStaticPath() . '/' . $plugin->getName() . '/'.$path;
		if (!File::exists($fileName))
			throw new FileNotFoundException('The style file does not exist (file '.
				'name: '.$fileName);
	
		DataBase::query(
			"INSERT INTO ".DataBase::formTableName('Premanager', 'Styles')." ".
			"(pluginID, path, author) ".
			"VALUES ('$plugin->getid()', '".DataBase::escape($path)."', ".
				"'".DataBase::escape($author)."'");
		$id = DataBase::insertID();
		
		DataBaseHelper::update('Premanager', 'Styles', 0, $id, null, array(),
			array('title' => $title, 'description' => $description));	
		
		$instance = self::createFromID($id, $plugin, $path);
		$instance->_title = $title;
		$instance->_description = $description;
		$instance->_author = $author;

		if (self::$_count !== null)
			self::$_count++;		
		
		return $instance;
	}        
	    
	/**
	 * Gets the count of styles
	 *
	 * @return int
	 */
	public static function getCount() {
		if (self::$_count === null) {
			$result = DataBase::query(
				"SELECT COUNT(style.id) AS count ".
				"FROM ".DataBase::formTableName('Premanager', 'Styles')." AS style");
			self::$_count = $result->get('count');
		}
		return self::$_count;
	}  

	/**
	 * Gets the default style class
	 * 
	 * @return Premanager\Models\StyleClass
	 */
	public static function getDefault() {
		if (self::$_default === null) {
			$result = DataBase::query(
				"SELECT style.id, style.pluginID, style.path ".
				"FROM ".DataBase::formTableName('Premanager', 'Styles')." AS style ".
				"WHERE style.isDefault = '1'");
			if ($result->next()) {
				self::$_default = self::createFromID($result->get('id'),
					$result->get('pluginID'), $result->get('path'));
			} else
				throw new CorruptDataException('No default style found');
		}
		return self::$_default;
	}
	
	/**
	 * Sets the default style class
	 * 
	 * @param Premanager\Models\StyleClass $style the new default style class
	 */
	public static function setDefault(StyleClass $style) {
		if (!$style)
			throw new ArgumentNullException('style');
		
		// Remove former default style
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Styles')." AS style ".
			"SET style.isDefault = '0' ".
			"WHERE style.isDefault = '1'");
		
		// Set the new default style
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Styles')." AS style ".
			"SET style.isDefault = '1' ".
			"WHERE style.id = '$style->getid()'");
		
		self::$_default = $style;
	}

	/**
	 * Gets a list of style classes
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getStyleClasses() {
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
				'plugin' => array(Plugin::getDescriptor(), 'getPlugin', 'pluginID'),
				'path' => array(DataType::STRING, 'getPath', 'path'),
				'title' => array(DataType::STRING, 'getTitle', '*title'),
				'description' => array(DataType::STRING, 'getDescription',
					'*description'),
				'author' => array(DataType::STRING, 'getAuthor', '*author')),
				'Premanager', 'Styles', array(__CLASS__, 'getByID'), true);
		}
		return self::$_descriptor;
	}      

	// ===========================================================================
	
	/**
	 * Gets the id of this style class
	 *
	 * @return int
	 */
	public function getID() {
		$this->checkDisposed();
	
		return $this->_id;
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
	 * Creates a list of stylesheets used by this style
	 * 
	 * @return Premanager\Models\Style
	 */
	public function getStylesheets()  {
		$this->checkDisposed();
		
		if ($this->_stylesheets === null) {
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
				$src = $stylesheet->getAttribute('src');
				$type = $stylesheet->getAttribute('type');
				$media = $stylesheet->getAttribute('media');
				$this->_stylesheets[] =
					StylesheetInfo::simpleCreate($this->getPlugin()->getName(),
					dirname($this->getPath()) . '/' . $src,
					$media, $type);
			}
		}
		
		return $this->_stylesheets;
	}   
	
	/**
	 * Deletes and disposes this style class
	 * 
	 * If this style was the default style, any other style will get the new
	 * default style.
	 * 
	 * Throws a Premanager\InvalidOperationException if this is the last style
	 * because there must be at least one style
	 */
	public function delete() {         
		$this->checkDisposed();
		
		// Check if this is the last style
		if (self::getCount() == 1)
			throw new InvalidOperationException('Last style can not be deleted');
			
		// If this was the default style, select another default style
		if (self::getDefault() == $this) {
			$arr = self::getStyleClasses(0, 1);
			self::setDefault($arr[0]);
		}
			
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'Styles')." ".
			"WHERE style.id = '$this->_id'");
			
		unset(self::$_instances[$this->_id]);
		if (self::$_count !== null)
			self::$_count--;			
	
		$this->dispose();
	}      
	
	// ===========================================================================        
	
	private function load() {
		$result = DataBase::query(
			"SELECT style.pluginID, style.path, style.author, translation.title, ".
				"translation.description ".    
			"FROM ".DataBase::formTableName('Premanager', 'Styles')." AS style ",
			/* translating */
			"WHERE style.id = '$this->_id'");
		if (!$result->next())
			return false;
		
		$this->_pluginID = $result->get('pluginID');
		$this->_path = $result->get('path');
		$this->_title = $result->get('title');
		$this->_description = $result->get('description');
		$this->_author = $result->get('author');
		
		return true;
	}
}

?>