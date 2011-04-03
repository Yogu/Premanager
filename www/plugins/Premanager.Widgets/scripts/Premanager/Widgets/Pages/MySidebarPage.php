<?php
namespace Premanager\Widgets\Pages;

use Premanager\Execution\Environment;

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
 * A page that allows to edit the own sidebar
 */
class MySidebarPage extends SidebarPage {
	/**
	 * Creates a new SidebarAdminPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\StructureNode $structureNode the structure node
	 *   this page node is embedded in
	 */
	public function __construct($parent, StructureNode $structureNode) {
		parent::__construct($parent, $structureNode, 
			Sidebar::get(Environment::getCurrent()->getUser()));
	}
	
	// ===========================================================================
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		if (!$this->getSidebar()->getUser())
			return Page::createMessagePage($this, Translation::defaultGet(
				'Premanager.Widgets', 'guestEditsSidebarMessage'));
			
		if (Request::getPOST('confirm')) {	
			$this->getSidebar()->setIsExisting(false);
			return new Redirection($this->getURL());
		} else if (Request::getPOST('cancel')) {
			return new Redirection($this->getURL());
		} else if (Request::getPOST('reset')) {
			$page = new Page($this);
			$template = new Template('Premanager', 'confirmation');
			$template->set('message', Translation::defaultGet('Premanager.Widgets',
				'resetMySidebarConfirmation'));
			$page->createMainBlock($template->get());
			return $page;
		}
		
		$response = parent::getResponse();
		if ($response)
			return $response;
			
		$page = new Page($this);
		$page->addStylesheet('Premanager.Widgets/stylesheets/stylesheet.css');
		$page->title =
			Translation::defaultGet('Premanager.Widgets', 'mySidebar');
		$template = new Template('Premanager.Widgets', 'mySidebar');
		$template->set('sidebar', $this->getSidebar());
		$page->createMainBlock($template->get());
		$page->appendBlock(parent::getWidgetClassesBlock());
		return $page;
	} 
}

?>
