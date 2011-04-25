<?php
namespace Premanager\Execution;

use Premanager\Models\Right;
use Premanager\Modeling\SortRule;
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
	 * @var bool
	 */
	private $_isProjectNode;
	/**
	 * @var bool
	 */
	private $_isRootNode;
	/**
	 * @var Premanager\Modeling\QueryList
	 */
	private $_listCache;
	
	// ===========================================================================
	
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
			$this->_structureNode = Project::getOrganization()->getRootNode();
			$this->_isProjectNode = true;
			$this->_isRootNode = true;
		} else {
			if (!($structureNode instanceof StructureNode))
				throw new ArgumentException('$structureNode must be an a '.
					'Premanager\Models\StructureNode', 'structureNode');
			if ($structureNode->gettype() == StructureNodeType::TREE)
				throw new ArgumentException('$structureNode must not be a TREE node',
					'structureNode');
			$this->_structureNode = $structureNode;
			
			// If the parent is the organization node, this must be a project node
			$this->_isProjectNode =
				$structureNode->getProject()->getRootNode() == $structureNode;
			$this->_isRootNode = $this->_isProjectNode &&
				$structureNode->getProject()->getID() == 0;
		}
		
		parent::__construct($parent);
		
		$this->project = $this->_structureNode->getProject();
	}
	
	// ===========================================================================
	
	/**
	 * Gets the root node of organization
	 * 
	 * @return Premanager\Execution\StructurePageNode the organization's root node
	 */
	public static function getRootNode() {
		static $value;
		if (!$value)
			$value = new self();
		return $value;
	}
	
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
		if ($this->_isRootNode) {
			$project = Project::getByName($name);
			if ($project)
				return $this->getChildByStructureNode($project->getRootNode());
		}
		
		$structureNode = $this->_structureNode->getChild($name);
		if ($structureNode &&
			($structureNode->canAccess(Environment::getCurrent()->getUser()) ||
			Rights::hasRight(Right::getByName('Premanager', 'structureAdmin'))))
				return $this->getChildByStructureNode($structureNode);
	}	
	
	/**
	 * Gets an array of all child page nodes
	 * 
	 * @param int $count is ignored in this implementation
	 * @param Premanager\Execution\PageNode $referenceNode is ignored in this
	 *   implementation
	 * @return array an array of the child Premanager\Execution\PageNode's
	 */
	public function getChildren($count = -1, PageNode $referenceNode = null) {
		$structureNodes = $this->getChildrenHelper($this->getList(), null, -1);
			
		$list = array();
		
		// Projects if root node
		if ($this->_isRootNode) {
			foreach (Project::getProjects() as $project) {
				if ($project->getID())
					$list[] = $this->getChildByStructureNode($project->getRootNode());
			}
		}
		
		// Child nodes
		$right = Rights::hasRight(Right::getByName('Premanager', 'structureAdmin'));
		foreach ($structureNodes as $structureNode) {
			if ($right ||
				$structureNode->canAccess(Environment::getCurrent()->getUser()))
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
		if ($this->_structureNode != $structureNode->getParent() && 
			!($this->_isRootNode && $structureNode->getParent() == null))
			throw new ArgumentException('The passed structure node is not a child '.
				'of the structure node this page node represents', 'structureNode');
		
		// If the child is a TREE node, the embedded node is used, not the
		// structure page node
		if ($structureNode->getType() == StructureNodeType::TREE)
			return $structureNode->getTreeClass()->createInstance($this,
				$structureNode);
		else
			return new StructurePageNode($this, $structureNode);
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		// A project's root node's name is the project's name
		return $this->_structureNode->getParent() ? 
			$this->_structureNode->getName() :
			$this->_structureNode->getProject()->getName();
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		if ($this->_isProjectNode)
			return $this->project->gettitle();
		else
			return $this->_structureNode->getTitle();
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		switch ($this->_structureNode->gettype()) {
			case StructureNodeType::PANEL:
				//TODO: create a panel page
				throw new NotImplementedException();
				
			default:
				$subNodes = array();
				$right =
					Rights::hasRight(Right::getByName('Premanager', 'structureAdmin'));
				foreach ($this->getList() as $structureNode) {
					if ($right ||
						$structureNode->canAccess(Environment::getCurrent()->getUser()))
							$subNodes[] = $this->getChildByStructureNode($structureNode);
				}
				
				// create list of sub-page-nodes
				$template = new Template('Premanager', 'subPagesList');
				$template->set('list', $subNodes);
				
				$page = new Page($this);
				$page->createMainBlock($template->get());
				return $page;
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
	
	private function getList() {
		if ($this->_listCache === null) {
			$this->_listCache = $this->_structureNode->getChildren();
			$this->_listCache = $this->_listCache->sort(array(
				new SortRule($this->_listCache->exprMember('title'))));
		}
		return $this->_listCache;
	}
}

?>
