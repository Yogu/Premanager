<?php
namespace Premanager\Pages;

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
		$group = Group::getByName($name);
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
		$models = $this->getChildrenHelper(self::getList(), $referenceModel,
			$count);
			
		$list = array();
		foreach ($models as $model) {
			$list[] = new GroupPage($this, $model);
		}
		return $list;
	}

	/**
	 * Performs a call of this page
	 */
	public function execute() {
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
		Output::select($page);
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
	 * Gets the list of groups sorted by name
	 * 
	 * @return Premanager\QueryList\QueryList the list of groups
	 */
	private static function getList() {
		static $cache;
		if (!$cache) {
			$cache = Group::getGroups();
			$cache = $cache->sort(array(new SortRule($cache->exprMember('name'))));
		}
		return $cache;
	}
}

?>
