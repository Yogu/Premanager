<?php
namespace Premanager\Pages;

use Premanager\Models\StructureNodeType;
use Premanager\Execution\FormPageNode;
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
 * A page that shows details about a structure node
 */
class StructureNodePage extends PageNode {
	/**
	 * @var Premanager\Models\StructureNode
	 */
	private $_structureNode;
	/**
	 * @var Premanager\Modeling\QueryList
	 */
	private $_childList;
	private $_realURL;

	// ===========================================================================
	
	/**
	 * Creates a new StructureNodePage
	 * 
	 * @param Premanager\Execution\PageNode $parent the parent node
	 * @param Premanager\Models\StructureNode $structureNode the displayed
	 *   structure node
	 */
	public function __construct(PageNode $parent, StructureNode $structureNode) {
		if ($structureNode->getProject() != $parent->getProject())
			throw new ArgumentException('The specified structure node must be of '.
				'the same project as the parent page node');
		
		parent::__construct($parent);
		
		$this->_structureNode = $structureNode;
	}
	
	// ===========================================================================
	
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
		switch ($name) {
			case '+edit':
				return new EditStructureNodePage($this, $this->getStructureNode());
			case '+add':
				return new AddStructureNodePage($this, $this->getStructureNode());
			case '+delete':
				return new DeleteStructureNodePage($this, $this->getStructureNode());
			case '+move':
				return new MoveStructureNodePage($this, $this->getStructureNode());
			case '+permissions':
				return new StructureNodePermissionsPage($this,
					$this->getStructureNode());
			default:
				$model = $this->_structureNode->getChild($name);
				if ($model)
					return new StructureNodePage($this, $model);
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
		$referenceModel = $referenceNode instanceof StructureNodePage ?
			$referenceNode->_structureNode : null;
		$models = $this->getChildrenHelper(self::getChildList(), $referenceModel,
			$count);
			
		$list = array();
		foreach ($models as $model) {
			$list[] = new StructureNodePage($this, $model);
		}
		return $list;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		if ($this->_structureNode->getParent())
			return $this->_structureNode->getName();
		else
			return '-';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->_structureNode->getTitle();
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {			
		$page = new Page($this);
		
		$template = new Template('Premanager', 'structureNodeView');
		$template->set('node', $this);
		$template->set('structureNode', $this->_structureNode);
		
		$page->createMainBlock($template->get());
		
		$template = new Template('Premanager', 'structureNodeChildList');
		$template->set('node', $this);
		$template->set('children', $this->getChildList()->getAll());
		$page->appendBlock(PageBlock::createSimple(
			Translation::defaultGet('Premanager', 'browseStructureNodesTitle'),
			$template->get()));
			
		$page->toolbar[] = new ToolBarItem($this->getURL().'/+edit',
			Translation::defaultGet('Premanager', 'editNode'), 
			Translation::defaultGet('Premanager',
				'editNodeDescription'),
			'Premanager/images/tools/edit.png');
			
		if ($this->_structureNode->getParent())
			$page->toolbar[] = new ToolBarItem($this->getURL().'/+move',
				Translation::defaultGet('Premanager', 'moveNode'), 
				Translation::defaultGet('Premanager',
					'moveNodeDescription'),
				'Premanager/images/tools/move.png');
		
		$canAdd = $this->_structureNode->getType() != StructureNodeType::TREE;
			$page->toolbar[] = new ToolBarItem($this->getURL().'/+add',
				Translation::defaultGet('Premanager', 'addNode'), 
				Translation::defaultGet('Premanager',
					$canAdd ? 'addNodeDescription' : 'addTreeNodeChildError'),
				'Premanager/images/tools/add.png',
				$canAdd);
		
		if ($this->_structureNode->getParent()) {
			$canDelete = $this->getStructureNode()->canDelete();
			$page->toolbar[] = new ToolBarItem($this->getURL().'/+delete',
				Translation::defaultGet('Premanager', 'deleteNode'), 
				Translation::defaultGet('Premanager',
					$canDelete ? 'deleteNodeDescription' : 'deleteTreeNodeError'),
				'Premanager/images/tools/delete.png',
				$canDelete);
				
			$page->toolbar[] = new ToolBarItem($this->getURL().'/+permissions',
				Translation::defaultGet('Premanager', 'nodePermissions'), 
				Translation::defaultGet('Premanager', 'nodePermissionsDescription'),
				'Premanager/images/tools/rights.png');
		}
			
		$page->toolbar[] = new ToolBarItem($this->getRealURL(),
			Translation::defaultGet('Premanager', 'gotoNode'),
			Translation::defaultGet('Premanager',
				'gotoNodeDescription'),
			'Premanager/images/tools/goto-page.png');
			
		return $page;
	} 
	
	/**
	 * Gets the list of child structure nodes
	 * 
	 * @return Premanager\Modeling\QueryList
	 */
	private function getChildList(){
		if ($this->_childList == null) {
			$this->_childList = $this->_structureNode->getChildren();
			$this->_childList = $this->_childList->sort(array(
				new SortRule($this->_childList->exprMember('title'))));
		}
		return $this->_childList;
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof StructureNodePage &&
			$other->_structureNode  == $this->_structureNode; 
	}	    
	
	/**
	 * Gets the internal URL to the page node that is edited by this page
	 * 
	 * @return string the real url
	 */
	public function getRealURL() {
		if (!$this->_realURL) {
			$node = $this->_structureNode;
			while ($node && $node->getParent()) {
				$this->_realURL = $node->getName(). '/' . $this->_realURL;
				$node = $node->getParent();
			}
		}
		return $this->_realURL;
	}
	
	/**
	 * Gets the structure node that is represented by this page
	 * 
	 * @return Premanager\Models\StructureNode
	 */
	public function getStructureNode() {
		return $this->_structureNode;
	}
}

?>
