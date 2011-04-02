<?php
namespace Premanager\Widgets;

use Premanager\ArgumentException;

use Premanager\IO\DataBase\DataBaseHelper;
use Premanager\QueryList\SortRule;
use Premanager\QueryList\QueryOperation;
use Premanager\Module;
use Premanager\IO\DataBase\DataBase;
use Premanager\Models\User;
use Premanager\Models\StructureNode;

/**
 * The base class for sidebars and widget pages
 */
class WidgetCollection extends Module {
	/**
	 * @var Premanager\Models\StructureNode
	 */
	private $_structureNode;
	/**
	 * @var int
	 */
	private $_structureNodeID;
	/**
	 * @var Premanager\Models\User
	 */
	private $_user;
	/**
	 * @var int
	*/
	private $_userID;
	/**
	 * @var int
	 */
	private $_count;
	/**
	 * @var array
	 */
	private $_widgets;
	
	/**
	 * @var Premanager\QueryList\QueryList
	 */
	private static $_instances;

	// ===========================================================================
	
	/**
	 * Internal use only.
	 */
	public function __construct(StructureNode $structureNode = null,
		User $user = null)
	{
		parent::__construct();
		$this->_structureNode = $stuctureNode;
		$this->_user = $user;
		$this->_structureNodeID = $structureNode ? $structureNode->getID() : 0;
		$this->_userID = $user ? $user->getID() : 0;
	}
	
	/**
	 * Gets the widget collection for the specified structure node and user
	 * 
	 * Internal method. Better use the static methods of inheriting classes
	 * 
	 * @param Premanager\Models\StructureNode $structureNode
	 * @param Premanager\Models\User $user
	 */
	public static function internalGet(StructureNode $structureNode = null,
		User $user = null)
	{
		if ($structureNode)
			throw new NotImplementedException('Widget Pages are not implemented yet');
		
		if ($user && $user->getID() == 0)
			$user = null;
			
		$key = ($structureNode ? $structureNode->getID() : 0) . '_' .
			($user ? $user->getID() : 0);
		if (self::$_instances[$key])
			return self::$_instances[$key];
		else {
			if ($structureNode)
				$instance = new WidgetCollection($structureNode, $user);
			else
				$instance = new Sidebar($structureNode, $user);
			self::$_instances[$key] = $instance;
			return $instance;
		}
	} 

	// =========================================================================== 
	
	/**
	 * Gets the structure node of the widget panel, if this widget collection is
	 * a widget panel.
	 * 
	 * @return Premanager\Models\StructureNode the structure node or null
	 */
	public function getStructureNode() {
		return $this->_structureNode;
	}
	
	/**
	 * If this is a user-specified widget collection, gets the owner user
	 * 
	 * @return Premanager\Models\User the user or null
	 */
	public function getUser() {
		return $this->_user;
	}
	
	/**
	 * Gets a list of widgets contained by this collection
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public function getWidgets() {
		if ($this->_widgets === null) {
			$l = Widget::getWidgets();
			$l = $l->filter(
				$l->exprAnd(
					$l->exprEqual($l->exprMember('structureNode'), $this->_structureNode),
					$l->exprEqual($l->exprMember('user'), $this->_user)));
			$l = $l->sort(array(
				new SortRule($l->exprMember('order'))));
			$this->_widgets = $l;
		}
		return $this->_widgets;
	}
	
	/**
	 * Appends a new widget to the end of the widget collection
	 * 
	 * @param Premanager\Widgets\WidgetClass $widgetClass the class to instanciate
	 * @return Premanager\Widgets\Widget the created widget
	 */
	public function insertNewWidget(WidgetClass $widgetClass) {
		$order = $this->getCount();
		
		$id = DataBaseHelper::insert('Premanager.Widgets', 'Widgets',
			DataBaseHelper::CREATOR_FIELDS | DataBaseHelper::EDITOR_FIELDS |
			DataBaseHelper::NO_TRANSLATION, null,
			array(
				'widgetClassID' => $widgetClass->getID(),
				'nodeID' => $this->_structureNodeID,
				'userID' => $this->_userID,
				'order' => $order),
			array());
		
		$widget = Widget::getByID($id);
		return $widget;
	}
	
	/**
	 * Gets the total count of widgets in this widget collection
	 * 
	 * @return int the total count of widgets
	 */
	public function getCount() {
		if ($this->_count === null) {
			$result = DataBase::query(
				"SELECT COUNT(widget.id) AS count ".
				"FROM ".DataBase::formTableName('Premanager.Widgets', 'Widgets').
					" AS widget ".
				"WHERE widget.nodeID = '".$this->_structureNodeID."' ".
					"AND widget.userID = '".$this->_userID."'");
			$this->_count = $result->get('count');
		}
		return $this->_count;
	}
	
	/**
	 * Removes the specified widget from this list and deletes and disposes the
	 * widget
	 * 
	 * @param Widget $widget the widget to remove
	 */
	public function remove(Widget $widget) {
		if ($widget->getWidgetCollection() != $this)
			throw new ArgumentException('The specified widget does not belong to '.
				'this widget collection');
			
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager.Widgets', 'Widgets')." ".
			"SET `order` = `order` - 1 ".
			"WHERE nodeID = '".$this->_structureNodeID."' ".
				"AND userID = '".$this->_userID."'".
				"AND `order` > ".$widget->getOrder());
			
		$widget->internalDelete();
	}
	
	public function moveUp(Widget $widget) {
		if ($widget->getWidgetCollection() != $this)
			throw new ArgumentException('The specified widget does not belong to '.
				'this widget collection');
			
		// Do not remove when first widget
		if ($widget->getOrder() > 0) {
			// Move top one down
			DataBase::query(
				"UPDATE ".DataBase::formTableName('Premanager.Widgets', 'Widgets')." ".
				"SET `order` = `order` + 1 ".
				"WHERE nodeID = '".$this->_structureNodeID."' ".
					"AND userID = '".$this->_userID."'".
					"AND `order` = ".($widget->getOrder() - 1));
			
			// Move this up
			DataBase::query(
				"UPDATE ".DataBase::formTableName('Premanager.Widgets', 'Widgets')." ".
				"SET `order` = `order` - 1 ".
				"WHERE id = ".$widget->getID());
			
			$this->refreshOrder();
		}
	}
	
	public function moveDown(Widget $widget) {
		if ($widget->getWidgetCollection() != $this)
			throw new ArgumentException('The specified widget does not belong to '.
				'this widget collection');
			
		// Do not remove when last widget
		if ($widget->getOrder() < $this->getWidgets()->getCount() - 1) {
			// Move bottom one up
			DataBase::query(
				"UPDATE ".DataBase::formTableName('Premanager.Widgets', 'Widgets')." ".
				"SET `order` = `order` - 1 ".
				"WHERE nodeID = '".$this->_structureNodeID."' ".
					"AND userID = '".$this->_userID."'".
					"AND `order` = ".($widget->getOrder() + 1));
			
			// Move this down
			DataBase::query(
				"UPDATE ".DataBase::formTableName('Premanager.Widgets', 'Widgets')." ".
				"SET `order` = `order` + 1 ".
				"WHERE id = ".$widget->getID());
			
			$this->refreshOrder();
		}
	}
	
	private function refreshOrder() {
		foreach ($this->getWidgets() as $widget) {
			$widget->internalRefreshOrder();
		}
	}
}

?>