<?php
namespace Premanager\Pages;

use Premanager\Debug\Debug;

use Premanager\Types;
use Premanager\Models\Group;
use Premanager\Execution\FormPageNode;
use Premanager\Models\StructureNodeType;
use Premanager\QueryList\SortRule;
use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Execution\Redirection;
use Premanager\Execution\ToolBarItem;
use Premanager\Models\StructureNode;
use Premanager\Premanager;
use Premanager\Execution\TreeListPageNode;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Translation;
use Premanager\Execution\Page;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\Execution\ListPageNode;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page to define who can access this page and who can't
 */
class StructureNodePermissionsPage extends FormPageNode {
	/**
	 * @var Premanager\Models\StructureNode
	 */
	private $_structureNode; 

	// ===========================================================================
	
	/**
	 * Creates a new StructureNodePermissionsPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\StructureNode $structureNode the structureNode to
	 *   edit
	 */
	public function __construct($parent, StructureNode $structureNode) {
		parent::__construct($parent);
		$this->_structureNode = $structureNode;
	} 

	// ===========================================================================
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return '+permissions';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'nodePermissions'); 
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof StructureNodePermissionsPage &&
			$other->_structureNode == $this->_structureNode; 
	}	    
	
	/**
	 * Gets the list of groups sorted by project and grou pname
	 * 
	 * @return Premanager\QueryList\QueryList the list of users
	 */
	private static function getList() {
		static $list;
		if (!$list) {
			$list = Group::getGroups();
			$list = $list->sort(array(
				new SortRule($list->exprMember('project')),
				new SortRule($list->exprMember('name'))));
		}
		return $list;
	}    

	// ===========================================================================
	
	/**
	 * Creates the form page based on the form's HTML
	 * 
	 * @param string $formHTML the form's HTML
	 * @return Premanager\Execution\Response the response object to send
	 */
	protected function getFormPage($formHTML) {
		if (Request::getPOST('cancel'))
			return new Redirection($this->getParent()->getURL());
			
		$message = Translation::defaultGet('Premanager', 'nodePermissionsMessage');
		
		$page = new Page($this);
		$page->title = Translation::defaultGet('Premanager', 'nodePermissions');
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
				if ($groupID == 0)
					$values[0] = true;
				else if (Types::isInteger($groupID) && $groupID > 0) {
					$group = Group::getByID($groupID);
					if ($group)
						$values[$groupID] = true;
				}
			}
		}
		
		return array('groups' => $values);
	}
	
	/**
	 * Gets the values for a form without POST data or model
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		$ids = array();
		if ($this->_structureNode->getNoAccessRestriction())
			$ids[0] = true;
		foreach ($this->_structureNode->getAuthorizedGroups() as $group) {
			$ids[$group->getID()] = true;
		}
		return array('groups' => $ids);
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
		if (!Rights::requireRight(Right::getByName('Premanager', 'structureAdmin'),
			$this->getProject(), $errorResponse))
			return $errorResponse;
		
		$groups = array();
		foreach ($values['groups'] as $groupID => $value)
			$groups[] = Group::getByID($groupID);
		$this->_structureNode->setAuthorizedGroups($groups);
		$this->_structureNode->setNoAccessRestriction(!!$values['groups'][0]);
		
		return new Redirection($this->getParent()->getURL());
	}
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected function getTemplate() {
		$list = self::getList();
		$options = array(0 => Translation::defaultGet('Premanager', 'everyone'));
		foreach ($list as $group) {
			if ($group->getProject()->getTitle() !== $currentProject) {
				$currentProject = $group->getProject()->getTitle(); 
				$options[$currentProject] = array();
			}
			$options[$currentProject][$group->getID()] = $group->getName();
		}
		
		$template = new Template('Premanager', 'structureNodePermissions');
		$template->set('groups', $options);
		$template->set('structureNode', $this->_structureNode);
		$template->set('node', $this);
		return $template;
	}
}

?>
