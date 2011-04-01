<?php
namespace Premanager\Pages;

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
 * A page that shows information about a project
 */
class ProjectPage extends PageNode {
	/**
	 * @var Premanager\Models\Project
	 */
	private $_project;

	// ===========================================================================
	
	/**
	 * Creates a new ProjectPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\Project $project the displayed project
	 */
	public function __construct($parent, Project $project) {
		parent::__construct($parent);
		
		$this->_project = $project;
	}

	// ===========================================================================
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
		if ($name == 'edit')
			return new EditProjectPage($this, $this->_project);
		if ($name == 'delete' && $this->_project->getID())
			return new DeleteProjectPage($this, $this->_project);
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
		
		$page->toolbar[] = new ToolBarItem($this->getURL().'/edit',
			Translation::defaultGet('Premanager', 'editProject'), 
			Translation::defaultGet('Premanager', 'editProjectDescription'),
			'Premanager/images/tools/edit.png');
		
		if ($this->_project->getID())
			$page->toolbar[] = new ToolBarItem($this->getURL().'/delete',
				Translation::defaultGet('Premanager', 'deleteProject'), 
				Translation::defaultGet('Premanager', 'deleteProjectDescription'),
				'Premanager/images/tools/delete.png');
			
		return $page;
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof ProjectPage &&
			$other->_project == $this->_project; 
	}	    
}

?>
