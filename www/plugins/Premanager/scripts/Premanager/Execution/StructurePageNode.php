<?php
namespace Premanager\Execution;

use Premanage\Execution\Template;
use Premanager\NotImplementedException;
use Premanager\ArgumentException;
use Premanager\Models\StructureNode;
use Premanager\Models\StructureNodeType;
use Premanager\Models\Project;

class StructurePageNode extends PageNode {
	/**
	 * @var Premanager\Models\StructureNode
	 */
	private $_structureNode;
	/**
	 * @var Premanager\Execution\TreeNode
	 */
	private $_treeNode = false;
	
	/**
	 * Creates a new page node based on a structure node or on the root node of
	 * the organization
	 * 
	 * @param Premanager\Models\PageNode|null $parent the parent node or null to
	 *   create a page node on the base of the root node of the organization
	 * @param StructureNode $structureNode the base structure node or null to
	 *   create a page node on the base of the root node of the organization
	 */
	public function __construct($parent = null, $structureNode = null) {
		if (!$parent)
			$this->_structureNode = Project::getOrganization()->rootNode;
		else {
			if (!($structureNode instanceof StructureNode))
				throw new ArgumentException('$structureNode must be an a '.
					'Premanager\Models\StructureNode', 'structureNode');
			$this->_structureNode = $structureNode;
		}
		
		parent::__construct($parent);
		
		$this->_project = $structureNode->project;
	}
	
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
		if ($this->_structureNode->type == StructureNodeType::TREE)
			return $this->getTreeNode()->getChildByName($name);
		else {
			$structureNode = $this->_structureNode->getChild($name);
			if ($structureNode)
				return new StructurePageNode($this, $structureNode);
			else
				return null;
		}
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_structureNode->name;
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->_structureNode->title;
	}
	
	public function getStandAloneTitle() {
		if ($this->_structureNode->type == StructureNodeType::TREE)
			return $this->getTreeNode()->standAloneTitle;
		else
			return $this->_structureNode->title;
	}
	
	/**
	 * Creates a page object that covers the data of this page node
	 * 
	 * @return Premanager\Execution\Page the page or null, if this page node does
	 *   not result in a page. 
	 */
	public function getPage() {
		switch ($this->_structureNode->type) {
			case StructureNodeType::TREE:
				return $this->getTreeNode()->getPage();
			
			case StructureNodeType::PANEL:
				//TODO: create a panel page
				throw new NotImplementedException();
				
			default:
				//TODO: fetch all sub nodes into $subNodes
				$subNodes = array();
				
				// create list of sub-page-nodes
				$template = new Template('Premanager', 'subPagesList');
				$template->set('list', $subNodes);
				
				$page = new Page($this);
				$page->createMainBlock($template->get());
				return $page;
		}
	}

	/**
	 * Performs a call of this page
	 */
	public function execute() {
		if ($this->_structureNode->type == StructureNodeType::TREE)
			$this->getTreeNode()->execute();
		else
			Output::select($this->getPage());
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof StructurePageNode &&
			$other->_structureNode == $this->_structureNode; 
	}	    
	
	/**
	 * Gets the embedded tree page node
	 * 
	 * @return Premanager\Execution\TreeNode the embedded tree page node or null
	 *   if the structure node's type is not TREE
	 */
	private function getTreeNode() {
		if ($this->_treeNode === false) {
			if ($this->_structureNode->type == StructureNodeType::TREE)
				$this->_treeNode = $this->_structureNode->treeClass->createInstance();
			else
				$this->_treeNode = null;
		}
		return $this->_treeNode;
	}
}

?>
