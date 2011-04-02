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
class SidebarAdminPage extends SidebarPage {
	/**
	 * Creates a new SidebarAdminPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\StructureNode $structureNode the structure node
	 *   this page node is embedded in
	 */
	public function __construct($parent, StructureNode $structureNode) {
		parent::__construct($parent, $structureNode, Sidebar::getDefault());
	}
	
	// ===========================================================================
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$response = parent::getResponse(
			Right::getByName('Premanager.Widgets', 'editDefaultSidebar'));
		if ($response)
			return $response;
			
		$page = new Page($this);
		$page->addStylesheet('Premanager.Widgets/stylesheets/stylesheet.css');
		$page->title =
			Translation::defaultGet('Premanager.Widgets', 'sidebarAdmin');
		$page->createMainBlock('<p>'.Translation::defaultGet('Premanager.Widgets',
			'sidebarAdminMessage').'</p>');
		$page->appendBlock(parent::getWidgetClassesBlock());
		return $page;
	} 
}

?>
