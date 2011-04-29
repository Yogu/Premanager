<?php
namespace Premanager\Pages;

use Premanager\Modeling\SortDirection;
use Premanager\Modeling\SortRule;
use Premanager\Modeling\QueryExpression;
use Premanager\Execution\Environment;
use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Debug\Debug;
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
	/**
	 * @var Premanager\Modeling\QueryList
	 */
	private $_list;

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
		if ($name == 'avatar')
			return new UserAvatarPage($this, $this->_user);
		
		if (Rights::hasRight(Right::getByName('Premanager', 'editUsers'))) {
			if ($name == 'edit')
				return new EditUserPage($this, $this->_user);
			if ($name == 'change-avatar')
				return new UserChangeAvatarPage($this, $this->_user);
		}
		if ($name == 'delete' && $this->_user->getID() &&
			Rights::hasRight(Right::getByName('Premanager', 'deleteUsers')))
			return new DeleteUserPage($this, $this->_user);
		if ($name == 'join-group' && 
			(Rights::hasRightInAnyProject(Right::getByName(
				'Premanager', 'manageGroupMemberships')) ||
			Rights::hasRightInAnyProject(Right::getByName(
				'Premanager', 'manageGroupMembershipsOfProjectMembers'))))
			return new UserJoinGroupPage($this, $this->_user);
		if ($name == 'leave-group' &&  
			(Rights::hasRightInAnyProject(Right::getByName(
				'Premanager', 'manageGroupMemberships')) ||
			Rights::hasRightInAnyProject(Right::getByName(
				'Premanager', 'manageGroupMembershipsOfProjectMembers'))))
			return new UserLeaveGroupPage($this, $this->_user);
		if ($name == 'rights' &&
			Rights::hasRight(Right::getByName('Premanager', 'manageRights')))
			return new UserRightsPage($this, $this->_user);
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
		
		// Groups
		$groups = $this->getList();
		if (count($groups)) {
			$template = new Template('Premanager', 'userGroupsList');
			$template->set('groups', $groups);
			$template->set('node', $this);
			$page->appendBlock(PageBlock::createSimple(
				Translation::defaultGet('Premanager', 'userGroupList'),
				$template->get()));
		}
		
		if (Rights::hasRight(Right::getByName('Premanager', 'editUsers')))
			$page->toolbar[] = new ToolBarItem($this->getURL().'/edit',
				Translation::defaultGet('Premanager', 'editUser'), 
				Translation::defaultGet('Premanager', 'editUserDescription'),
				'Premanager/images/tools/edit.png');
		
		if ($this->_user->getID() &&
			Rights::hasRight(Right::getByName('Premanager', 'deleteUsers'))) {
			$enabled = $this->_user != Environment::getCurrent()->getUser();
			$description = $enabled ? 'deleteUserDescription' :
				'deleteOwnUserDescription';
			$page->toolbar[] = new ToolBarItem($this->getURL().'/delete',
				Translation::defaultGet('Premanager', 'deleteUser'), 
				Translation::defaultGet('Premanager', $description),
				'Premanager/images/tools/delete.png', $enabled);
		}
		
		if (Rights::hasRightInAnyProject(Right::getByName(
				'Premanager', 'manageGroupMemberships')) ||
			Rights::hasRightInAnyProject(Right::getByName(
				'Premanager', 'manageGroupMembershipsOfProjectMembers')))
			$page->toolbar[] = new ToolBarItem($this->getURL().'/join-group',
				Translation::defaultGet('Premanager', 'userJoinGroup'), 
				Translation::defaultGet('Premanager', 'userJoinGroupDescription'),
				'Premanager/images/tools/join-group.png');
		
		if (Rights::hasRightInAnyProject(
			Right::getByName('Premanager', 'manageRights')))
			$page->toolbar[] = new ToolBarItem($this->getURL().'/rights',
				Translation::defaultGet('Premanager', 'viewUserRights'), 
				Translation::defaultGet('Premanager', 'viewUserRightsDescription'),
				'Premanager/images/tools/rights.png');
				
		if (Rights::hasRight(Right::getByName('Premanager', 'editUsers')))
			$page->toolbar[] = new ToolBarItem($this->getURL().'/change-avatar',
				Translation::defaultGet('Premanager', 'changeAvatar'), 
				Translation::defaultGet('Premanager', 'changeAvatarDescription'),
				'Premanager/images/tools/avatar.png');
			
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
			$other->_user === $this->_user; 
	}	    
	
	private function getList() {
		if (!$this->_list) {
			$this->_list = $this->_user->getGroups();
			$this->_list = $this->_list->sort(array(
				new SortRule($this->_list->exprMember('project'))));
		}
		return $this->_list;
	}
}

?>
