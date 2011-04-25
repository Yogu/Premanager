<?php
namespace Premanager\Pages;

use Premanager\Execution\TreePageNode;
use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Execution\ToolBarItem;
use Premanager\Models\Project;
use Premanager\Debug\Debug;
use Premanager\Modeling\SortDirection;
use Premanager\Modeling\QueryOperation;
use Premanager\Modeling\SortRule;
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
 * A page that shows a tree of structure nodes
 */
class StructureOverviewPage extends TreePageNode {
	private $_rootNodePage;
	
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
		if (!Rights::hasRight(Right::getByName('Premanager', 'structureAdmin'),
			$this->getProject()))
			return;
		
		if ($name == '-')
			return $this->getRootNodePage();
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
		if (!Rights::hasRight(Right::getByName('Premanager', 'structureAdmin'),
			$this->getProject()))
			return array();
			
		return array($this->getRootNodePage());
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		if (!Rights::requireRight(Right::getByName('Premanager', 'structureAdmin'),
			$this->getProject(), $errorResponse, false))
			return $errorResponse;
			
		$page = new Page($this);
		$page->createMainBlock(
			'<p>'.Translation::defaultGet('Premanager', 'structureMessage').'</p>');
			
		$template = new Template('Premanager', 'structureOverview');
		$template->set('node', $this);
		$template->set('rootNode', $this->getRootNodePage());
		$page->appendBlock(PageBlock::createSimple(
			Translation::defaultGet('Premanager', 'structureTitle'),
			$template->get()));
		
		return $page;
	} 
	
	/**
	 * Gets the root node page
	 * 
	 * @return Premanager\Pages\StructureNodePage
	 */
	private function getRootNodePage(){
		if ($this->_rootNodePage == null)
			$this->_rootNodePage =
				new StructureNodePage($this, $this->getProject()->getRootNode());
		return $this->_rootNodePage;
	}
}

?>
