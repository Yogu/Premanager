<?php
namespace Premanager\Pages;

use Premanager\Models\Right;

use Premanager\Execution\Rights;

use Premanager\Execution\ToolBarItem;
use Premanager\Models\Project;
use Premanager\Debug\Debug;
use Premanager\QueryList\SortDirection;
use Premanager\QueryList\QueryOperation;
use Premanager\QueryList\SortRule;
use Premanager\Execution\TreeListPageNode;
use Premanager\Models\StructureNode;
use Premanager\Execution\ListPageNode;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Translation;
use Premanager\Execution\Page;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\Models\User;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page that shows a list of all projects
 */
class ProjectsPage extends TreeListPageNode {
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
		if (!Rights::hasRight(Right::getByName('Premanager', 'manageProjects')))
			return;
		
		if ($name == '+')
			return new AddProjectPage($this);
		if ($name == '-')
			return new ProjectPage($this, Project::getOrganization());
		$project = Project::getByName($name);
		if ($project)
			return new ProjectPage($this, $project);
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
		if (!Rights::hasRight(Right::getByName('Premanager', 'manageProjects')))
			return array();
		
		$referenceModel = $referenceNode instanceof ProjectPage ?
			$referenceNode->getProject() : null;
		$models = $this->getChildrenHelper(self::getList(), $referenceModel,
			$count);
			
		$list = array();
		foreach ($models as $model) {
			$list[] = new ProjectPage($this, $model);
		}
		return $list;
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		if (!Rights::requireRight(Right::getByName('Premanager', 'manageProjects'),
			null, $errorResponse, false))
			return $errorResponse;
		
		$list = self::getList()->getRange($this->getStartIndex(),
			$this->getItemsPerPage(), true);
		
		$page = new Page($this);
		$page->title = Translation::defaultGet('Premanager', 'projects');
		$page->createMainBlock(Translation::defaultGet('Premanager',
			count($list) ? 'projectListMessage' : 'projectListEmpty'));
		
		$template = new Template('Premanager', 'projectListHead');
		$head = $template->get();
		
		$template = new Template('Premanager', 'projectListBody');
		$template->set('list', $list);
		$template->set('node', $this);
		$body = $template->get();
		
		$page->appendBlock(PageBlock::createTable($head, $body));
		
		$page->toolbar[] = new ToolBarItem($this->getURL().'/+',
			Translation::defaultGet('Premanager', 'addProject'), 
			Translation::defaultGet('Premanager', 'addProjectDescription'),
			'Premanager/images/tools/add-project.png');
		
		return $page;
	} 
	
	/**
	 * Counts the items
	 * 
	 * @return int
	 */
	protected function countItems() {
		if (!Rights::hasRight(Right::getByName('Premanager', 'manageProjects')))
			return 0;
		
		return self::getList()->getCount();
	}
	
	/**
	 * Gets the list of projects sorted by title
	 * 
	 * @return Premanager\QueryList\QueryList the list of users
	 */
	private static function getList() {
		static $list;
		if (!$list) {
			$list = Project::getProjects();
			$list = $list->sort(array(
				new SortRule($list->exprEqual($list->exprMember('id'), 0),
					SortDirection::DESCENDING),
				new SortRule($list->exprMember('title'))));
		}
		return $list;
	}
}

?>
