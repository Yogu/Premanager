<?php
namespace Premanager\Pages;

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
 * A page that shows information about a project
 */
class ProjectPage extends PageNode {
	/**
	 * @var Premanager\Models\Project
	 */
	private $_project;

	// ===========================================================================
	
	/**
	 * Creates a new page node
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\Group $parent the group to view
	 */
	public function __construct($parent, Project $project) {
		parent::__construct($parent);
		
		$this->_project = $project;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_project->getID() ? $this->_project->getName() : '-';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->_project->getTitle();
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$page = new Page($this);
		
		$template = new Template('Premanager', 'projectView');
		$template->set('node', $this);
		$template->set('project', $this->_project);
		
		$page->createMainBlock($template->get());
		return $page;
	}

	// ===========================================================================

	/**
	 * Gets the project represented by this page
	 * 
	 * @return Premanager\Models\Project the project represented by this page
	 */
	public function getProject() {
		return $this->_project;
	}
}

?>
