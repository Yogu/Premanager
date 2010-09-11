<?php
namespace Premanager\Execution;

use Premanager\NotImplementedException;

use Premanager\ArgumentException;
use Premanager\Models\StructureNode;
use Premanager\Models\StructureNodeType;

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
	 * Creates a new page node based on a structure node
	 * 
	 * @param Premanager\Models\PageNode|null $parent the parent node or null if
	 *   this is a root node
	 * @param StructureNode $structureNode
	 */
	public function __construct($parent, StructureNode $structureNode) {
		parent::__construct($parent);
		if (!$parent && $structureNode->parent)
			throw new ArgumentException('$parent must not be null if $structureNode '.
				'is not a root node');
			
		$this->_structureNode = $structureNode;
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
	 * Performs a call of this page
	 */
	public function execute() {
		switch ($this->_structureNode->type) {
			case StructureNodeType::TREE:
				$this->getTreeNode()->execute();
			
			case StructureNodeType::PANEL:
				throw new NotImplementedException();
				
			default:
				
		}
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
				$this->_treeNode = $this->_structureNode->tree->createInstance();
			else
				$this->_treeNode = null;
		}
		return $this->_treeNode;
	}
}

?>