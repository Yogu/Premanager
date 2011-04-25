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
use Premanager\Model;
use Premanager\Types;
use Premanager\Strings;
use Premanager\ArgumentException;
use Premanager\ArgumentNullException;
use Premanager\InvalidOperationException;
use Premanager\Debug\Debug;
use Premanager\Processing\TreeNode;
use Premanager\Models\User;
use Premanager\Models\StructureNode;
use Premanager\Modelling\ModelDescriptor;
use Premanager\Modelling\QueryList;
use Premanager\Modelling\DataType;
use Premanager\IO\DataBase\DataBase;

/**
 * An abstract base class for widgets
 */
abstract class Widget extends Model {
	/**
	 * @var int
	 */
	private $_id;
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
	 * @var array
	 */
	private static $_instances = array();
	/**
	 * @var int
	 */
	private static $_count;
	/**
	 * @var Premanager\QueryList\ModelDescriptor
	 */
	private static $_descriptor;
	/**
	 * @var Premanager\QueryList\QueryList
	 */
	private static $_queryList;

	// ===========================================================================    
	
	protected function __construct() {
		parent::__construct();	
	}
	
	private static function createFromID($id, $widgetClassID = null,
		$userID = null, $structureNodeID = null)
	{
		if (\array_key_exists($id, self::$_instances)) {
			$instance = self::$_instances[$id]; 
			if ($instance->_widgetClassID === null)
				$instance->_widgetClassID = $widgetClassID;
			if ($instance->_userID === null)
				$instance->_userID = $userID;
			if ($instance->_structureNodeID === null)
				$instance->_structureNodeID = $structureNodeID;
				
			return $instance;
		}

		if (!Types::isInteger($id) || $id < 0)
			throw new ArgumentException('$id must be a nonnegative integer value',
				'id');
			
		// Get widget class
		$result = DataBase::query(
			"SELECT widgetClass.class ".
			"FROM ".DataBase::formTableName('Premanager.Widgets', 'WidgetClasses').
				" AS widgetClass ".
			"INNER JOIN ".DataBase::formTableName('Premanager.Widgets', 'Widgets').
				" AS widget ON widget.widgetClassID = widgetClass.id ".
			"WHERE widget.id = '$id'");
		if (!$result->next())
			return null;
		$class = $result->get('class');
				
		$instance = new $class();
		$instance->_id = $id;
		$instance->_widgetClassID = $widgetClassID;
		$instance->_userID = $userID;
		$instance->_structureNodeID = $structureNodeID;
		self::$_instances[$id] = $instance;
		return $instance;
	} 

	// ===========================================================================
	
	/**
	 * Gets a widget using its id
	 * 
	 * @param int $id the id of the widget
	 * @return Premanager\Widgets\Widget
	 */
	public static function getByID($id) {
		$id = (int)$id;
			
		if (!Types::isInteger($id) || $id < 0)
			return null;
		else if (\array_key_exists($id, self::$_instances)) {
			return self::$_instances[$id];
		} else {
			// This method already checks if the model exists
			return self::createFromID($id);
		}
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
		if (!self::$_queryList)
			self::$_queryList = new QueryList(self::getDescriptor());
		return self::$_queryList;
	}          

	/**
	 * Gets a boundle of information about this model
	 *
	 * @return Premanager\QueryList\ModelDescriptor
	 */
	public static function getDescriptor() {
		if (self::$_descriptor === null) {
			self::$_descriptor = new ModelDescriptor(__CLASS__, array(
				'id' => array(DataType::NUMBER, 'getID', 'id'),
				'widgetClass' => array(WidgetClass::getDescriptor(), 'getWidgetClass',
					'widgetClassID'),
				'structureNode' => array(StructureNode::getDescriptor(),
					'getStructureNode', 'nodeID'),
				'user' => array(User::getDescriptor(), 'getUser', 'userID'),
				'order' => array(DataType::NUMBER, 'getOrder', 'order')),
				'Premanager.Widgets', 'Widgets', array(__CLASS__, 'getByID'), false);
		}
		return self::$_descriptor;
	}                                           

	// ===========================================================================
	
	/**
	 * Gets the id of this widget
	 *
	 * @return int
	 */
	public function getID() {
		$this->checkDisposed();
	
		return $this->_id;
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
		DataBaseHelper::delete('Premanager.Widgets', 'Widgets',
			DataBaseHelper::NO_TRANSLATION | DataBaseHelper::NO_NAME, $this->_id);
			
		//TODO: Delete options
		
		$this->_id = 0;
		$this->dispose();
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
	
	private function load() {
		$result = DataBase::query(
			"SELECT widget.widgetClassID, widget.nodeID, widget.userID, ".
				"widget.order ".
			"FROM ".DataBase::formTableName('Premanager.Widgets', 'Widgets').
				" AS widget ".
			"WHERE widget.id = '$this->_id'");
		
		if (!$result->next())
			return false;
		
		$this->_widgetClassID = $result->get('widgetClassID');
		$this->_structureNodeID = $result->get('nodeID');
		$this->_userID = $result->get('userID');
		$this->_order = $result->get('order');
		
		return true;
	}      
}

?>
