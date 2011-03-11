<?php
namespace Premanager\Widgets\Pages;

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
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$list = self::getList();
		
		$page = new Page($this);
		$page->title =
			Translation::defaultGet('Premanager.Widgets', 'sidebarAdmin');
		$page->createMainBlock(Translation::defaultGet('Premanager.Widgets',
			'sidebarAdminMessage'));
		
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
