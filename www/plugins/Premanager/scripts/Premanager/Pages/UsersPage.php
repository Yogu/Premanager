<?php
namespace Premanager\Pages;

use Premanager\Execution\TreeListPageNode;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Translation;
use Premanager\Execution\Page;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\Models\User;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\Execution\ListPageNode;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page that shows a list of all users
 */
class UsersPage extends TreeListPageNode {
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
		$user = User::getByName($name);
		if ($user)
			return new UserPage($this->parent, $user);
	}
	
	/**
	 * Gets the title that is used in a context where the titles of the parent
	 * nodes are not displayed
	 * 
	 * @return string
	 */
	public function getStandAloneTitle() {
		return Translation::defaultGet('Premanager', 'users');
	}
	
	/**
	 * Creates a page object that covers the data of this page node
	 * 
	 * @return Premanager\Execution\Page the page or null, if this page node does
	 *   not result in a page. 
	 */
	public function getPage() {
		$list = User::getUsers()->getRange($this->startIndex, $this->itemsPerPage,
			true);
		
		$page = new Page($this->parent);
		$page->createMainBlock(Translation::defaultGet('Premanager',
			count($list) ? 'userListMessage' : 'userListEmpty'));
		
		$template = new Template('Premanager', 'userListHead');
		$head = $template->get();
		
		$template = new Template('Premanager', 'userListBody');
		$template->set('users', User::getUsers());
		$template->set('node', $this);
		$body = $template->get();
		
		$page->appendBlock(PageBlock::createTable($head, $body));
		return $page;
	}

	/**
	 * Performs a call of this page
	 */
	public function execute() {
		Output::select($this->getPage());
	} 
	
	/**
	 * Counts the items
	 * 
	 * @return int
	 */
	protected function countItems() {
		return User::getUsers()->count;
	}
}

?>
