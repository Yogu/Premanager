<?php
namespace Premanager\Blog\Pages;

use Premanager\Blog\Article;
use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Execution\ToolBarItem;
use Premanager\Models\TreeClass;
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
 * A page that shows information about an article
 */
class ArticlePage extends ListPageNode {
	/**
	 * @var Premanager\Blog\Article
	 */
	private $_article;

	// ===========================================================================
	
	/**
	 * Creates a new page node
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Blog\Article $aritcle the article to show
	 */
	public function __construct($parent, Article $article) {
		parent::__construct($parent);
		
		$this->_article = $article;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_article->getName();
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->_article->getTitle();
	}
	
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$page = new Page($this);
		
		$template = new Template('Premanager', 'groupView');
		$template->set('node', $this);
		$template->set('group', $this->_group);
		$template->set('projectsURL',
			PageNode::getTreeURL('Premanager', 'projects'));
		$page->createMainBlock($template->get());
		
		// Members
		$members = $this->_group->getMembers($this->getStartIndex(),
			$this->getItemsPerPage());
			if (count($members)) {
			$template = new Template('Premanager', 'groupMemberList');
			$template->set('members', $members);
			$template->set('node', $this);
			$body = $template->get();
			$page->appendBlock(PageBlock::createSimple(
				Translation::defaultGet('Premanager', 'groupMemberHeader'),
				$template->get()));
		}
		
		if (Rights::hasRight(Right::getByName('Premanager', 'manageGroups'),
			$this->_group->getProject())) {
			$page->toolbar[] = new ToolBarItem($this->getURL().'/edit',
				Translation::defaultGet('Premanager', 'editGroup'), 
				Translation::defaultGet('Premanager', 'editGroupDescription'),
				'Premanager/images/tools/edit.png');
			
			$page->toolbar[] = new ToolBarItem($this->getURL().'/delete',
				Translation::defaultGet('Premanager', 'deleteGroup'), 
				Translation::defaultGet('Premanager', 'deleteGroupDescription'),
				'Premanager/images/tools/delete.png');
		}
		
		if (Rights::hasRight(Right::getByName('Premanager', 'manageRights'),
			$this->_group->getProject())) {
			$page->toolbar[] = new ToolBarItem($this->getURL().'/rights',
				Translation::defaultGet('Premanager', 'editGroupRights'), 
				Translation::defaultGet('Premanager', 'editGroupRightsDescription'),
				'Premanager/images/tools/rights.png');
		}
				
		return $page;
	}

	/**
	 * Gets the group represented by this page
	 * 
	 * @return Premanager\Models\Group the group represented by this page
	 */
	public function getGroup() {
		return $this->_group;
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof GroupPage &&
			$other->_group == $this->_group; 
	}	    
	
	/**
	 * Counts the items
	 * 
	 * @return int
	 */
	protected function countItems() {
		return $this->_group->getMembers()->getCount();
	}
}

?>
