<?php             
namespace Premanager\Widgets;

use Premanager\IO\DataBase\DataBaseHelper;
use Premanager\InvalidEnumArgumentException;
use Premanager\Execution\PageNode;
use Premanager\Execution\StructurePageNode;
use Premanager\IO\CorruptDataException;
use Premanager\Module;
use Premanager\Modeling\Model;
use Premanager\Types;
use Premanager\Strings;
use Premanager\ArgumentException;
use Premanager\ArgumentNullException;
use Premanager\InvalidOperationException;
use Premanager\Debug\Debug;
use Premanager\Processing\TreeNode;
use Premanager\Models\Plugin;
use Premanager\Models\StructureNode;
use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\QueryList;
use Premanager\Modeling\DataType;
use Premanager\IO\DataBase\DataBase;

/**
 * A class for widget
 */
final class WidgetClass extends Model {
	private $_pluginID;
	private $_plugin;
	private $_className;
	private $_title;
	private $_description;
	
	/**
	 * @var Premanager\Widgets\WidgetClassDescriptor
	 */
	private static $_descriptor;

	// ===========================================================================

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Widgets\WidgetClassModel
	 */
	public static function getDescriptor() {
		return WidgetClassModel::getInstance();
	}
	
	/**
	 * Gets a widget class using its id
	 * 
	 * @param int $id
	 * @return Premanager\Widgets\WidgetClass
	 */
	public static function getByID($id) {
		return self::getDescriptor()->getByID($id);
	}
	
	/**
	 * Creates a new widget class and inserts it into data base
	 *
	 * @param Premanager\Models\Plugin $plugin the plugin that registers this
	 *   widget class             
	 * @param string $className the class name for the widget class
	 * @param string $title a title
	 * @param string $description a short description
	 * @return Premanager\Widgets\WidgetClass
	 */
	public static function createNew(Plugin $plugin, $className, $title,
		$description)
	{
		return self::getDescriptor()->createNew($plugin, $className, $title,
			$description);
	}        
	    
	/**
	 * Gets the count of widget classes
	 *
	 * @return int
	 */
	public static function getCount() {
		$result = DataBase::query(
			"SELECT COUNT(widgetClass.treeID) AS count ".
			"FROM ".DataBase::formTableName('Premanager.Widgets', 'WidgetClasses').
				" AS widgetClass");
		return $result->get('count');
	}  

	/**
	 * Gets a list of all widget classes
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getWidgetClasses() {
		return self::getDescriptor()->getQueryList();
	}                                                  

	// ===========================================================================

	/**
	 * Gets a boulde of information about the WidgetClass model
	 *
	 * @return Premanager\Widgets\WidgetClassModel
	 */
	public function getModelDescriptor() {
		return WidgetClassModel::getInstance();
	}

	/**
	 * Gets the plugin that has registered this widget class
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
	 * Gets the class name for this widget class
	 *
	 * @return string
	 */
	public function getClassName() {
		$this->checkDisposed();
			
		if ($this->_className === null)
			$this->load();
		return $this->_className;	
	}        

	/**
	 * Gets a translated title
	 *
	 * @return string the title
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
	 * @return string the description
	 */
	public function getDescription() {
		$this->checkDisposed();
			
		if ($this->_description === null)
			$this->load();
		return $this->_description;
	}   
	
	/**
	 * Deletes and disposes this widget class
	 */
	public function delete() {         
		$this->checkDisposed();
			
		//TODO: Delete options
		
		parent::delete();
	}       

	public function getSampleContent() {
		return call_user_func(array($this->getClassName(), 'getSampleContent'));
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
		$fields[] = 'class';
		$fields[] = 'translation.title';
		$fields[] = 'translation.description';
		
		if ($values = parent::load($fields)) {
			$this->_pluginID = $values['pluginID'];
			$this->_className = $values['class'];
			$this->_title = $values['title'];
			$this->_description = $values['description'];
		}
		
		return $values;
	}         
}

?>
