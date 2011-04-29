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
use Premanager\Modeling\SortDirection;
use Premanager\Modeling\QueryOperation;
use Premanager\Modeling\SortRule;
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
	private $_structureNode;
	
	/**
	 * Creates a new SidebarAdminPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\StructureNode $structureNode the structure node
	 *   this page node is embedded in
	 */
	public function __construct($parent, StructureNode $structureNode) {
		$this->_structureNode = $structureNode;
		
		if (Request::getGET('user'))
			$user = User::getByName(Request::getGET('user'));
		if ($user && $user->getID())
			$sidebar = Sidebar::get($user);
		else
			$sidebar = Sidebar::getDefault();
		parent::__construct($parent, $sidebar);
	}
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
		$user = User::getByName($name);
		if ($user)
			return new UserSidebarPage($this, $user);
	}
	
	/**
	 * Gets an array of all child page nodes
	 * 
	 * @param int $count the number of items the array should contain at most or
	 *   -1 if all available items should be contained
	 * @param Premanager\Execution\PageNode $referenceNode the page node that
	 *   should be always in the array
	 * @return array an array of the child Premanager\Execution\PageNode's
	 */
	public function getChildren($count = -1, PageNode $referenceNode = null) {
		$referenceModel = $referenceNode instanceof UserPage ?
			$referenceNode->getUser() : null;
		$users = User::getUsers();
		$users = $users->sort(array(new SortRule($users->exprMember('name'))));
		$models = $this->getChildrenHelper($users, $referenceModel,
			$count);
			
		$list = array();
		foreach ($users as $user) {
			if ($user->getID())
				$list[] = new UserSidebarPage($this, $user);
		}
		return $list;
	}
	
	// ===========================================================================
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$right = $this->getSidebar()->getUser() ? 'editUserSidebars' :
			'editDefaultSidebar';
		$response = parent::getResponse(
			Right::getByName('Premanager.Widgets', $right));
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
		return $this->_structureNode->getname();
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->_structureNode->gettitle();
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof SidebarAdminPage &&
			$other->_structureNode === $this->_structureNode; 
	}	 
}

?>
