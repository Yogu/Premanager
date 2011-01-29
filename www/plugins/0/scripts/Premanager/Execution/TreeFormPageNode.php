<?php
namespace Premanager\Execution;

use Premanager\Models\StructureNode;
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
 * A base for form page nodes consisting of multiple pages
 * 
 * Tree page nodes do not have to extend this class, they can implement the same
 * methods on their own.
 */
abstract class TreeFormPageNode extends FormPageNode {
	/**
	 * @var Premanager\Models\StructureNode
	 */
	private $_structureNode;
	
	/**
	 * Creates a new TreePageNode
	 * 
	 * @param Premanager\Execution\PageNode $parent the parent node
	 * @param Premanager\Models\StructureNode $structureNode the structure node
	 *   this page node is embedded in
	 * @param int $pageIndex the requested page index or null to use the GET
	 *   parameter 'page'
	 * @param int $itemsPerPage the count of items to view on one page or null to
	 *   use the option Premanager.itemsPerPage
	 */
	public function __construct(PageNode $parent, StructureNode $structureNode,
		$pageIndex = null, $itemsPerPage = null)
	{
		parent::__construct($parent, $pageIndex, $itemsPerPage);
		$this->_structureNode = $structureNode;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_structureNode->getname();
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->_structureNode->gettitle();
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof TreeFormPageNode &&
			$other->_structureNode == $this->_structureNode; 
	}	   
	
}

?>
