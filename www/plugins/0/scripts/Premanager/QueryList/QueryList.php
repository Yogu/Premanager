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
	 * @var array
	 */
	private $_sortRules = array();
	/**
	 * @var Premanager\QueryList\QueryListStrategy
	 */
	private $_strategy;

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
	 * @param Premanager\QueryList\ModelDescriptor $modelType the type of items
	 * @param Premanager\QueryList\QueryExpression $filter a filter the items 
	 *   must match
	 * @param array $sortRules an array of Premanager\QueryList\SortRule objects
	 * 
	 * @throws Premanager\ArgumentException $filter is not valid for lists of the
	 *   type specified by $modelType - or - $filter contains invalid elements
	 */
	public function __construct(ModelDescriptor $modelType,
		QueryExpression $filter = null, array $sortRules = array()) {
		parent::__construct();
		
		$this->_modelType = $modelType;
		
		if ($filter !== null) {
			if ($filter->getObjectType() != $modelType)
				throw new ArgumentException('The filter is not valid for lists of the '.
					'type specified by modelType (see getObjectType())', 'filter');
			$this->_filter = $filter;
		}
		
		if (count($sortRules)) {
			$this->checkSortRules($sortRules);
			$this->_sortRules = $sortRules;
		}
	}

	// ===========================================================================
	
	/**
	 * Gets the model descriptor for the models contained by this list
	 * 
	 * @return Premanager\QueryList\ModelDescriptor the model descriptor for the
	 *   models contained by this list
	 */
	public function getModelType() {			
		return $this->_modelType;
	}
	
	/**
	 * Gets the filter used for this query list
	 * 
	 * @return Premanager\QueryList\QueryExpression the expression used as filter
	 *   or null if the query list is not filtered
	 */
	public function getFilter() {			
		return $this->_filter;
	}
	
	/**
	 * Gets the sort rules specified for this query list
	 * 
	 * @return array an array of Premanager\QueryList\SortRule objects
	 */
	public function getSortRules() {			
		return $this->_sortRules;
	}
	
	/**
	 * Gets the count of items
	 * 
	 * @return int the count of items
	 */
	public function getCount() {
		return $this->getStrategy()->getCount();
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
		return $this->getStrategy()->getAll();
	}
	
	/**
	 * Gets an item speicified by its index
	 * 
	 * @param int $index the index of the object
	 * @return mixed the object
	 */
	public function getByIndex($index) {
		return $this->getStrategy()->getByIndex($index);
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
		return $this->getStrategy()->getRange($index, $count, $weakRangeCheck);
	}
	
	/**
	 * Finds an item in this list and gets its index
	 * 
	 * @param Premanager\Model $item the item to find
	 * @return int the index of the item or -1 if it is not in this list
	 */
	public function indexOf(Model $item) {
		foreach ($this as $index => $o) {
			if ($o == $item)
				return $index;
		}
		return -1;
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
			
		if ($condition->getType() != DataType::BOOLEAN)
			throw new ArgumentException('Only BOOLEAN expressions are valid for the '.
				'$condition parameter', 'condition');
		
		if ($condition->getValue() === true)
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
	 * The order of items that can not be sorted with the speicified rules is
	 * random.
	 * 
	 * If this list is already sorted, the sort rules of this list are ignored in
	 * the returned list.
	 * 
	 * @param array $rules an array of QueryExpressions
	 * @return QueryList a list of all items of this list in a specified order
	 * 
	 * @throws Premanager\ArgumentException $rules contains an invalid element
	 */
	public function sort(array $rules) {
		// If there is nothing to sort and this list is not sorted, too, return this
		// list
		if (!count($rules) && !count($this->_sortRules))
			return $this;
			
		$this->checkSortRules($rules);
		return new QueryList($this->_modelType, $this->_filter, $rules);
	}
	
	/**
	 * Checks whether an offset is in range
	 * 
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset) {
		return \Types::isInteger($offset) && $offset >= 0 && $offset < $this->getcount();
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
	 * @param mixed the second operand
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
	 * Gets an UNEQUAL expression
	 *
	 * @param mixed $operand0 the first operand
	 * @param mixed the second operand
	 * @return Premanager\QueryList\QueryExpression
	 */
	public function exprUnequal($operand0, $operand1) {
		if (!($operand0 instanceof QueryExpression))
			$operand0 = new QueryExpression($this->_modelType, $operand0);
		if (!($operand1 instanceof QueryExpression))
			$operand1 = new QueryExpression($this->_modelType, $operand1);	
			
		return new QueryExpression($this->_modelType, QueryOperation::UNEQUAL,
			$operand0, $operand1);
	}
	
	/**
	 * Gets an expression
	 *
	 * @param int operation enum Premanager\QueryList\QueryOperation 
	 * @param mixed $operand0 the first operand
	 * @param mixed $operand1 the second operand
	 * @param mixed $operand2 the third operand
	 * @return Premanager\QueryList\QueryExpression
	 */
	public function expr($operation, $operand0, $operand1 = null,
		$operand2 = null) {
		if ($operand0 != null && !($operand0 instanceof QueryExpression))
			$operand0 = new QueryExpression($this->_modelType, $operand0);
		if ($operand1 != null && !($operand1 instanceof QueryExpression))
			$operand1 = new QueryExpression($this->_modelType, $operand1);
		if ($operand2 != null && !($operand2 instanceof QueryExpression))
			$operand2 = new QueryExpression($this->_modelType, $operand2);
			
		return new QueryExpression($this->_modelType, $operation,
			$operand0, $operand1, $operand2);
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
			$arg0->gettype() instanceof ModelDescriptor)
		{
			$object = $arg0;
			$type = $object->gettype();
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
	
	/**
	 * Checks an array of sort rules for validity on this list
	 * 
	 * @throws ArgumentException if the sort rules are invalid
	 */
	private function checkSortRules() {
		for ($i = 0; $i < count($sortRules); $i++) {
			if (!($rule instanceof SortRule))
				throw new ArgumentException('Item '.$i.' of the $sortRules array '.
					'is not an instance of Premanager\QueryList\SortRule', 'sortRules');
			if ($rule->getobjectType() != $modelType)
				throw new ArgumentException('Item '.$i.' of the $sortRules array '.
					'is valid for lists fo the type specified by modelType (see '.
					'getObjectType())', 'sortRules');
			}
	}
	
	/**
	 * Gets the strategy to be used for this query
	 * 
	 * @return Premanager\QueryList\QueryListStrategy
	 */
	private function getStrategy() {
		if (!$this->_strategy) {
			$filterQuery = $this->_filter ? $this->_filter->getQuery() : '';
			if ($filterQuery !== null) {
				$sortQueries = array();
				$ok = true;
				foreach ($this->_sortRules as $rule) {
					if (($sortQueries[] = $rule->getQuery()) == null) {
						$ok = false;
						break;
					}
				}
				if ($ok)
					$this->_strategy =
						new FullQueryQueryListStrategy($this, $filterQuery, $sortQueries);
			}
			if (!$this->_strategy)
				$this->_strategy = new PHPQueryListStrategy($this);
		}
		return $this->_strategy;
	}
}

?>
