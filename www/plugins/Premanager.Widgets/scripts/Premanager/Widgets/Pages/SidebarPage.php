<?php
namespace Premanager\Widgets\Pages;

use Premanager\Widgets\Widget;

use Premanager\Execution\Redirection;

use Premanager\Widgets\Sidebar;

use Premanager\Widgets\WidgetClass;

use Premanager\Execution\TreePageNode;
use Premanager\Models\Project;
use Premanager\Execution\Options;
use Premanager\DateTime;
use Premanager\Models\Session;
use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Execution\ToolBarItem;
use Premanager\Debug\Debug;
use Premanager\QueryList\SortDirection;
use Premanager\QueryList\QueryOperation;
use Premanager\QueryList\SortRule;
use Premanager\Execution\TreeListPageNode;
use Premanager\Models\StructureNode;
use Premanager\Execution\ListPageNode;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Translation;
use Premanager\Execution\Page;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\Models\User;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * An abstract class for pages that allow to edit a sidebar
 */
abstract class SidebarPage extends PageNode {	
	private $_sidebar;

	// ===========================================================================
	
	/**
	 * Creates a new SidebarAdminPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Widgets\Sidebar $sidebar the sidebar to edit
	 */
	public function __construct($parent, Sidebar $sidebar)
	{
		parent::__construct($parent, $structureNode);
		$this->_sidebar = $sidebar;
	}
	
	// ===========================================================================
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse(Right $requiredRight = null) {
		if ($requiredRight && !Rights::requireRight($requiredRight, null,
			$errorResponse, false))
			return $errorResponse;
			
		if (Request::getPOST('add')) {
			$id = Request::getPOST('widget-class-id');
			$widgetClass = WidgetClass::getByID($id);
			if ($widgetClass) {
				if ($requiredRight && !Rights::requireRight($requiredRight, null,
					$errorResponse))
					return $errorResponse;
				
				if (!$this->_sidebar->isExisting()) {
					$this->_sidebar->applyFrom(Sidebar::getDefault());
					$this->_sidebar->setIsExisting(true);
				}
				$this->_sidebar->insertNewWidget($widgetClass);
			}
			return new Redirection();
		} else if (Request::getPOST('remove') || Request::getPOST('move-up') ||
			Request::getPOST('remove-down'))
		{
			$id = Request::getPOST('widget-id');
			$widget = Widget::getByID($id);
			if ($widget && $widget->getWidgetCollection() == $this->_sidebar) {
				if ($requiredRight && !Rights::requireRight($requiredRight, null,
					$errorResponse))
					return $errorResponse;
			
				if (!$this->_sidebar->isExisting()) {
					$this->_sidebar->applyFrom(Sidebar::getDefault());
					$this->_sidebar->setIsExisting(true);
					
					// ID has changed - get new widget
					$widget = Widget::getWidgets()->getByIndex($widget->getOrder());
					Debug::assert($newWidget != null);
				}
				
				if (Request::getPOST('remove'))
					$this->_sidebar->remove($widget);
				else if (Request::getPOST('move-up'))
					$this->_sidebar->moveUp($widget);
				else if (Request::getPOST('move-down'))
					$this->_sidebar->moveDown($widget);
			}
			return new Redirection();
		}
	}

	/**
	 * Gets the list of widget classes sorted by their title
	 * 
	 * @return Premanager\QueryList\QueryList the list of widget classes
	 */
	private static function getList() {
		static $cache;
		if (!$cache) {
			$cache = WidgetClass::getWidgetClasses();
			$cache = $cache->sort(array(
				new SortRule($cache->exprMember('title'))));
		}
		return $cache;
	}
	
	/**
	 * Gets the sidebar edited by this page
	 * 
	 * @return Premanager\Widgets\Sidebar the sidebar
	 */
	public function getSidebar() {
		return $this->_sidebar;
	}
	
	protected static function getWidgetClassesBlock() {
		$list = self::getList();
		$template = new Template('Premanager.Widgets', 'widgetClasses');
		$template->set('widgetClasses', self::getList());
		return PageBlock::createSimple(
			Translation::defaultGet('Premanager.Widgets', 'widgetClassesTitle'),
			$template->get());
	}
}

?>
