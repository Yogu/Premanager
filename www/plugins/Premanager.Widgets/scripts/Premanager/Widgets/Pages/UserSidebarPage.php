<?php
namespace Premanager\Widgets\Pages;

use Premanager\Pages\AddGroupHomePage;
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
class UserSidebarPage extends SidebarPage {
	private $_sidebarTranslation = 'userSidebar';
	private $_resetSidebarConfirmationTranslation = 'resetSidebarConfirmation';
	private $_templateName = 'userSidebar';
	private $_right;
	
	/**
	 * Creates a new SidebarAdminPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\User $user the user whose sidebar to be edited
	 */
	public function __construct($parent, User $user, $sidebarTranslation = null,
		$resetSidebarConfirmationTranslation = null, $templateName = null,
		$right = false)
	{
		parent::__construct($parent, Sidebar::get($user));
		if ($sidebarTranslation)
			$this->_sidebarTranslation = $sidebarTranslation;
		if ($resetSidebarConfirmationTranslation)
			$this->_resetSidebarConfirmationTranslation =
				$resetSidebarConfirmationTranslation;
		if ($templateName)
			$this->_templateName = $templateName;
		if ($right !== false)
			$this->_right = $right;
		else
			$this->_right = Right::getByName('Premanager.Widgets', 'editUserSidebars');
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
				'Premanager.Widgets', 'editingGuestsSidebarError'));
			
		if (Request::getPOST('confirm')) {	
			$this->getSidebar()->setIsExisting(false);
			return new Redirection($this->getURL());
		} else if (Request::getPOST('cancel')) {
			return new Redirection($this->getURL());
		} else if (Request::getPOST('reset')) {
			$page = new Page($this);
			$template = new Template('Premanager', 'confirmation');
			$template->set('message', Translation::defaultGet('Premanager.Widgets',
				$this->_resetSidebarConfirmationTranslation));
			$page->createMainBlock($template->get());
			return $page;
		}
		
		$response = parent::getResponse($this->_right);
		if ($response)
			return $response;
			
		$page = new Page($this);
		$page->addStylesheet('Premanager.Widgets/stylesheets/stylesheet.css');
		$page->title =
			Translation::defaultGet('Premanager.Widgets',
				$this->_sidebarTranslation);
		$template = new Template('Premanager.Widgets', $this->_templateName);
		$template->set('sidebar', $this->getSidebar());
		$page->createMainBlock($template->get());
		$page->appendBlock(parent::getWidgetClassesBlock());
		return $page;
	} 
	
	/**
	 * Gets an array of names and values of the query ('page' => 7 for '?page=7')
	 * 
	 * @return array
	 */
	public function getURLQuery() {
		$query = array();
		if (Request::getGET('user')) {
			$query['user'] = Request::getGET('user');
		}
		return $query;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->getSidebar()->getUser() ?
			 $this->getSidebar()->getUser()->getName() : '';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->getSidebar()->getUser() ?
			$this->getSidebar()->getUser()->getName() : '';
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof UserSidebarPage &&
			$other->getSidebar() == $this->getSidebar(); 
	}	    
}

?>
