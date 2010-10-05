<?php
namespace Premanager\Pages;

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
 * A page that shows a list of all users
 */
class UsersPage extends ListPageNode {
	/**
	 * @var Premanager\Models\StructureNode
	 */
	private $_structureNode;
	
	/**
	 * Creates a new UsersPage
	 * 
	 * @param Premanager\Execution\PageNode $parent the parent node
	 * @param Premanager\Models\StructureNode $structureNode the structure node
	 *   this page node is embedded in
	 */
	public function __construct(PageNode $parent, StructureNode $structureNode) {
		parent::__construct($parent);
		$this->_structureNode = $structureNode;
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
			return new UserPage($this, $user);
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
		$users = $this->getChildrenHelper(User::getUsers(), $referenceModel,
			$count);
			
		$list = array();
		foreach ($users as $user) {
			$list[] = new UserPage($this, $user);
		}
		return $list;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_structureNode->name;
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->_structureNode->title;
	}

	/**
	 * Performs a call of this page
	 */
	public function execute() {
		$list = User::getUsers()->getRange($this->startIndex, $this->itemsPerPage,
			true);
		
		$page = new Page($this);
		$page->createMainBlock(Translation::defaultGet('Premanager',
			count($list) ? 'userListMessage' : 'userListEmpty'));
		
		$template = new Template('Premanager', 'userListHead');
		$head = $template->get();
		
		$template = new Template('Premanager', 'userListBody');
		$template->set('users', User::getUsers());
		$template->set('node', $this);
		$body = $template->get();
		
		$page->appendBlock(PageBlock::createTable($head, $body));
		Output::select($page);
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof UsersPage &&
			$other->_structureNode == $this->_structureNode; 
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
