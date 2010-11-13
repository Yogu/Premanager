<?php
namespace Premanager\Execution;

use Premanager\IO\Request;
use Premanager\IO\StatusCode;
use Premanager\Execution\Template;
use Premanager\NotImplementedException;
use Premanager\ArgumentException;
use Premanager\Models\StructureNode;
use Premanager\Models\StructureNodeType;
use Premanager\IO\Output;
use Premanager\Module;

/**
 * Defines a page node consisting of multiple pages
 */
abstract class ListPageNode extends PageNode {
	/**
	 * @var int
	 */
	private $_pageIndex;
	/**
	 * @var int
	 */
	private $_itemCount;
	/**
	 * @var int
	 */
	private $_pageCount;
	/**
	 * @var int
	 */
	private $_startIndex;
	/**
	 * @var int
	 */
	private $_itemsPerPage;
	
	// ===========================================================================
	
	/**
	 * Creates a ListPageNode
	 * 
	 * @param Premanager\Models\PageNode|null $parent the parent node
	 * @param int $pageIndex the requested page index or null to use the GET
	 *   parameter 'page'
	 * @param int $itemsPerPage the count of items to view on one page or null to
	 *   use the option Premanager.itemsPerPage
	 */
	public function __construct($parent, $pageIndex = null,
		$itemsPerPage = null) {
		parent::__construct($parent);
		
		if ($pageIndex === null)
			$this->_pageIndex = (int)Request::getGET('page');
		else
			$this->_pageIndex = (int)$pageIndex;
			
		if ($itemsPerPage === null)
			$this->_itemsPerPage = 
				(int)Options::defaultGet('Premanager', 'itemsPerPage');
		else
			$this->_itemsPerPage = (int)$itemsPerPage;
	}
	
	// ===========================================================================
	
	/**
	 * Gets an array of names and values of the query ('page' => 7 for '?page=7')
	 * 
	 * @return array
	 */
	public function getURLQuery() {
		$arr = parent::getURLQuery();
		if ($this->_pageIndex > 1)
			$arr['page'] = $this->getPageIndex();
		return $arr;
	}
	
	/**
	 * Gets the count of pages
	 * 
	 * @return int
	 */
	public function getPageCount() {
		if ($this->_pageCount === null)
			$this->_pageCount = ceil($this->getitemCount() / $this->_itemsPerPage);
		return $this->_pageCount;
	}
	
	/**
	 * Gets the count of items
	 * 
	 * @return int
	 */
	public function getItemCount() {
		if ($this->_itemCount === null)
			$this->_itemCount = $this->countItems();
		return $this->_itemCount;
	}
	
	/**
	 * Gets the count of items to view on one page
	 * 
	 * @return int
	 */
	public function getItemsPerPage() {
		return $this->_itemsPerPage;
	}
	
	/**
	 * Gets the index of the first item to include
	 * 
	 * @return int
	 */
	public function getStartIndex() {
		if ($this->_startIndex === null)
			$this->_startIndex = ($this->getpageIndex()-1) * $this->_itemsPerPage;
		return $this->_startIndex;
	}
	
	/**
	 * Gets the page index
	 * 
	 * @return int
	 */
	public function getPageIndex() {
		if ($this->_pageIndex === null) {
			// Check that current page is in page range  
			if ($this->_pageIndex > $this->getpageCount())
				$this->_pageIndex = $this->getpageCount();
			if ($this->_pageIndex < 1)
				$this->_pageIndex = 1;
		}
		return $this->_pageIndex;
	}
	
	/**
	 * Counts the items
	 * 
	 * @return int
	 */
	protected abstract function countItems();
}

?>
