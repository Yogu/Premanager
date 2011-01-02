<?php
namespace Premanager\Pages;

use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Debug\Debug;
use Premanager\Models\Project;
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
 * A page that allows to add a new group
 */
class AddGroupPage extends GroupFormPage {	
	private $_project;
	
	/**
	 * Creates a new EditProjectPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\Project $project the project that should contain
	 *   the new group
	 */
	public function __construct($parent, Project $project) {
		parent::__construct($parent, null, $project); // not editing a group
		$this->_project = $project;
	} 

	// ===========================================================================
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return '+';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'addGroup');
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
			$this->_project, $errorResponse))
			return $errorResponse;
		
		$group = Group::createNew($values['name'], $values['title'],
			$values['color'], $values['text'], $values['priority'],
			$this->_project, $values['autoJoin']);
		return new Redirection(
			$this->getParent()->getURL() . '/' . $group->getName());
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof AddGroupPage;
	}	    
}

?>
