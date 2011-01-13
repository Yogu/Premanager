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
 * A page that asks whether to delete a project
 */
class DeleteProjectPage extends PageNode {	
	private $_project;

	// ===========================================================================
	
	/**
	 * Creates a new DeleteProjectPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\Project $project the project to delete
	 */
	public function __construct($parent, Project $project) {
		if (!$project->getID())
			throw new ArgumentException('The organization can not be deleted');
		
		parent::__construct($parent);

		$this->_project = $project;
	} 

	// ===========================================================================
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return 'delete';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'deleteProject');
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		if (Request::getPOST('confirm')) {	
			if (!Rights::requireRight(Right::getByName('Premanager', 'manageProjects'),
				null, $errorResponse))
				return $errorResponse;
			
			$this->_project->delete();
			return new Redirection($this->getParent()->getParent()->getURL());
		} else if (Request::getPOST('cancel')) {
			return new Redirection($this->getParent()->getURL());
		} else {
			$page = new Page($this);
			$template = new Template('Premanager', 'confirmation');
			$template->set('message', Translation::defaultGet('Premanager',
				'deleteProjectMessage', array('title' => $this->_project->getTitle(),
				'url' => $this->getParent()->getURL())));
			$page->createMainBlock($template->get());
			return $page;
		}
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof DeleteProjectPage &&
			$other->_project == $this->_project; 
	}	    
}

?>
