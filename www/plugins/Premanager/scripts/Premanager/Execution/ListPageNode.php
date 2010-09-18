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
	
	/**
	 * The page index
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $pageIndex = Module::PROPERTY_GET;
	
	/**
	 * The count of pages
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $pageCount = Module::PROPERTY_GET;
	
	/**
	 * The count of items
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $itemCount = Module::PROPERTY_GET;
	
	/**
	 * The count of items to show on one page
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $itemsPerPage = Module::PROPERTY_GET;
	
	/**
	 * The index of the first item to include
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $startIndex = Module::PROPERTY_GET;
	
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
		
		$this->_pageCount = ceil($this->itemCount / $this->_itemsPerPage);
		
		// Check that current page is in page range  
		if ($this->_pageIndex > $this->_pageCount)
			$this->_pageIndex = $this->_pageCount;
		if ($this->_pageIndex < 1)
			$this->_pageIndex = 1;  
		
		$this->_startIndex = ($this->_pageIndex-1) * $this->_itemsPerPage;
	}
	
	/**
	 * Gets an array of names and values of the query ('page' => 7 for '?page=7')
	 * 
	 * @return array
	 */
	public function getURLQuery() {
		$arr = parent::getURLQuery();
		$arr['page'] = $this->_pageIndex;
		return $arr;
	}
	
	/**
	 * Gets the count of pages
	 * 
	 * @return int
	 */
	public function getPageCount() {
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
		return $this->_startIndex;
	}
	
	/**
	 * Gets the page index
	 * 
	 * @return int
	 */
	public function getPageIndex() {
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