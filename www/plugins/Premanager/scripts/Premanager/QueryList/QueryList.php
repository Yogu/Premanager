<?php
namespace Premanager\QueryList;

use Premanager\QueryList\QueryOperation;
use Premanager\QueryList\QueryExpression;
use Premanager\Module;
use Premanager\ArgumentException;
use Premanager\ArgumentNullException;
use Premanager\ArgumentOutOfRangeException;
use Premanager\InvalidOperationException;
use Premanager\IO\DataBase\DataBase;

/**
 * Allows query-like access to models
 */
class QueryList extends Module implements \ArrayAccess, \IteratorAggregate,
	\Countable {
	/**
	 * @var Premanager\QueryList\ModelDescriptor
	 */
	private $_modelType;
	/**
	 * @var Premanager\QueryList\QueryExpression
	 */
	private $_filter;
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
	
	const MAX_LONG = '9223372036854775807';
	
	/**
	 * The count of items that are collected in one query if it is not clear how
	 * many items has to be received 
	 */
	const ITEMS_PER_STEP = 32;

	// ===========================================================================
	
	/**
	 * The count of items
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $count = Module::PROPERTY_GET;

	// ===========================================================================
	
	/**
	 * Creates a new Premanager\QueryList\QueryList
	 * 
	 * @param Premanager\QueryList\ModelDescriptor $modelType
	 * @param Premanager\QueryList\QueryExpression $filter
	 */
	public function __construct(ModelDescriptor $modelType, $filter = null) {
		parent::__construct();
		
		$this->_modelType = $modelType;
		
		if ($filter !== null) {
			if (!($filter instanceof QueryExpression))
				throw new ArgumentException('$filter must be null or a '.
					'Premanager\QueryList\QueryExpression', 'filter');
			$this->_filter = $filter;
		}
	}

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
	 * Gets the count of items
	 * 
	 * @return int the count of items
	 */
	public function count() {
		// This method is needed to implement the Countable interface
		return $this->getCount();
	}
	
	/**
	 * Gets all items, one item or a range of items
	 * 
	 * If both arguments are null, returns all items as an array.
	 * If one argument is specified, returns the item with the speicified index.
	 * If both arguments are speicified, returns an array of the specified range.
	 * 
	 * Note: if $count is 1, an array is returned, not a single object.
	 * 
	 * @param int|null $index the start index of the range
	 * @param int|null $count the counts of items to return
	 * @returns mixed either an array of objects or a single object
	 */
	public function get($index = null, $count = null) {
		if ($index === null) {
			if ($count !== null)
				throw new ArgumentException('If $index is null, $count must be null, '.
					'too', 'index');
			return $this->getAll();
		} else if ($count === null)
			return $this->getByIndex($index);
		else
			return $this->getRange($index, $count);
	}
	
	/**
	 * Gets all items in this list
	 * 
	 * @return array an array of all items
	 */
	public function getAll() {
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
				$obj = $this->_modelType->getByID($id);
				if (!$this->_filter || $this->_filter->evaluate($obj, true)) {
					$this->_items[$count] = $obj;
					$count++;
				}
			}
			
			// No we are sure to have collected all items
			$this->_count = $count;
			$this->_completed = true;
		}
		
		// optimated for lists without post-query evaluation
		/*
		// If all elements are collected, simply return that collection. If count
		// has not been received jet, get it now because it would be needed anyway.
		if (\count($this->_items) != $this->count) {
			$result = DataBase::query($this->getQueryBase());
			for ($i = 0; $result->next(); $i++) {
				$id = $result->get($this->_modelType->idField);
				$this->_items[$i] = $this->_modelType->getByID($id);
			}
		}
		*/
		return $this->_items;
	}
	
	/**
	 * Gets an item speicified by its index
	 * 
	 * @param int $index the index of the object
	 * @return mixed the object
	 */
	public function getByIndex($index) {
		if ($index === null)
			throw new ArgumentNullException('index');
		if (!is_int($index))
			throw new ArgumentException('index must be an integer', 'index');
		if ($index < 0)
			throw new ArgumentOutOfRangeException('index', $index,
				'$index must not be negative');
	
		$count = count($this->_items);
		
		// If the item is not already collected, get it. If the collection has
		// completed an the item is still not collected, the index is too large.
		// Otherwise, continue collecting items
		while ($index >= $count && !$this->_completed) {
			// Request another list of possible correct items
			$result = DataBase::query(
				$this->getQueryBase().
				"LIMIT ".$this->_queryItemIndex.", ".self::ITEMS_PER_STEP);
			while ($index >= $count && $result->next()) {
				// We don't have to check this item later, so store the current index
				$this->_queryItemIndex++;
				
				// Get the object and evaluate it
				$id = $result->get('id');
				$obj = $this->_modelType->getByID($id);
				if (!$this->_filter || $this->_filter->evaluate($obj, true)) {
					$this->_items[$count] = $obj;
					$count++;
				}
			}
			
			// Determine whether there are more items by comparing the requested
			// count of items to the actual received count of items.
			if ($result->rowCount < self::ITEMS_PER_STEP) {
				$this->_completed = true;
				$this->_count = $count;
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
	 * @return array an array of objects
	 */
	public function getRange($index, $count) {
		if ($index === null)
			throw new ArgumentNullException('index');
		if (!is_int($index))
			throw new ArgumentException('index must be an integer', 'index');
		if ($index < 0)
			throw new ArgumentOutOfRangeException('index', $index,
				'$index must not be negative');
			
		if ($count === null)
			throw new ArgumentNullException('count');
		if (!is_int($count))
			throw new ArgumentException('count must be an integer', 'count');
		if ($count < 0)
			throw new ArgumentOutOfRangeException('count', $count,
				'$count must not be negative');
			
		if ($count == 0)
			return array();
		
			
		$currentCount = \count($this->_items);
		
		$lastIndex = $index + $count - 1;
		
		// If the item is not already collected, get it. If the collection has
		// completed an the item is still not collected, the index is too large.
		// Otherwise, continue collecting items
		while ($lastIndex >= $currentCount && !$this->_completed) {
			// Request another list of possible correct items
			$result = DataBase::query(
				$this->getQueryBase().
				"LIMIT ".$this->_queryItemIndex.", ".self::ITEMS_PER_STEP);
			while ($count > $index && $result->next()) {
				// We don't have to check this item later, so store the current index
				$this->_queryItemIndex++;
				
				// Get the object and evaluate it
				$id = $result->get('id');
				$obj = $this->_modelType->getByID($id);
				if (!$this->_filter || $this->_filter->evaluate($obj, true)) {
					$this->_items[$currentCount] = $obj;
					$currentCount++;
				}
			}
			
			// Determine whether there are more items by comparing the requested
			// count of items to the actual received count of items.
			if ($result->rowCount < self::ITEMS_PER_STEP) {
				$this->_completed = true;
				$this->_count = $currentCount;
			}
		}
		 
		if ($lastIndex < $currentCount)
			return array_slice($this->_items, $index, $count);
		else if ($index >= $currentCount)
			throw new ArgumentOutOfRangeException('index', $index,
				'$index must be smaller than the count ('.$this->_count.')');
		else
			throw new ArgumentOutOfRangeException('count', $count,
				'$count must be smaller than the difference between actual count ('.
				$this->_count.') and $index ('.$index.')');
	}
	
	/**
	 * Gets a list that contains all items of this list for that the speicified
	 * condition is true
	 * 
	 * @param Premanager\QueryList\QueryExpression $condition the expression that
	 *   is checked for each item
	 * @return QueryList a list of all items for that the condition is true
	 */
	public function filter(QueryExpression $condition) {
		if (!$condition)
			throw new ArgumentNullException('$condition');
			
		if ($condition->type != DataType::BOOLEAN)
			throw new ArgumentException('Only BOOLEAN expressions are valid for the '.
				'$condition parameter', 'condition');
		
		if ($condition->value === true)
			return $this;
		
		if ($this->_filter)
			return new QueryList(new QueryExpression($this->_modelType,
				QueryOperation::LOGICAL_AND, $this->_filter, $condition));
		else
			return new QueryList($this->_modelType, $condition);
	}
	
	/**
	 * Gets a list that contains all items of this list in a specified order
	 * 
	 * @param array $criteria
	 * @return QueryList a list of all items of this list in a specified order
	 */
	public function sort(array $criteria) {
		// If there is nothing to sort, return this list
		if (!count($criteria))
			return $this;
		
		throw new NotImplementedException();
	}
	
	/**
	 * Checks whether an offset is in range
	 * 
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset) {
		return \is_int($offset) && $offset >= 0 && $offset < $this->count;
	}
	
	/**
	 * Gets an item specified by its index
	 * 
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset) {
		return $this->get($offset);
	}
	
	public function offsetSet($offset, $value) {
		throw new InvalidOperationException('QueryLists can not be changed');
	}
	
	public function offsetUnset($offset) {
		throw new InvalidOperationException('QueryLists can not be changed');
	}
	
	public function getIterator() {
		return new QueryListIterator($this);
	}
	
	/**
	 * Gets an EQUAL expression
	 *
	 * @param mixed $operand0 the first operand
	 * @param unknown_type the second operand
	 * @return Premanager\QueryList\QueryExpression
	 */
	public function exprEqual($operand0, $operand1) {
		if (!($operand0 instanceof QueryExpression))
			$operand0 = new QueryExpression($this->_modelType, $operand0);
		if (!($operand1 instanceof QueryExpression))
			$operand1 = new QueryExpression($this->_modelType, $operand1);
			
		return new QueryExpression($this->_modelType, QueryOperation::EQUAL,
			$operand0, $operand1);
	}
	
	/**
	 * Gets an AND expression
	 * 
	 * @param mixed $operand0 the first operand
	 * @param mixed the second operand
	 * @return Premanager\QueryList\QueryExpression
	 */
	public function exprAnd($operand0, $operand1) {
		if (!($operand0 instanceof QueryExpression))
			$operand0 = new QueryExpression($this->_modelType, $operand0);
		if (!($operand1 instanceof QueryExpression))
			$operand1 = new QueryExpression($this->_modelType, $operand1);
			
		return new QueryExpression($this->_modelType, QueryOperation::LOGICAL_AND,
			$operand0, $operand1);
	}
	
	/**
	 * Gets an MEMBER expression
	 * 
	 * @param Premanager\QueryList\QueryExpression|string $arg0 a MODEL expression
	 *   or the member name if the model is the object itself
	 * @param string|null $arg1 the member name
	 * @return Premanager\QueryList\QueryExpression
	 */
	public function exprMember($arg0, $arg1 = null) {
		if ($arg0 instanceof QueryExpression &&
			$arg0->type instanceof ModelDescriptor)
		{
			$object = $arg0;
			$type = $object->type;
			$memberName = $arg1;
		} else {
			$object = null;
			$type = $this->_modelType;
			$memberName = $arg0;
		}

		$member = $type->getMemberInfo($memberName);
		if (!$member)
			throw new ArgumentException('Member name ('.$memberName.') not found in '.
				'object descriptor');
			
		return new QueryExpression($type, QueryOperation::MEMBER, $object, $member);
	}
	
	private function getQueryBase() {
		if ($this->_queryBase === null) {
			$condition = $this->_filter ? $this->_filter->getQuery() : '';
			
			$this->_queryBase =
				"SELECT item.id ".
				"FROM ".DataBase::formTableName($this->_modelType->table)." AS item ".
				($condition ? "WHERE $condition " : "");
		}
		return $this->_queryBase;
	}
}

?>