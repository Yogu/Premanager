<?php
namespace Premanager\Pages;

use Premanager\Execution\Rights;
use Premanager\Models\Scope;
use Premanager\Models\Right;
use Premanager\Execution\Redirection;
use Premanager\Types;
use Premanager\Execution\FormPageNode;
use Premanager\Execution\ToolBarItem;
use Premanager\Models\Project;
use Premanager\Models\Group;
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
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page that allows to manage the rights of a group
 */
class GroupRightsPage extends FormPageNode {
	/**
	 * @var Premanager\Models\Group
	 */
	private $_group;

	// ===========================================================================
	
	/**
	 * Creates a new GroupRightsPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\Group $parent the group to select
	 */
	public function __construct($parent, Group $group) {
		parent::__construct($parent);
		
		$this->_group = $group;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return 'rights';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'editGroupRights');
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof GroupRightsPage && $other->_group === $this->_group;
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
			'editGroupRightsMessage');
		
		$page = new Page($this);
		$page->title = Translation::defaultGet('Premanager', 'editGroupRights');
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
		return Request::getPOSTValues();
	}
	
	/**
	 * Gets the values for a form without POST data or model
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		$rights = array();
		foreach ($this->_group->getRights() as $right) {
			$rights[$right->getID()] = true;
		}
		return $rights;
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
		if (!Rights::requireRight(Right::getByName('Premanager', 'manageRights'),
			$this->_group->getProject(), $errorResponse))
			return $errorResponse;
				
		$rights = array();
		foreach ($values as $rightID => $foo) {
			if (Types::isInteger($rightID)) {
				$right = Right::getByID($rightID);
				if ($right) {
					$isOrganization = !$this->_group->getProject()->getID();
					// organizations can have project rights. Grants right to all projects.
					if ($isOrganization || $right->getScope() != Scope::ORGANIZATION)
						$rights[] = $right;
				}
			}
		}
		$this->_group->setRights($rights);
		
		return new Redirection($this->getParent()->getURL());
	}
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected function getTemplate() {
		$rights = array();
		foreach (Right::getRights() as $right) {
			$isOrganization = !$this->_group->getProject()->getID();
			// organizations can have project rights. Grants right to all projects.
			if ($isOrganization || $right->getScope() != Scope::ORGANIZATION)
				$rights[] = $right;
		}
		
		$template = new Template('Premanager', 'groupRightsForm');
		$template->set('group', $this->_group);
		$template->set('rights', $rights);
		$template->set('node', $this);
		return $template;
	}
}

?>
