<?php
namespace Premanager\QueryList;

use Premanager\InvalidEnumArgumentException;
use Premanager\ArgumentNullException;
use Premanager\ArgumentException;
use Premanager\Module;
use Premanager\Types;

/**
 * A single rule how to sort elements in a list
 */
class SortRule extends Module{
	/**
	 * @var Premanager\QueryList\QueryExpression
	 */
	private $_expression;
	/**
	 * @var int
	 */
	private $_direction;
	
	/**
	 * The expression that defines the order
	 * 
	 * @var Premanager\QueryList\QueryExpression
	 */
	public $expression = Module::PROPERTY_GET;
	
	/**
	 * The direction in which to sort (enum Premanager\QueryList\SortDirection)
	 * 
	 * @var int
	 */
	public $direction = Module::PROPERTY_GET;
	
	/**
	 * The type of "this"
	 * 
	 * @var Premanager\QueryList\Model
	 */
	public $objectType = Module::PROPERTY_GET;
	
	/**
	 * Creates a new SortRules and sets its properties
	 * 
	 * @param Premanager\QueryList\QueryExpression the expression that defines the
	 *   order; must be one of NUMBER, BOOLEAN, STRING, DATE_TIME or TIME_SPAN
	 * @param int $direction the direction in which to sort
	 *   (enum Premanager\QueryList\SortDirection); default is ASCENDING
	 */
	public function __construct(QueryExpression $expression, $direction =
		SortDirection::ASCENDING) {
		parent::__construct();
		
		switch ($expression->type) {
			case DataType::NUMBER:
			case DataType::BOOLEAN:
			case DataType::STRING:
			case DataType::DATE_TIME:
			case DataType::TIME_SPAN:
				break;
			default:
				throw new ArgumentException('The expression is not of a comparable '.
					'type´', 'expression');
		}
		
		switch ($direction) {
			case SortDirection::ASCENDING:
			case SortDirection::DESCENDING:
				break;
			default:
				throw new InvalidEnumArgumentException('direction', $direction,
					'Premanager\QueryList\SortDirection');
		}
		
		$this->_expression = $expression;
		$this->_direction = $direction;
	}
	
	/**
	 * Gets the expression that defines the order
	 * 
	 * @return Premanager\QueryList\QueryExpression
	 */
	public function getExpression() {
		return $this->_expression;
	}
	
	/**
	 * Gets the direction in which to sort
	 * (enum Premanager\QueryList\SortDirection)
	 * 
	 * @return int
	 */
	public function getDirection() {
		return $this->_direction;
	}
	
	/**
	 * Gets the type of models this rule can be evaluated on
	 * 
	 * @return Premanager\QueryList\ModelDescriptor
	 */
	public function getObjectType() {
		return $this->_expression->getObjectType();
	}
	
	/**
	 * Compares two models of the type specified by the $objectType property
	 * 
	 * @param Premanager\Model $obj0 the left-hand operand
	 * @param Premanager\Model $obj1 the right-hand operand
	 * @return int a value less than zero if $obj1 has to be ordered higher than
	 *   $obj0, zero if their order is equal or a value greater than zero if $obj0
	 *   has to be ordered higher than $obj1
	 * @throws Premanager\ArgumentException one of the arguments is of the wrong
	 *   type
	 */
	public function evaluate($obj0, $obj1) {
		if (!($obj0 instanceof $this->_expression->objectType->className))
			throw ArgumentException('$obj0 is of the wrong type, expected type: '.
				$this->_expression->objectType->className, 'obj0');
		if (!($obj1 instanceof $this->_expression->objectType->className))
			throw ArgumentException('$obj1 is of the wrong type, expected type: '.
				$this->_expression->objectType->className, 'obj1');
				
		$value0 = $this->_expression->evaluate($obj0);
		$value1 = $this->_expression->evaluate($obj1);
		switch ($this->_expression->objectType) {
			case DataType::NUMBER:
			case DataType::BOOLEAN:
			case DataType::STRING:
				$result = $value0 > $value1 ? 1 : ($value0 < $value1 ? -1 : 0);
				break;
			case DataType::DATE_TIME:
			case DataType::TIME_SPAN:
				$result = $value0->compareTo($value1);
				break;
		}
		
		if ($this->_direction == SortDirection::DESCENDING)
			$result = -$result;
		return $result;
	}
}

?>
