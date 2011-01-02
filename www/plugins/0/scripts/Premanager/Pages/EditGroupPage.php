<?php
namespace Premanager\Pages;

use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Execution\Redirection;
use Premanager\Execution\ToolBarItem;
use Premanager\Models\Group;
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
 * A page that allows to edit an existing group
 */
class EditGroupPage extends GroupFormPage {	
	private $_group;

	// ===========================================================================
	
	/**
	 * Creates a new EditGroupPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\Group $group the group to edit
	 */
	public function __construct($parent, Group $group) {
		parent::__construct($parent, $group);

		$this->_group = $group;
	} 

	// ===========================================================================
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return 'edit';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'editGroup');
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
		if (!Rights::requireRight(Right::getByName('Premanager', 'manageGroups'),
			$this->_group->getProject(), $errorResponse))
			return $errorResponse;
			
		$this->_group->setValues($values['name'], $values['title'],
			$values['color'], $values['text'], $values['priority'],
			$values['autoJoin'], $values['loginConfirmationRequired']);
		return new Redirection($this->getParent()->getURL());
	}
	
	/**
	 * Gets the values for a form without POST data
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		return array(
			'name' => $this->_group->getName(),
			'title' => $this->_group->getTitle(),
			'color' => $this->_group->getColor(),
			'text' => $this->_group->getText(),
			'priority' => $this->_group->getPriority(),
			'autoJoin' => $this->_group->getAutoJoin(),
			'loginConfirmationRequired' =>
				$this->_group->getLoginConfirmationRequired());
	}
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected function getTemplate() {
		$template = parent::getTemplate();
		$template->set('group', $this->_group);
		return $template;
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof EditGroupPage &&
			$other->_group == $this->_group; 
	}	    
}

?>
