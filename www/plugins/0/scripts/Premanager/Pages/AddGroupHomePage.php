<?php
namespace Premanager\Pages;

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
 * A page that allows the user to add a group displaying a list of all projects
 */
class AddGroupHomePage extends ListPageNode {
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$list = self::getList()->getRange($this->getStartIndex(),
			$this->getItemsPerPage(), true);
		
		$page = new Page($this);
		$page->title = Translation::defaultGet('Premanager', 'addGroup');
		$page->createMainBlock(
			Translation::defaultGet('Premanager', 'addGroupProjectListMessage'));
		
		$template = new Template('Premanager', 'addGroupProjectList');
		$template->set('list', $list);
		$template->set('node', $this);
		
		$page->appendBlock(PageBlock::createSimple(
			Translation::defaultGet('Premanager', 'projects'), $template->get()));
		
		return $page;
	} 
	
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
	 * Counts the items
	 * 
	 * @return int
	 */
	protected function countItems() {
		return self::getList()->getcount();
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
