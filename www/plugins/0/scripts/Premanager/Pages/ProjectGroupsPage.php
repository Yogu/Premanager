<?php
namespace Premanager\Pages;

use Premanager\Execution\ToolBarItem;
use Premanager\Models\Project;
use Premanager\Debug\Debug;
use Premanager\QueryList\SortRule;
use Premanager\Execution\TreeListPageNode;
use Premanager\Models\StructureNode;
use Premanager\Execution\ListPageNode;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Translation;
use Premanager\Execution\Page;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\Models\Group;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page that shows a list of the groups of a project
 */
class ProjectGroupsPage extends ListPageNode {
	private $_project;

	// ===========================================================================
	
	/**
	 * Creates a new ProjectGroupsPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\Projet $project the project whose groups to show
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
		if ($name == '+')
			return new AddGroupPage($this, $this->_project);
		$group = Group::getByName($this->_project, $name);
		if ($group)
			return new GroupPage($this, $group);
	}
	
	/**
	 * Gets an array of all child page nodes
	 * 
	 * @param int $count the number of items the array should contain at most or
	 *   -1 if all available items should be contained
	 * @param Premanager\Execution\PageNode $referenceNode the page node that
	 *   should be always in the array
	 * @return array an array of the child Premanager\Execution\PageNode's
	 */
	public function getChildren($count = -1, PageNode $referenceNode = null) {
		$referenceModel = $referenceNode instanceof GroupPage ?
			$referenceNode->getGroup() : null;
		$models = $this->getChildrenHelper($this->getList(), $referenceModel,
			$count);
			
		$list = array();
		foreach ($models as $model) {
			$list[] = new GroupPage($this, $model);
		}
		return $list;
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
		$list = self::getList()->getRange($this->getStartIndex(),
			$this->getItemsPerPage(), true);
		
		$page = new Page($this);
		$page->title = Translation::defaultGet('Premanager', 'projectGroupsTitle', 
			array('projectTitle' => $this->_project->getTitle()));
		
		if ($this->_project->getID()) {
			if (count($list))
				$message = Translation::defaultGet('Premanager', 'projectGroupsMessage',
					array('projectTitle' => $this->_project->getTitle()));
			else
				$message =
					Translation::defaultGet('Premanager', 'projectGroupsEmptyMessage',
					array('projectTitle' => $this->_project->getTitle()));
		} else {
			if (count($list))
				$message =
					Translation::defaultGet('Premanager', 'organizationGroupsMessage');
			else
				$message = Translation::defaultGet('Premanager', 
					'organizationGroupsEmptyMessage');
		}
		
		$page->createMainBlock($message);
		
		if (count($list)) {
			$template = new Template('Premanager', 'groupListHead');
			$head = $template->get();
			
			$template = new Template('Premanager', 'groupListBody');
			$template->set('groups', $list);
			$template->set('node', $this);
			$template->set('isProjectView', true); // no group headers
			$body = $template->get();
			
			$page->appendBlock(PageBlock::createTable($head, $body));
		}
		
		$page->toolbar[] = new ToolBarItem($this->getURL().'/+',
			Translation::defaultGet('Premanager', 'addGroup'), 
			Translation::defaultGet('Premanager', 'addGroupDescription'),
			'Premanager/images/tools/add-group.png');
		
		return $page;
	} 
	
	/**
	 * Counts the items
	 * 
	 * @return int
	 */
	protected function countItems() {
		return self::getList()->getcount();
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof ProjectGroupsPage &&
			$other->_project == $this->_project; 
	}	   

	// ===========================================================================
	
	/**
	 * Gets the list of groups sorted by name
	 * 
	 * @return Premanager\QueryList\QueryList the list of groups
	 */
	private function getList() {
		static $cache;
		if (!$cache)
			$cache = array();
			
		if (!$cache[$this->_project->getID()]) {
			$list = Group::getGroups();
			$list = $list->filter(
				$list->exprEqual(
					$list->exprMember('project'),
					$this->_project));
			$cache[$this->_project->getID()] = $list;
		}
		return $cache[$this->_project->getID()];
	}
}

?>
