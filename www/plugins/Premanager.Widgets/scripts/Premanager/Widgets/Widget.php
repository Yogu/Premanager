<?php             
namespace Premanager\Widgets;

use Premanager\Models\Plugin;

use Premanager\Execution\Template;

use Premanager\NotImplementedException;

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
use Premanager\Models\User;
use Premanager\Models\StructureNode;
use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\QueryList;
use Premanager\Modeling\DataType;
use Premanager\IO\DataBase\DataBase;

/**
 * An abstract base class for widgets
 */
abstract class Widget extends Model {
	/**
	 * @var int
	 */
	private $_widgetClassID;
	/**
	 * @var Premanager\Widgets\WidgetClass
	 */
	private $_widgetClass = false;
	/**
	 * @var int
	 */
	private $_userID;
	/**
	 * @var Premanager\Models\User
	 */
	private $_user;
	/**
	 * @var int
	 */
	private $_structureNodeID;
	/**
	 * @var Premanager\Models\StructureNode
	 */
	private $_structureNode;
	/**
	 * @var int
	 */
	private $_order;
	
	/**
	 * @var Premanager\QueryList\ModelDescriptor
	 */
	private static $_descriptor;

	// ===========================================================================

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Widgets\WidgetModel
	 */
	public static function getDescriptor() {
		return WidgetModel::getInstance();
	}
	
	/**
	 * Gets a widget using its id
	 * 
	 * @param int $id
	 * @return Premanager\Widgets\Widget
	 */
	public static function getByID($id) {
		return self::getDescriptor()->getByID($id);
	}
	    
	/**
	 * Gets the count of widgets
	 * 
	 * The result is not cached.
	 *
	 * @return int
	 */
	public static function getCount() {
		// Premanager\Widgets\WidgetCollection can create new widgets, so caching
		// the count is not possible
		$result = DataBase::query(
			"SELECT COUNT(widgetClass.treeID) AS count ".
			"FROM ".DataBase::formTableName('Premanager.Widgets', 'WidgetClasses').
				" AS widgetClass");
		return $result->get('count');
	}  

	/**
	 * Gets a list of all widgets
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getWidgets() {
		return self::getDescriptor()->getQueryList();
	}                                          

	// ===========================================================================

	/**
	 * Gets a boulde of information about the Widget model
	 *
	 * @return Premanager\Widgets\WidgetModel
	 */
	public function getModelDescriptor() {
		return WidgetModel::getInstance();
	}
	
	/**
	 * Gets the widget class this widget is a instance of
	 * 
	 * @return Premanager\Widgets\WidgetClass the widget class
	 */
	public function getWidgetClass() {
		if ($this->_widgetClass === false) {
			if ($this->_widgetClassID === null)
				$this->load();
			$this->_widgetClass = WidgetClass::getByID($this->_widgetClassID);
		}
		return $this->_widgetClass;
	}

	/**
	 * Gets the structure node of the widget panel this widget is placed in, if
	 * the widget is placed in a widget panel
	 *
	 * @return Premanager\Models\StructureNode
	 */
	public function getStructureNode() {
		$this->checkDisposed();
			
		if ($this->_structureNode === null) {
			if ($this->_structureNodeID === null)
				$this->load();
			$this->_structureNode = StructureNode::getByID($this->_structureNodeID);
		}
		return $this->_structureNode;
	}      

	/**
	 * Gets the user that owns this widget, if this widget is user-specified
	 *
	 * @return Premanager\Models\User the user that owns this widget or null if
	 *   this is not a user-specified widget
	 */
	public function getUser() {
		$this->checkDisposed();
			
		if ($this->_user === null) {
			if ($this->_userID === null)
				$this->load();
			$this->_user = User::getByID($this->_userID);
		}
		return $this->_user;
	}

	/**
	 * Gets the widget collection this widget is placed in
	 *
	 * @return Premanager\Widgets\WidgetCollection the widget collection this
	 *   widget is placed in
	 */
	public function getWidgetCollection() {
		$this->checkDisposed();
			
		if ($this->getStructureNode())
			throw new NotImplementedException();
		else 
			return Sidebar::get($this->getUser());
	}
	
	/**
	 * Gets a number that defines the index of this widget in a column
	 * 
	 * @return int the order number
	 */
	public function getOrder() {
		$this->checkDisposed();
		
		if ($this->_order === null)
			$this->load();
		return $this->_order;
	}
	
	/**
	 * Gets the content of this widget in HTML
	 * 
	 * @return string the content in HTML
	 */
	public abstract function getContent();
	
	/**
	 * Gets the title of this widget
	 * 
	 * The default implementation gets the title of the widget class
	 * 
	 * @return string the title of this widget
	 */
	public function getTitle() {
		return $this->getWidgetClass()->getTitle();
	}
	
	/**
	 * Gets a url, if this widget should be linked to it
	 * 
	 * Inherited classes can override this method.
	 * 
	 * @return string the relative url or an empty string
	 */
	public function getLinkURL() {
		return '';
	}
	
	/**
	 * Gets the html code of the widget including its surrounding block
	 * 
	 * @return the html code of the widget
	 */
	public function getHTML() {
		$template = new Template('Premanager.Widgets', 'widget');
		$template->set('widget', $this);
		return $template->get();
	}
	
	public function internalRefreshOrder() {
		$this->_order = 0;
	}
	
	public function internalDelete() {
		$this->delete();
	}

	// ===========================================================================
	
	/**
	 * Gets a sample content in HTML
	 * 
	 * Must be implemented by inheriting classes
	 * 
	 * @return string the content in HMTL
	 */
	public static function getSampleContent() {
		throw new NotImplementedException(
			'Premanager\Widgets\Widget::getSamleContent() method is not implemented '.
			'by inheriting class '.get_class($this));
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
		$fields[] = 'widgetClassID';
		$fields[] = 'nodeID';
		$fields[] = 'userID';
		$fields[] = 'order';
		
		if ($values = parent::load($fields)) {
			$this->_widgetClassID = $values['widgetClassID'];
			$this->_structureNodeID = $values['nodeID'];
			$this->_userID = $values['userID'];
			$this->_order = $values['order'];
		}
		
		return $values;
	}   
}

?>
