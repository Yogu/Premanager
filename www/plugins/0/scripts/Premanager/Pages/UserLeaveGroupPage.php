<?php
namespace Premanager\Pages;

use Premanager\Models\Right;

use Premanager\Execution\Rights;

use Premanager\Execution\Redirection;
use Premanager\Types;
use Premanager\Execution\FormPageNode;
use Premanager\Execution\ToolBarItem;
use Premanager\Models\Project;
use Premanager\Models\Group;
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
 * A page that allows to remove a user from a group
 */
class UserLeaveGroupPage extends PageNode {
	/**
	 * @var Premanager\Models\User
	 */
	private $_user;

	// ===========================================================================
	
	/**
	 * Creates a new UserJoinGrouPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\User $parent the user to select
	 */
	public function __construct($parent, User $user) {
		parent::__construct($parent);
		
		$this->_user = $user;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return 'leave-group';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'userLeaveGroup');
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof UserLeaveGroupPage &&
			$other->_user == $this->_user;
	}	    

	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		foreach (Request::getPOSTValues() as $key => $value) {
			if (substr($key, 0, 12) == 'leave-group-') {
				$id = substr($key, 12);
				break;
			}
		}
		if (Types::isInteger($id) && $id > 0)
			$group = Group::getByID($id);
		if ($group) {
			$right = Right::getByName('Premanager', 'manageGroupMemberships');
			if ($group->getProject()->getID())
				$right = array($right, Right::getByName('Premanager',
					'manageGroupMembershipsOfProjectMembers'));
				
			if (!Rights::requireRight($right, $group->getProject(), $errorResponse))
				return $errorResponse;
			
			$this->_user->leaveGroup($group);
		}
		return new Redirection($this->getParent()->getURL());
	}

	// ===========================================================================
	
	/**
	 * Creates the form page based on the form's HTML
	 * 
	 * @param string $formHTML the form's HTML
	 * @return Premanager\Execution\Response the response object to send
	 */
	protected function getFormPage($formHTML) {
		$message = Translation::defaultGet('Premanager',
			'userJoinGroupMessage', array('name' => $this->_user->getName()));
		
		$page = new Page($this);
		$page->title = Translation::defaultGet('Premanager', 'userJoinGroup');
		$page->createMainBlock("<p>$message</p>\n$formHTML");
		return $page;
	}
	
	/**
	 * Loads the values from POST parameters and validates them
	 * 
	 * The $errors parameter should be an array of following:
	 *   array(
	 *     fieldName - the name of the field that is invalid
	 *     errorMessage - a description of the error
	 *   )
	 * 
	 * @param array &$errors an array of errors as described above
	 * @return array the array of values
	 */
	protected function getValuesFromPOST(array &$errors) {
		$selected = Request::getPOST('groups');
		$values = array();
		if (is_array($selected)) {
			foreach ($selected as $groupID) {
				if (Types::isInteger($groupID) && $groupID > 0) {
					$group = Group::getByID($groupID);
					if ($group) {
						if ($this->_user->isInGroup($group))
							$errors[] = array('groups',
							 Translation::defaultGet('Premanager', 'userJoinAlreadyMemberError',
							  array('groupName' => $group->getName(),
							  'projectTitle' => $group->getProject()->getTitle())));
						$values[$groupID] = true;
					}
				}
			}
		}
		if (!count($values) && !count($errors))
			$errors[] = array('groups',
				Translation::defaultGet('Premanager', 'userJoinNoGroupSelectedError'));
		return array('groups' => $values);
	}
	
	/**
	 * Gets the values for a form without POST data or model
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		return array('groups' => array());
	}
	
	/**
	 * Applies the values and gets the response
	 * 
	 * Is called when the form is submitted and validated. 
	 * 
	 * @param array $values the array of values
	 * @return Premanager\Execution\Response the response to send
	 */
	protected function applyValues(array $values) {
		foreach ($values['groups'] as $groupID => $value) {
			$group = Group::getByID($groupID);
			$this->_user->joinGroup($group);
		}
		
		return new Redirection($this->getParent()->getURL());
	}
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected function getTemplate() {
		$list = self::getList();
		$options = array();
		foreach ($list as $group) {
			if ($group->getProject()->getTitle() !== $currentProject) {
				$currentProject = $group->getProject()->getTitle(); 
				$options[$currentProject] = array();
			}
			$options[$currentProject][$group->getID()] = $group->getName();
		}
		
		$template = new Template('Premanager', 'userJoinGroupForm');
		$template->set('groups', $options);
		$template->set('node', $this);
		return $template;
	}
}

?>
