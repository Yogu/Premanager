<?php
namespace Premanager\Pages;

use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Execution\Redirection;
use Premanager\Execution\ToolBarItem;
use Premanager\Models\User;
use Premanager\Premanager;
use Premanager\Execution\TreeListPageNode;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Translation;
use Premanager\Execution\Page;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\Models\Group;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\Execution\ListPageNode;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page that asks whether to delete a user
 */
class DeleteUserPage extends PageNode {	
	private $_user;

	// ===========================================================================
	
	/**
	 * Creates a new DeleteUserPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\User $user the user to delete
	 */
	public function __construct($parent, User $user) {
		if (!$user->getID())
			throw new ArgumentException('The guest can not be deleted');
		
		parent::__construct($parent);

		$this->_user = $user;
	} 

	// ===========================================================================
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return 'delete';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'deleteUser');
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		if (Request::getPOST('confirm')) {
			if (!Rights::requireRight(Right::getByName('Premanager', 'deleteUsers'),
				null, $errorResponse))
				return $errorResponse;
			
			$this->_user->delete();
			return new Redirection($this->getParent()->getParent()->getURL());
		} else {
			$page = new Page($this);
			$template = new Template('Premanager', 'confirmation');
			$template->set('message', Translation::defaultGet('Premanager',
				'deleteUserMessage', array('name' => $this->_user->getName(),
				'url' => $this->getParent()->getURL())));
			$page->createMainBlock($template->get());
			return $page;
		}
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof DeleteUserPage &&
			$other->_user == $this->_user; 
	}	    
}

?>
