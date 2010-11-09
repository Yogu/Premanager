<?php
namespace Premanager\QueryList;

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
 * Defines a QueryListStragegy which uses only php methods to filter and sort
 * the items
 */
class PHPQueryListStrategy extends QueryListStrategy {
	/**
	 * @var int
	 */
	private $_count;
	/**
	 * @var array
	 */
	private $_items = array();
	/**
	 * @var int
	 */
	private $_queryItemIndex = 0; // the current index in data base
	/**
	 * @var bool
	 */
	private $_completed;
	/**
	 * @var string
	 */
	private $_queryBase;
	
	/**
	 * The count of items that are collected in one query if it is not clear how
	 * many items have to be received 
	 */
	const ITEMS_PER_STEP = 32;
	
	const MAX_LONG = '9223372036854775807';

	// ===========================================================================
	
	/**
	 * Gets the count of items
	 * 
	 * @return int the count of items
	 */
	public function getCount() {
		if (!$this->_completed)
			$this->getAll();
			
		return $this->_count;
	}
	
	/**
	 * Gets all items in this list
	 * 
	 * @return array an array of all items
	 */
	public function getAll() {
		// If we have not collected all items, continue collecting
		if (!$this->_completed) {
			// If there are sort rules, we have to sort at first
			if (count($this->getQueryList()->getSortRules()))
				$this->retrieveAllSorted();
			else
				$this->retrieveAllUnsorted();
		}
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
	
		$count = count($this->_items);
		
		if (!$this->_completed) {
			// If there are sort rules, we have to sort at first
			if ($this->getQueryList()->getSortRules())
				$this->retrieveAllSorted();
			else {
				// If the item is not already collected, get it. If the collection has
				// completed an the item is still not collected, the index is too large.
				// Otherwise, continue collecting items
				while ($index >= $count && !$this->_completed) {
					// Request another list of possible correct items
					$result = DataBase::query(
						$this->getQueryBase().
						"LIMIT ".$this->_queryItemIndex.", ".self::ITEMS_PER_STEP);
					while ($index >= $count && $result->next()) {
						// We don't have to check this item later, so store the current
						// index
						$this->_queryItemIndex++;
						
						// Get the object and evaluate it
						$id = $result->get('id');
						$obj = $this->getQueryList()->getModelType()->getByID($id);
						$filter = $this->getQueryList()->getFilter();
						if (!$filter || $filter->evaluate($obj, true)) {
							$this->_items[$count] = $obj;
							$count++;
						}
					}
					
					// Determine whether there are more items by comparing the requested
					// count of items to the actual received count of items.
					if ($result->getRowCount() < self::ITEMS_PER_STEP) {
						$this->_completed = true;
						$this->_count = $count;
					}
				}
			}
		}
		 
		if ($index < $count)
			return $this->_items[$index];
		else
			throw new ArgumentOutOfRangeException('index', $index,
				'$index must be smaller than the count ('.$count.')');
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
			throw new ArgumentException('index must be an integer', 'index');
		if (!Types::isInteger($count))
			throw new ArgumentException('count must be an integer', 'count');
			
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
			
		if ($count == 0)
			return array();
		
			
		$currentCount = \count($this->_items);
		
		$lastIndex = $index + $count - 1;
		
		if (!$this->_completed) {
			// If there are sort rules, we have to sort at first
			if (count($this->getQueryList()->getSortRules())) {
				$this->retrieveAllSorted();
				$currentCount = $this->_count; // is needed below 
			} else {
				// If the item is not already collected, get it. If the collection has
				// completed an the item is still not collected, the index is too large.
				// Otherwise, continue collecting items
				while ($lastIndex >= $currentCount && !$this->_completed) {
					// Request another list of possible correct items
					$result = DataBase::query(
						$this->getQueryBase().
						"LIMIT ".$this->_queryItemIndex.", ".self::ITEMS_PER_STEP);
					while ($count > $index && $result->next()) {
						// We don't have to check this item later, so store the current
						// index
						$this->_queryItemIndex++;
						
						// Get the object and evaluate it
						$id = $result->get('id');
						$obj = $this->getQueryList()->getModelType()->getByID($id);
						$filter = $this->getQueryList()->getFilter();
						if (!$filter || $filter->evaluate($obj, true)) {
							$this->_items[$currentCount] = $obj;
							$currentCount++;
						}
					}
					
					// Determine whether there are more items by comparing the requested
					// count of items to the actual received count of items.
					if ($result->getRowCount() < self::ITEMS_PER_STEP) {
						$this->_completed = true;
						$this->_count = $currentCount;
					}
				}
			}
		}
		
		if ($index >= $currentCount) {
			if ($weakRangeCheck) {
				$index = $currentCount-1;		
				$lastIndex = $index + $count - 1;
			} else
				throw new ArgumentOutOfRangeException('index', $index,
					'$index must be smaller than the count ('.$this->_count.')');
		}
		
		if ($lastIndex >= $currentCount) {
			if ($weakRangeCheck)
				$count = $this->_count - $index;
			else
				throw new ArgumentOutOfRangeException('count', $count,
					'$count must not be larger than the difference between actual count '.
					'('.$this->_count.') and $index ('.$index.')');
		}
		
		return array_slice($this->_items, $index, $count);
	}

	// ===========================================================================
	
	private function getQueryBase() {
		if ($this->_queryBase === null) {
			$modelType = $this->getQueryList()->getModelType();
			$this->_queryBase =
				"SELECT item.id ".
				"FROM ".DataBase::formTableName(
					$modelType->getPluginName(), $modelType->getTable())." AS item ";
		}
		return $this->_queryBase;
	}
	
	/**
	 * Retrieves all items and sorts them
	 * 
	 * This is neccessary if sorting can not be done with a query
	 */
	private function retrieveAllSorted() {
		$this->retrieveAllUnsorted();
		
		// Sort the items based on the sort rules; the lower the index the more
		// decisive the rule is
		uasort($this->_items, array($this, 'compareItems'));
	}

	/**
	 * Compares two items using the $_sortRules field
	 * 
	 * @param Premanager\Model $item0 the left-hand operand
	 * @param Premanager\Model $item1 the right-hand operand
	 * @return int value less than, equal or greater than zero to indicate whether
	 *   $item0 is less, equal or greater than $item1
	 */
	private function compareItems($item0, $item1) {
		foreach ($this->getQueryList()->getSortRules() as $rule) {
			if ($result = $rule->evaluate($item0, $item1))
				return $result;
		}
		return 0;
	}
	
	/**
	 * Retrieves all items but does not care about sorting them
	 */
	private function retrieveAllUnsorted() {
		// If we have not collected all items, continue collecting
		if (!$this->_completed) { 
			$count = \count($this->_items);
			
			// Request all items beginning at the index of the last item received and
			// evaluated
			$result = DataBase::query(
				$this->getQueryBase().
				"LIMIT ".$this->_queryItemIndex.", ".self::MAX_LONG);
			while ($result->next()) {
				// Get the object and evaluate it
				$id = $result->get('id');
				$obj = $this->getQueryList()->getModelType()->getByID($id);
				$filter = $this->getQueryList()->getFilter();
				if (!$filter || $filter->evaluate($obj, true)) {
					$this->_items[$count] = $obj;
					$count++;
				}
			}
			
			// No we are sure to have collected all items
			$this->_count = $count;
			$this->_completed = true;
		}
	}
}

?>
