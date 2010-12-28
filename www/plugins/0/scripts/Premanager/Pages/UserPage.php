<?php
namespace Premanager\Pages;

use Premanager\Execution\ToolBarItem;
use Premanager\Premanager;
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
 * A page that shows a user profile
 */
class UserPage extends PageNode {
	/**
	 * @var Premanager\Models\User
	 */
	private $_user;

	// ===========================================================================
	
	/**
	 * Creates a new page node
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\User $parent the user to view
	 */
	public function __construct($parent, User $user) {
		parent::__construct($parent);
		
		$this->_user = $user;
	}
	
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
		if ($name == 'edit')
			return new EditUserPage($this, $this->_user);
		if ($name == 'delete' && $this->_user->getID())
			return new DeleteUserPage($this, $this->_user);
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
		$list = array();
		$list[] = new EditUserPage($this, $this->_user);
		if ($this->_user->getID())
			$list[] = new DeleteUserPage($this, $this->_user);
		return $list;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_user->getName();
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->_user->getName();
	}

	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$page = new Page($this);
		
		$template = new Template('Premanager', 'userView');
		$template->set('node', $this);
		$template->set('user', $this->_user);
		
		$page->createMainBlock($template->get());
		
		$page->toolbar[] = new ToolBarItem($this->getURL().'/edit',
			Translation::defaultGet('Premanager', 'editUser'), 
			Translation::defaultGet('Premanager', 'editUserDescription'),
			'Premanager/images/tools/edit.png');
		
		if ($this->_user->getID())
			$page->toolbar[] = new ToolBarItem($this->getURL().'/delete',
				Translation::defaultGet('Premanager', 'deleteUser'), 
				Translation::defaultGet('Premanager', 'deleteUserDescription'),
				'Premanager/images/tools/delete.png');
			
		return $page;
	}

	/**
	 * Gets the user represented by this page
	 * 
	 * @return Premanager\Models\User the user represented by this page
	 */
	public function getUser() {
		return $this->_user;
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof UserPage &&
			$other->_user == $this->_user; 
	}	    
}

?>
