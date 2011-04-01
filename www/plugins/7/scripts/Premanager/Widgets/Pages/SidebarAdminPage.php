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
 * A page that allows to edit the sidebar of all guests and users without own
 * sidebar
 */
class SidebarAdminPage extends TreePageNode {	
	private $_sidebar;

	// ===========================================================================
	
	/**
	 * Creates a new SidebarAdminPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\StructureNode $structureNode the structure node
	 *   this page node is embedded in
	 */
	public function __construct($parent, StructureNode $structureNode) {
		parent::__construct($parent, $structureNode);
		$this->_sidebar = Sidebar::getDefault();
	}
	
	// ===========================================================================
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		if (!Rights::requireRight(
			Right::getByName('Premanager.Widgets', 'editDefaultSidebar'),
			null, $errorResponse, false))
			return $errorResponse;
			
		if (Request::getPOST('add')) {
			$id = Request::getPOST('widget-class-id');
			$widgetClass = WidgetClass::getByID($id);
			if ($widgetClass) {
				if (!Rights::requireRight(
					Right::getByName('Premanager.Widgets', 'editDefaultSidebar'),
					null, $errorResponse))
					return $errorResponse;
				
				$this->_sidebar->insertNewWidget($widgetClass);
			}
			return new Redirection();
		} else if (Request::getPOST('widget-id')) {
			$id = Request::getPOST('widget-id');
			$widget = Widget::getByID($id);
			if ($widget && $widget->getWidgetCollection() == $this->_sidebar) {
				if (!Rights::requireRight(
					Right::getByName('Premanager.Widgets', 'editDefaultSidebar'),
					null, $errorResponse))
					return $errorResponse;
				
				if (Request::getPOST('remove'))
					$this->_sidebar->remove($widget);
				else if (Request::getPOST('move-up'))
					$this->_sidebar->moveUp($widget);
				else if (Request::getPOST('move-down'))
					$this->_sidebar->moveDown($widget);
			}
			return new Redirection();
		}
		
		$list = self::getList();
		
		$page = new Page($this);
		$page->title =
			Translation::defaultGet('Premanager.Widgets', 'sidebarAdmin');
		$page->createMainBlock('<p>'.Translation::defaultGet('Premanager.Widgets',
			'sidebarAdminMessage').'</p>');
		
		$template = new Template('Premanager.Widgets', 'sidebarAdmin');
		$template->set('widgetClasses', self::getList());
		$page->appendBlock(PageBlock::createSimple(
			Translation::defaultGet('Premanager.Widgets', 'sidebarAdmin'),
			$template->get()));
		$page->addStylesheet('Premanager.Widgets/stylesheets/stylesheet.css');
		
		return $page;
	} 

	/**
	 * Gets the list of viewonline sessions sorted by last request time
	 * 
	 * @return Premanager\QueryList\QueryList the list of sessions
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
}

?>
