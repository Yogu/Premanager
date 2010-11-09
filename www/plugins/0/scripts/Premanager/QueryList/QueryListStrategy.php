<?php
namespace Premanager\QueryList;

use Premanager\Types;
use Premanager\Execution\Template;
use Premanager\Model;
use Premanager\QueryList\QueryOperation;
use Premanager\QueryList\QueryExpression;
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
	 * @var Premanager\QueryList\QueryList
	 */
	private $_queryList;

	// ===========================================================================
	
	/**
	 * Creates a new Premanager\QueryList\QueryListStrategy
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
	 * @return Premanager\QueryList\QueryList
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
}

?>
