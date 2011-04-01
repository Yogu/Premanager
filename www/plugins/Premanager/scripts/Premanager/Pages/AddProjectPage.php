<?php
namespace Premanager\Pages;

use Premanager\Execution\Redirection;
use Premanager\Execution\ToolBarItem;
use Premanager\Models\Project;
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
 * A page that allows to add a new project
 */
class AddProjectPage extends ProjectFormPage {	
	
	/**
	 * Creates a new EditProjectPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 */
	public function __construct($parent) {
		parent::__construct($parent, null); // not editing a project
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
		return Translation::defaultGet('Premanager', 'addProject');
	}
	
	/**
	 * Gets the values for a form without POST data
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		return array();
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
		if (!Rights::requireRight(Right::getByName('Premanager', 'manageProjects'),
			null, $errorResponse))
			return $errorResponse;
		
		$project = Project::createNew($values['name'], $values['title'],
			$values['subTitle'], $values['author'], $values['copyright'],
			$values['description'], $values['keywords']);
		return new Redirection(
			$this->getParent()->getURL() . '/' . $project->getName());
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof AddProjectPage;
	}	    
}

?>
