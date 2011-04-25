<?php
namespace Premanager\Modeling;

use Premanager\Types;
use Premanager\Execution\Template;
use Premanager\Model;
use Premanager\Modeling\QueryOperation;
use Premanager\Modeling\QueryExpression;
use Premanager\Module;
use Premanager\ArgumentException;
use Premanager\ArgumentNullException;
use Premanager\ArgumentOutOfRangeException;
use Premanager\InvalidOperationException;
use Premanager\IO\DataBase\DataBase;

/**
 * Defines the methods for a query list that access the data base 
 */
abstract class QueryListStrategy extends Module  {
	/**
	 * @var Premanager\Modeling\QueryList
	 */
	private $_queryList;

	// ===========================================================================
	
	/**
	 * Creates a new Premanager\Modeling\QueryListStrategy
	 * 
	 * @param Premanage\QueryList\QueryList $queryList the query list this
	 *   strategy is linked to
	 */
	public function __construct(QueryList $queryList) {
		parent::__construct();
		
		$this->_queryList = $queryList;
	}

	// ===========================================================================
	
	/**
	 * Gets the query list this strategy is linked to
	 * 
	 * @return Premanager\Modeling\QueryList
	 */
	public function getQueryList() {
		return $this->_queryList;
	}

	// ===========================================================================
	
	/**
	 * Gets the count of items
	 * 
	 * @return int the count of items
	 */
	public abstract function getCount();
	
	/**
	 * Gets all items in this list
	 * 
	 * @return array an array of all items
	 */
	public abstract function getAll();
	
	/**
	 * Gets an item speicified by its index
	 * 
	 * @param int $index the index of the object
	 * @return mixed the object
	 */
	public abstract function getByIndex($index);
	
	/**
	 * Gets a range of items as an array
	 * 
	 * @param int $index the start index of the range
	 * @param int $count the count of items in the range
	 * @param bool $weakRangeCheck true if the range check should not throw an
	 *   exception on error but simply adjust the range
	 * @return array an array of objects
	 */
	public abstract function getRange($index, $count, $weakRangeCheck = false);
	
	/**
	 * Checks whether an index is in the valid range
	 * 
	 * @param int $index the index to validate
	 * @return bool true, if the index is in the valid range
	 */
	public function isIndexValid($index) {
		return $index >= 0 && $index < $this->getCount();
	}
	
	/**
	 * Clears all the cache for this query list
	 * 
	 * Must only be overwritten if the strategy has a cache
	 */
	public function clearCache() {
		
	}
}

?>
