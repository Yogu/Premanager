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
 * A page that allows to edit an existing project
 */
class EditProjectPage extends ProjectFormPage {	
	private $_project;

	// ===========================================================================
	
	/**
	 * Creates a new EditProjectPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\Project $project the project to edit
	 */
	public function __construct($parent, Project $project) {
		parent::__construct($parent, $project);

		$this->_project = $project;
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
		return Translation::defaultGet('Premanager', 'editProject');
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
		
		$this->_project->setValues($values['name'], $values['title'],
			$values['subTitle'], $values['author'], $values['copyright'],
			$values['description'], $values['keywords']);
		return new Redirection($this->getParent()->getURL());
	}
	
	/**
	 * Gets the values for a form without POST data
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		return array(
			'name' => $this->_project->getName(),
			'title' => $this->_project->getTitle(),
			'subTitle' => $this->_project->getSubTitle(),
			'author' => $this->_project->getAuthor(),
			'copyright' => $this->_project->getCopyright(),
			'description' => $this->_project->getDescription(),
			'keywords' => $this->_project->getKeywords());
	}
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected function getTemplate() {
		$template = parent::getTemplate();
		$template->set('project', $this->_project);
		return $template;
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof EditProjectPage &&
			$other->_project == $this->_project; 
	}	    
}

?>
