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
 * A page that shows a list of all groups
 */
class GroupsPage extends TreeListPageNode {	
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
		if ($name == '+')
			return new AddGroupHomePage($this);
		if ($name == '-')
			return new ProjectGroupsPage($this, Project::getOrganization());
		else {
			$project = Project::getByName($name);
			if ($project)
			return new ProjectGroupsPage($this, $project);
		}
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
		$models = $this->getChildrenHelper(self::getProjectList(), $referenceModel,
			$count);
			
		$list = array();
		foreach ($models as $model) {
			$list[] = new ProjectGroupsPage($this, $model);
		}
		return $list;
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
		$page->title = Translation::defaultGet('Premanager', 'groups');
		$page->createMainBlock(Translation::defaultGet('Premanager',
			count($list) ? 'groupListMessage' : 'groupListEmpty'));
		
		$template = new Template('Premanager', 'groupListHead');
		$head = $template->get();
		
		$template = new Template('Premanager', 'groupListBody');
		$template->set('groups', $list);
		$template->set('node', $this);
		$body = $template->get();
		
		$page->appendBlock(PageBlock::createTable($head, $body));
		
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
		return self::getList()->getCount();
	}
	
	/**
	 * Gets the list of groups sorted by project and name
	 * 
	 * @return Premanager\QueryList\QueryList the list of groups
	 */
	private static function getList() {
		static $cache;
		if (!$cache) {
			$cache = Group::getGroups();
			$cache = $cache->sort(array(
				new SortRule($cache->exprMember('project')),
				new SortRule($cache->exprMember('name'))));
		}
		return $cache;
	}
	
	/**
	 * Gets the list of projects sorted by name
	 * 
	 * @return Premanager\QueryList\QueryList the list of projects
	 */
	private static function getProjectList() {
		static $cache;
		if (!$cache) {
			$cache = Project::getProjects();
			$cache = $cache->sort(array(new SortRule($cache->exprMember('name'))));
		}
		return $cache;
	}
}

?>
