<?php
namespace Premanager\Execution;

use Premanager\Debug\Debug;
use Premanager\IO\Output;
use Premanager\Execution\Template;
use Premanager\NotImplementedException;
use Premanager\ArgumentException;
use Premanager\Models\StructureNode;
use Premanager\Models\StructureNodeType;
use Premanager\Models\Project;
use Premanager\Module;

class StructurePageNode extends PageNode {
	/**
	 * @var Premanager\Models\StructureNode
	 */
	private $_structureNode;
	/**
	 * @vqr bool
	 */
	private $_isProjectNode;
	
	/**
	 * The base structure node
	 * 
	 * @var Premanager\Models\StructureNode
	 */
	public $structureNode = Module::PROPERTY_GET;
	
	/**
	 * Creates a new page node based on a structure node or on the root node of
	 * the organization. The structure node must not be a TREE node
	 * 
	 * @param Premanager\Models\PageNode|null $parent the parent node or null to
	 *   create a page node on the base of the root node of the organization
	 * @param StructureNode $structureNode the base structure node or null to
	 *   create a page node on the base of the root node of the organization
	 */
	public function __construct($parent = null, $structureNode = null) {
		if (!$parent) {
			$this->_structureNode = Project::getOrganization()->rootNode;
			$this->_isProjectNode = true;
		} else {
			if (!($structureNode instanceof StructureNode))
				throw new ArgumentException('$structureNode must be an a '.
					'Premanager\Models\StructureNode', 'structureNode');
			if ($structureNode->type == StructureNodeType::TREE)
				throw new ArgumentException('$structureNode must not be a TREE node',
					'structureNode');
			$this->_structureNode = $structureNode;
			
			// If the parent is the organization node, this must be a project node
			$this->_isProjectNode =
				$structureNode->project->getRootNode() == $structureNode;
		}
		
		parent::__construct($parent);
		
		$this->_project = $this->_structureNode->project;
	}
	
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
		$structureNode = $this->_structureNode->getChild($name);
		if ($structureNode) {
			return $this->getChildByStructureNode($structureNode);
		} else
			return null;
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
		$referenceModel = $referenceNode instanceof StructurePageNode ?
			$referenceNode->getStructureNode() : null;
		$structureNodes = $this->getChildrenHelper(
			$this->_structureNode->getChildren(), $referenceModel, $count);
			
		$list = array();
		foreach ($structureNodes as $structureNode) {
			$list[] = $this->getChildByStructureNode($structureNode);
		}
		return $list;
	}
	
	/**
	 * Creates a PageNode that shows the contents of the speicified structure node
	 * 
	 * @param Premanager\Models\StructureNode $structureNode the structure node
	 * @return Premanager\Execution\PageNode the page node
	 */
	public function getChildByStructureNode(StructureNode $structureNode) {
		if ($this->_structureNode != $structureNode->parent)
			throw new ArgumentException('The passed structure node is not a child '.
				'of the structure ndoe this page node represents', 'structureNode');
		
		// If the child is a TREE node, the embedded node is used, not the
		// structure page node
		if ($structureNode->type == StructureNodeType::TREE)
			return $structureNode->treeClass->createInstance($this, $structureNode);
		else
			return new StructurePageNode($this, $structureNode);
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
		if ($this->_isProjectNode)
			return $this->_project->title;
		else
			return $this->_structureNode->title;
	}
	
	public function getStandAloneTitle() {
		return $this->_structureNode->title;
	}

	/**
	 * Performs a call of this page
	 */
	public function execute() {
		switch ($this->_structureNode->type) {
			case StructureNodeType::PANEL:
				//TODO: create a panel page
				throw new NotImplementedException();
				
			default:
				$subNodes = array();
				foreach ($this->_structureNode->getChildren() as $structureNode) {
					$subNodes[] = $this->getChildByStructureNode($structureNode);
				}
				
				// create list of sub-page-nodes
				$template = new Template('Premanager', 'subPagesList');
				$template->set('list', $subNodes);
				
				$page = new Page($this);
				$page->createMainBlock($template->get());
				Output::select($page);
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
	 * Gets the base structure node
	 * 
	 * @return Premanager\Models\StructureNode
	 */
	public function getStructureNode() {
		return $this->_structureNode;
	}
	
	/**
	 * Checks if this is the root node of a project
	 * 
	 * @return bool true, if this is the root node of a project
	 */
	public function isProjectNode() {
		return $this->_isProjectNode;
	}
	
	/**
	 * Gets an array of names and values of the query ('page' => 7 for '?page=7')
	 * 
	 * @return array
	 */
	public function getURLQuery() {
		return array();
	}
}

?>
