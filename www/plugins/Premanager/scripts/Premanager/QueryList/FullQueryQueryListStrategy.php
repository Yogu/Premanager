<?php
namespace Premanager\QueryList;

use Premanager\Debug\Debug;
use Premanager\NotImplementedException;
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
 * Defines a QueryListStragegy which uses a query to filter and order the items
 * and does not use a post filter
 */
class FullQueryQueryListStrategy extends QueryListStrategy {
	/**
	 * @var string
	 */
	private $_queryBase;
	/**
	 * @var string
	 */
	private $_filterQuery;
	/**
	 * @var array
	 */
	private $_sortQueries;
	/**
	 * @var array
	 */
	private $_items;
	/**
	 * @var int
	 */
	private $_count;
	/**
	 * @var Premanager\DataBase\DataBaseResult
	 */
	private $_currentResult;
	/**
	 * @var int
	 */
	private $_currentResultCounter;
	
	/**
	 * The count of items that are collected in one query if it is not clear how
	 * many items have to be received 
	 */
	const ITEMS_PER_STEP = 32;

	// ===========================================================================
	
	/**
	 * Creates a new Premanager\QueryList\FullQueryQueryListStrategy
	 * 
	 * The last two arguments can be passed for performance reasons if the values
	 * are already calculated
	 * 
	 * @param Premanage\QueryList\QueryList $queryList the query list this
	 *   strategy is linked to
	 * @param string|null $filterQuery if specified, the query for the filter
	 *   expression (Premanager\QueryList\QueryExpression::getQuery()
	 * @param array|null $sortQueries if specified, an array of the queries fo all
	 *   sort rules (Premanager\QueryList\SortRule::getQuery()
	 */
	public function __construct(QueryList $queryList, $filterQuery,
		array $sortQueries) {
		parent::__construct($queryList);
		
		$this->_filterQuery = $filterQuery;
		$this->_sortQueries = $sortQueries;
	}

	// ===========================================================================
	
	/**
	 * Gets the count of items
	 * 
	 * @return int the count of items
	 */
	public function getCount() {
		if ($this->_count === null){
			$result = DataBase::query("SELECT COUNT(item.id) AS count " .
				$this->getQueryBase());
			if ($this->_count = $result->get('count'))
				return $this->_count;
			else
				$this->_count = 0;
		}
		return $this->_count;
	}
	
	/**
	 * Gets all items in this list
	 * 
	 * @return array an array of all items
	 */
	public function getAll() {
		// If all items have been collected, return them
		if ($this->_count != null && $this->_count == count($this->_items))
			return $this->_items;
		
		// Otherwise, collect all items and store them in the cache
		$result = DataBase::query("SELECT item.id " . $this->getQueryBase());
		$this->_items = array();
		while ($result->next()) {
			$this->_items[] =
				$this->getQueryList()->getModelType()->getByID($result->get('id'));
		}
		$this->_count = count($this->_items);
		return $this->_items;
	}
	
	/**
	 * Gets an item speicified by its index
	 * 
	 * @param int $index the index of the object
	 * @return mixed the object
	 */
	public function getByIndex($index) {
		if (!Types::isInteger($index))
			throw new ArgumentException('index must be an integer', 'index');
		if ($index < 0)
			throw new ArgumentOutOfRangeException('index', $index,
				'$index must not be negative');
			
		if ($this->_count !== null && $index > $this->_count)
			throw new ArgumentOutOfRangeException('$index must be smaller than the '.
				'count of items ('.$this->_count.')', 'index');
						
		if ($index < count($this->_items))
			return $this->_items[$index];
			
		// If the next item is accessed, the client probably goes through the items
		// in a loop, so we can use a global data base result
		if ($index == count($this->_items)) {
			// If there is a running query, use the result. Otherwise, start a new 
			// query
			if ($this->_currentResult) {
				// Try to access the next item
				if ($this->_currentResult->next()) {
					$this->_currentResultCounter++;
					$item = $this->getQueryList()->getModelType()->getByID(
						$this->_currentResult->get('id'));
					$this->_items[] = $item;
					return $item;
				} else if ($this->_currentResultCounter == self::ITEMS_PER_STEP) {
					// The last request returned as much items as requested, so there
					// might be more
					if ($item = $this->startRequest())
						return $item;
					else					
						throw new ArgumentOutOfRangeException('$index must be smaller '.
							'than the count of items ('.$this->_count.')', $index, 'index');
				} else {
					// The last request returned less items as requested. This means that
					// the end has been reached	
					$this->_count = count($this->_items);
					throw new ArgumentOutOfRangeException('$index must be smaller '.
						'than the count of items ('.$this->_count.')', 'index');
				}
			} else {
				if ($item = $this->startRequest())
					return $item;
				else					
					throw new ArgumentOutOfRangeException('$index must be smaller '.
						'than the count of items ('.$this->_count.')', $index, 'index');
			}
		}
		
		// It looks like the item was not requested in a loop, at least not in a one
		// that begins at the index 0, so we have to access the item directly. We
		// nor can store the item somewhere because that would cause an empty range.
		$result = DataBase::query(
			"SELECT item.id ".
			$this->getQueryBase().
			"LIMIT $index,1");
		if ($result->next()) {
			return $this->getQueryList()->getModelType()->getByID($result->get('id'));
		} else
			throw new ArgumentOutOfRangeException('$index must be smaller than the '.
				'count of items', $index, 'index');
	}
	
	/**
	 * Gets a range of items as an array
	 * 
	 * @param int $index the start index of the range
	 * @param int $count the count of items in the range
	 * @param bool $weakRangeCheck true if the range check should not throw an
	 *   exception on error but simply adjust the range
	 * @return array an array of objects
	 */
	public function getRange($index, $count, $weakRangeCheck = false) {
		if (!Types::isInteger($index))
			throw new ArgumentException('$index must be an integer', 'index');
		if (!Types::isInteger($count))
			throw new ArgumentException('$count must be an integer', 'count');
			
		if ($index < 0) {
			if ($weakRangeCheck)
				$index = 0;
			else
				throw new ArgumentOutOfRangeException('index', $index,
					'$index must not be negative');
		}
		if ($count < 0) {
			if ($weakRangeCheck)
				$count = 0;
			else
				throw new ArgumentOutOfRangeException('count', $count,
					'$count must not be negative');
		}
		
		// Maybe the requested items are already collected
		if ($index + $count < count($this->_items))
			return array_slice($this->_items, $index, $count);
			
		// Are the items before $index already received? Then we can start there
		if (count($this->_items) >= $index) {
			// Add the items that have already been received
			$array = array();
			$i = $index;
			while ($i < count($this->_items)) {
				$array[] = $this->_items[$i];
				$i++;
			}
			
			// Receive and add the new items
			try {
				while ($index + $count > count($this->_items)) {
					$array[$i] = $this->getByIndex(count($this->_items));
					$i++;
				}
			} catch (ArgumentOutOfRangeException $e) {
				if (!$weakRangeCheck)
					throw new ArgumentOutOfRangeException('count', $count,
						'$count must not be larger than the difference between actual '.
						'count ('.$this->_count.') and $index ('.$index.')');
			}
			return $array;
		}
		
		$array = array();
		$result = DataBase::query(
			"SELECT item.id ".
			$this->getQueryBase().
			"LIMIT $index,$count");
		while ($result->next()) {
			$array[] =
				$this->getQueryList()->getModelType()->getByID($result->get('id'));
		}
		
		if (!$weakRangeCheck && count($array) != $count)
			throw new ArgumentOutOfRangeException('count', $count,
				'$count must not be larger than the difference between actual count '.
				'('.$this->_count.') and $index ('.$index.')');
		return $array;
	}
	
	/**
	 * Checks whether an index is in the valid range
	 * 
	 * @param int $index the index to validate
	 * @return bool true, if the index is in the valid range
	 */
	public function isIndexValid($index) {
		if ($index < 0)
			return false;
			
		if ($this->_count !== null)
			return $index < $this->_count;
		if ($index < count($this->_items))
			return true;
		if ($index == 0 || $index <= count($this->_items)) {
			// Get the item because it probably will be requested later. Calling
			// getByIndex() will cache the item.
			try {
				$this->getByIndex($index);
				return true;
			} catch (ArgumentOutOfRangeException $e) {
				return false;
			}
		} else
			return $index < $this->getCount();
	}
	
	/**
	 * Clears all the cache for this query list
	 */
	public function clearCache() {
		$this->_items = null;
		$this->_count = null;
		$this->_currentResult = null;
		$this->_currentResultCounter = null;
	}

	// ===========================================================================
	
	protected function getQueryBase() {
		if ($this->_queryBase === null) {
			$t = $this->getQueryList()->getModelType();
			
			if ($this->_filterQuery === null) {
				if ($filter = $this->_filterQuery = $this->getQueryList()->getFilter())
					$this->_filterQuery = $filter->getQuery();
				else
					$this->_filterQuery = '';
			}
			
			if ($this->_sortQueries === null) {
				$this->_sortQueries = array();
				foreach ($this->_filterQuery = $this->getQueryList()->getSortRules()
					as $sortRule)
				{
					$this->_sortQueries[] = $sortRule->getQuery();
				}
			}
			
			$rightPart = '';
			if ($this->_filterQuery)
				$rightPart .= 'WHERE ' . $this->_filterQuery;
			if (count($this->_sortQueries))
				$rightPart .= ' ORDER BY ' . implode($this->_sortQueries, ', ') . ' '; 
			
			$table = DataBase::formTableName($t->getPluginName(), $t->getTable());
			// we only need to translate if there is a filter or sort rule that might
			// use a translation
			if ($t->isTableTranslated() && $rightPart) 
				$this->_queryBase = DataBase::getQuery(
					"FROM ".$table." AS item ".
					$this->getQueryList()->getJoinSQL(),
					/* translating */
					$rightPart).' '; 
			else
				$this->_queryBase = 'FROM ' . $table . ' AS item ' .
					$this->getQueryList()->getJoinSQL() . $rightPart . ' ';
		}
		return $this->_queryBase;
	}
	
	/**
	 * Starts a data base request that ties in with $this->_items and adds the
	 * first item to thel ist
	 * 
	 * @return Premanager\Model the next item or null if the end has been reached
	 */
	private function startRequest() {
		$this->_currentResult = DataBase::query(
			"SELECT item.id ".
			$this->getQueryBase().
			"LIMIT ".count($this->_items).",".self::ITEMS_PER_STEP);
		if ($this->_currentResult->next()) {
			$this->_currentResultCounter = 1;
			$item = $this->getQueryList()->getModelType()->getByID(
				$this->_currentResult->get('id'));
			$this->_items[] = $item;
			return $item;
		} else {
			// The end has been reached.
			$this->_count = count($this->_items);
		}
	}
}

?>
