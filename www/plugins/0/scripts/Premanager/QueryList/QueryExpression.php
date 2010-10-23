<?php
namespace Premanager\QueryList;

use Premanager\TimeSpan;
use Premanager\DateTime;
use Premanager\InvalidOperationException;
use Premanager\Module;
use Premanager\Types;
use Premanager\Model;
use Premanager\QueryList\DataType;
use Premanager\ArgumentException;
use Premanager\ArgumentNullException;
use Premanager\InvalidEnumArgumentException;

/**
 * Defines an operating expression with one or more operands
 * 
 * The following operators are available:
 * - Premanager\QueryList\QueryOperation::MEMBER
 * - Premanager\QueryList\QueryOperation::NOT
 *   * !bool - $operand0 is a BOOLEAN expression
 * - Premanager\QueryList\QueryOperation::NEGATE
 *   * !bool - $operand0 is a NUMBER expression
 * - Premanager\QueryList\QueryOperation::NEGATE
 *   * !bool - $operand0 is a NUMBER expression
 *   
 */
class QueryExpression extends Module {
	/**
	 * @var int
	 */
	private $_operation;
	/**
	 * @var Premanager\QueryList\QueryExpression
	 */
	private $_operand0;
	/**
	 * @var Premanager\QueryList\QueryExpression
	 */
	private $_operand1;
	/**
	 * @var Premanager\QueryList\QueryExpression
	 */
	private $_operand2;
	/**
	 * @var Premanager\QueryList\MemberInfo
	 */
	private $_memberInfo;
	/**
	 * @var int|Premanager\QueryList\ModelDescriptor
	 */
	private $_type;
	/**
	 * @var mixed
	 */
	private $_value;
	/**
	 * @var Premanager\QueryList\ModelDescriptor
	 */
	private $_objectType;
	
	/**
	 * The result type (enum Premanager\QueryList\DataType or a
	 * remanager\QueryList\ModelDescriptor)
	 * 
	 * @var int|Premanager\QueryList\ModelDescriptor
	 */
	public $type = Module::PROPERTY_GET;
	
	/**
	 * The expression operation
	 * (enum Premanager\QueryList\QueryOperation)
	 * 
	 * @var int
	 */
	public $operation = Module::PROPERTY_GET;
	
	/**
	 * The literal value, if $operation is NONE
	 * 
	 * @var mixed
	 */
	public $value = Module::PROPERTY_GET;
	
	/**
	 * The type of "this"
	 * 
	 * @var Premanager\QueryList\Model
	 */
	public $objectType = Module::PROPERTY_GET;
	
	/**
	 * Creates a new QueryExpression.
	 * 
	 * If there is only one argument, this object will contain a literal value
	 * specified by this argument.
	 * 
	 * @param Premanager\QueryList\ModelDescriptor $objectType the type of "this"
	 * @param mixed $operation the operation
	 *   (enum Premanager\QueryList\QueryOperation)
	 * @param Premanager\QueryList\QueryExpression $operand0 the first operand
	 * @param Premanager\QueryList\QueryExpression|Premanager\QueryList\MemberInfo
	 *   $operand1 member, if $operatoin is MEMBER, the second operand otherwise
	 * @param Premanager\QueryList\QueryExpression $operand2 the third operand,
	 *   if needed
	 */
	public function __construct(ModelDescriptor $objectType, $operation,
	  QueryExpression $operand0 = null, $operand1 = null,
	  QueryExpression $operand2 = null) {
		parent::__construct();
		
	  $this->_objectType = $objectType;
	  
		// No operand specified, so $operation contains a constant
		if (\func_num_args() <= 2) {
			if (Types::isInteger($operation) || is_float($operation))
				$this->_type = DataType::NUMBER;
			else if (is_bool($operation))
				$this->_type = DataType::BOOLEAN;
			else if (is_string($operation))
				$this->_type = DataType::STRING;
			else if ($operation instanceof DateTime)
				$this->_type = DataType::DATE_TIME;
			else if ($operation instanceof TimeSpan)
				$this->_type = DataType::TIME_SPAN;
			else if ($operation instanceof Model) {
				$class = get_class($operation);
				$func = array($class, 'getDescriptor');
				if (!is_callable($func))
					throw new ArgumentException('The class of the argument ('.$class.
						') must implement the static getDescriptor() method', 'operand0');
				$this->_type = call_user_func($func);
				if (!$this->_type instanceof ModelDescriptor)
					throw new ArgumentException('The static method getDescriptor() of '.
						'the class of the argument ('.$class.') does not return a '.
						'Premanager\QueryList\ModelDescriptor.', 'operand0');
			} else if ($operation === null)
				throw new ArgumentNullException('operation');
			else
				throw new ArgumentException('The two-argument constructor expects a '.
					'number, bool, string, Premanager\DateTime, Premanager\TimeSpan or '.
					'model for the second argument.', 'operation');
			$this->_value = $operation;
			$this->_operation = QueryOperation::NONE;
		} else {
			switch ($operation) {
				case QueryOperation::MEMBER:
					if (!$operand1 || !($operand1 instanceof MemberInfo))
						throw new ArgumentException('Invalid operands for the MEMBER '.
							'operator');
					if ($operand0) {
						if (!($operand0->gettype() instanceof ModelDescriptor))
							throw new ArgumentException(
								'Invalid operands for the MEMBER operator');
						if ($operand1->getmodelDescriptor() != $operand0->gettype())
							throw new ArgumentException('The member is none of the '.
								'operand\'s model', 'operand1');
					} else {
						if ($operand1->getmodelDescriptor() != $this->_objectType)
							throw new ArgumentException('The member is none of the '.
								'object model of this expression', 'operand1');
					}
					$this->_operand0 = $operand0;
					$this->_memberInfo = $operand1;
					$this->_type = $this->_memberInfo->gettype();
					break;
					
				case QueryOperation::NOT:
					if (!$operand0 || $operand0->gettype() != DataType::BOOLEAN)
						throw new ArgumentException(
							'Invalid operands for the NOT operator');
					$this->_operand0 = $operand0;
					$this->_type = DataType::BOOLEAN;
					break;
					
				case QueryOperation::NEGATE:
					switch ($operand0 ? $operand0->gettype() : 0) {
						case DataType::NUMBER:
							$this->_operand0 = $operand0;
							$this->_type = DataType::NUMBER;
							break;
							
						case DataType::TIME_SPAN:
							$this->_operand0 = $operand0;
							$this->_type = DataType::TIME_SPAN;
							break;
							
						default:
							throw new ArgumentException(
								'Invalid operands for the NEGATE operator');
					}
					break;
					
				case QueryOperation::MULTIPLY:
				case QueryOperation::DIVIDE:
				case QueryOperation::MODULUS:
					if (!$operand0 || !$operand1 ||
						!($operand1 instanceof QueryExpression) ||
						$operand0->gettype() != DataType::NUMBER || 
						$operand1->gettype() != DataType::NUMBER)
						throw new ArgumentException(
							'Invalid operands for the arithmetic operator');
					$this->_operand0 = $operand0;
					$this->_operand1 = $operand1;
					$this->_type = DataType::NUMBER;
					break;
					
				case QueryOperation::SUBTRACT:
					if (!$operand0 || !$operand1 ||
						!($operand1 instanceof QueryExpression))
						throw new ArgumentException(
							'Invalid operands for the arithmetic operator');
					
					if ($operand0->gettype() == DataType::NUMBER && 
						$operand1->gettype() == DataType::NUMBER)
						$this->_type = DataType::NUMBER;
					else if ($operand0->gettype() == DataType::TIME_SPAN && 
						$operand1->gettype() == DataType::TIME_SPAN)
						$this->_type = DataType::TIME_SPAN;
					else if ($operand0->gettype() == DataType::DATE_TIME && 
						$operand1->gettype() == DataType::TIME_SPAN)
						$this->_type = DataType::DATE_TIME;
						
					$this->_operand0 = $operand0;
					$this->_operand1 = $operand1;
					break;
					
				case QueryOperation::ADD:
					if (!$operand0 || !$operand1 ||
						!($operand1 instanceof QueryExpression))
						throw new ArgumentException(
							'Invalid operands for the arithmetic operator');
					
					if ($operand0->gettype() == DataType::NUMBER && 
						$operand1->gettype() == DataType::NUMBER)
						$this->_type = DataType::NUMBER;
					else if ($operand0->gettype() == DataType::TIME_SPAN && 
						$operand1->gettype() == DataType::TIME_SPAN)
						$this->_type = DataType::TIME_SPAN;
					else if ($operand0->gettype() == DataType::DATE_TIME && 
						$operand1->gettype() == DataType::TIME_SPAN)
						$this->_type = DataType::DATE_TIME;
					else if ($operand0->gettype() == DataType::TIME_SPAN && 
						$operand1->gettype() == DataType::DATE_TIME) {
						$this->_type = DataType::DATE_TIME;
						
						// Change operand0 and operand1 so that the first operand is a
						// date/time (normalize)
						$tmp = $operand1;
						$operand1 = $operand0;
						$operand0 = $tmp;
					} else if ($operand0->gettype() == DataType::STRING && 
						$operand1->gettype() == DataType::STRING)
						$this->_type = DataType::STRING;
						
					$this->_operand0 = $operand0;
					$this->_operand1 = $operand1;
					break;
						
				case QueryOperation::LESS:
				case QueryOperation::GREATER:
				case QueryOperation::LESS_EQUAL:		
				case QueryOperation::GREATER_EQUAL:
					if (!$operand0 || !$operand1 ||
						!($operand1 instanceof QueryExpression) ||
						!(($operand0->gettype() == DataType::NUMBER && 
						$operand1->gettype() == DataType::NUMBER) ||
						($operand0->gettype() == DataType::TIME_SPAN && 
						$operand1->gettype() == DataType::TIME_SPAN) ||
						($operand0->gettype() == DataType::DATE_TIME && 
						$operand1->gettype() == DataType::DATE_TIME)))
						throw new ArgumentException(
							'Invalid operands for the comparing operator');
						
					$this->_operand0 = $operand0;
					$this->_operand1 = $operand1;
					$this->_type = DataType::BOOLEAN;
					break;
					
				case QueryOperation::EQUAL:		
				case QueryOperation::UNEQUAL:
					if (!$operand0 || !$operand1 ||
						!($operand1 instanceof QueryExpression) ||
						$operand0->gettype() != $operand1->gettype())
						throw new ArgumentException(
							'Invalid operands for the equality testing operator');
					$this->_operand0 = $operand0;
					$this->_operand1 = $operand1;
					$this->_type = DataType::BOOLEAN;
					break;
					
				case QueryOperation::LOGICAL_AND:
				case QueryOperation::LOGICAL_OR:
					if (!$operand0 || !$operand1 ||
						!($operand1 instanceof QueryExpression) ||
						$operand0->gettype() != DataType::BOOLEAN ||
						$operand1->gettype() != DataType::BOOLEAN)
						throw new ArgumentException(
							'Invalid operands for the logical operator');
					$this->_operand0 = $operand0;
					$this->_operand1 = $operand1;
					$this->_type = DataType::BOOLEAN;
					break;
					
				case QueryOperation::CONDITIONAL:		
					if (!$operand0 || !$operand1 || !$operand2 ||
						!($operand1 instanceof QueryExpression) ||
						$operand0->gettype() != DataType::BOOLEAN ||
						$operand1->gettype() != $operand2->gettype())
						throw new ArgumentException(
							'Invalid operands for the CONDITIONAL operator');
					$this->_operand0 = $operand0;
					$this->_operand1 = $operand1;
					$this->_operand2 = $operand2;
					$this->_type = $operand1->gettype();
					break;
					
				case QueryOperation::IS_NULL:		
					if (!$operand0)
						throw new ArgumentException(
							'Invalid operands for the IS_NULL operator');
					$this->_operand0 = $operand0;
					$this->_type = DataType::BOOLEAN;
					break;
					
				case QueryOperation::NONE:
					throw new ArgumentException('NONE is not a valid operator');
					
				
				default:
					throw new InvalidEnumArgumentException('operation', $operation,
						'Premanager\QueryList\QueryOperation');
			}
			$this->_operation = $operation;
		}
	}
	
	/**
	 * Gets the result type
	 * 
	 * @return int|string (enum Premanager\DataType)
	 */
	public function getType() {
		return $this->_type;
	}
	
	/**
	 * Gets the operation
	 * 
	 * @return int (enum Premanager\QueryOperation)
	 */
	public function getOperation() {
		return $this->_operation;
	}
	
	/**
	 * If the operation is NONE, gets the literal value
	 * 
	 * If the operation is not NONE, gets null.
	 * 
	 * @return mixed
	 */
	public function getValue() {
		return $this->_value;
	}
	
	/**
	 * Gets the type of "this"
	 * 
	 * @return Premanager\QueryList\ModelDescriptor
	 */
	public function getObjectType() {
		return $this->_objectType;
	}
	
	/**
	 * Checks whether a additional evaluation after the query is needed to
	 * evaluate this expression
	 * 
	 * @return bool
	 */
	public function isPostQueryEvaluationNeeded() {
		return true; //TODO: implement sql evaluation
	}
	
	/**
	 * Gets a query string for the expression part that can be represented as sql
	 * 
	 * @return string
	 */
	public function getQuery() {
		switch ($this->_operation) {
			case QueryOperation::NONE:
				switch ($this->_type) {
					case DataType::NUMBER:
					case DataType::STRING:
					case DataType::DATE_TIME: // overrides the __tostring() method
						return "'" . ((string)$this->_value) . "'";
					case DataType::BOOLEAN:
						return $this->_value ? "'1'" : "'0'";
					case DataType::TIME_SPAN:
						return "'" . $this->_value->getTimestamp() . "'";
				}
				break;
				
			case QueryOperation::MEMBER:
				if ($this->_operand0 == null && $this->_memberInfo->getFieldName()) {
					return $this->_memberInfo->getFieldName();
				}
				break;
				
			case QueryOperation::NOT:
				if ($op0 = $this->_operand0->getQuery())
					return 'NOT ('.$op0.')';
				break;
				
			case QueryOperation::NEGATE:
				if ($op0 = $this->_operand0->getQuery())
					return '-('.$op0.')';
				break;
				
			case QueryOperation::MULTIPLY:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())))
					return '('.$op0.') * ('.$op1.')';
				break;
				
			case QueryOperation::DIVIDE:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())))
					return '('.$op0.') / ('.$op1.')';
				break;
				
			case QueryOperation::MODULUS:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())))
					return '('.$op0.') % ('.$op1.')';
				break;
				
			case QueryOperation::ADD:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())))
				{
					switch ($this->_operand0->getType()) {
						case DataType::STRING:
							return 'CONCAT (('.$op0.'), ('.$op1.'))';
						case DataType::DATE_TIME:
							return 'DATE_ADD(('.$op0.'), INTERVAL ('.$op1.') SECOND)';
						default:
							return '(' . $op0 . ') + (' . $op1 . ')';
					}
				}
				break;
				
			case QueryOperation::SUBTRACT:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())))
				{
					switch ($this->_operand0->getType()) {
						case DataType::DATE_TIME:
							return 'DATE_SUB(('.$op0.'), INTERVAL ('.$op1.') SECOND)';
						default:
							return '('.$op0.') - ('.$op1.')';
					}
				}
				break;
				
			case QueryOperation::EQUAL:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())))
					return '('.$op0.') = ('.$op1.')';
				break;
				
			case QueryOperation::UNEQUAL:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())))
					return '('.$op0.') != ('.$op1.')';
				break;
				
			case QueryOperation::LESS:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())))
					return '('.$op0.') < ('.$op1.')';
				break;
				
			case QueryOperation::GREATER:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())))
					return '('.$op0.') > ('.$op1.')';
				break;
				
			case QueryOperation::LESS_EQUAL:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())))
					return '('.$op0.') <= ('.$op1.')';
				break;
				
			case QueryOperation::GREATER_EQUAL:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())))
					return '('.$op0.') >= ('.$op1.')';
				break;
				
			case QueryOperation::LOGICAL_AND:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())))
					return '('.$op0.') AND ('.$op1.')';
				break;
				
			case QueryOperation::LOGICAL_OR:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())))
					return '('.$op0.') OR ('.$op1.')';
				break;
				
			case QueryOperation::CONDITIONAL:
				if (($op0 = $this->_operand0->getQuery()) && 
					($op1 = ($this->_operand1->getQuery())) &&
					($op2 = $this->_operand2->getQuery()))
					return 'IF(('.$op0.'), ('.$op1.'), ('.$op2.'))';
				break;
				
			case QueryOperation::IS_NULL:
				if ($op0 = $this->_operand0->getQuery())
					return '('.$op0.') IS NULL';
				break;
		}
		return '';
	}
	
	/**
	 * Gets the value for this expression
	 * 
	 * @param mixed $object the object
	 * @param bool $queryEvaluated set to true if it is guaranteed that only
	 *   objects that match the query filter are passed (then some calculations
	 *   can be dropped) 
	 * @return mixed
	 */
	public function evaluate($object, $queryEvaluated = false) {
		$className = $this->_objectType->getClassName();
		if (!($object instanceof $className))
			throw ArgumentException('$object is of the wrong type, expected type: '.
				$this->_objectType->getclassName(), 'object');
		
		switch ($this->_operation) {
			case QueryOperation::NONE:
				return $this->_value;

			case QueryOperation::MEMBER:
				return $this->_memberInfo->getValue($this->_operand0 ?
					$this->_operand0->evaluate($object, $queryEvaluated) : $object);
					
			case QueryOperation::NOT:
				return !$this->_operand0->evaluate($object, $queryEvaluated);
					
			case QueryOperation::NEGATE:
				switch ($this->_operand0->getType()) {
					case DataType::NUMBER:
						return -$this->_operand0->evaluate($object, $queryEvaluated);
					case DataType::TIME_SPAN:
						return
							$this->_operand0->evaluate($object, $queryEvaluated)->negate();
				}
					
			case QueryOperation::MULTIPLY:
				return $this->_operand0->evaluate($object, $queryEvaluated) *
				  $this->_operand1->evaluate($object, $queryEvaluated);
					
			case QueryOperation::DIVIDE:
				return $this->_operand0->evaluate($object, $queryEvaluated) /
				  $this->_operand1->evaluate($object, $queryEvaluated);
					
			case QueryOperation::MODULUS:
				return $this->_operand0->evaluate($object, $queryEvaluated) %
				  $this->_operand1->evaluate($object, $queryEvaluated);
					
			case QueryOperation::ADD:
				switch ($this->_operand0->gettype()) {
					case DataType::NUMBER:
						return $this->_operand0->evaluate($object, $queryEvaluated) +
						  $this->_operand1->evaluate($object, $queryEvaluated);
					case DataType::STRING:
						return $this->_operand0->evaluate($object, $queryEvaluated) .
						  $this->_operand1->evaluate($object, $queryEvaluated);
					case DataType::TIME_SPAN:
					case DataType::DATE_TIME: // then the second must be a TIME_STAMP
						return $this->_operand0->evaluate($object, $queryEvaluated)->add(
						  $this->_operand1->evaluate($object, $queryEvaluated));
				}
					
			case QueryOperation::SUBTRACT:
				switch ($this->_operand0->gettype()) {
					case DataType::NUMBER:
						return $this->_operand0->evaluate($object, $queryEvaluated) -
						  $this->_operand1->evaluate($object, $queryEvaluated);
					case DataType::TIME_SPAN:
					case DataType::DATE_TIME:
						return $this->_operand0->evaluate($object, $queryEvaluated)->
							subtract($this->_operand1->evaluate($object, $queryEvaluated));
				}
					
			case QueryOperation::LESS:
				switch ($this->_operand0->gettype()) {
					case DataType::NUMBER:
						return $this->_operand0->evaluate($object, $queryEvaluated) <
						  $this->_operand1->evaluate($object, $queryEvaluated);
					case DataType::TIME_SPAN:
					case DataType::DATE_TIME:
						return $this->_operand0->evaluate($object,
							$queryEvaluated)->compareTo($this->_operand1->evaluate($object,
							$queryEvaluated), $queryEvaluated) < 0;
				}
					
			case QueryOperation::GREATER:
				switch ($this->_operand0->gettype()) {
					case DataType::NUMBER:
						return $this->_operand0->evaluate($object, $queryEvaluated) >
						  $this->_operand1->evaluate($object, $queryEvaluated);
					case DataType::DATE_TIME:
					case DataType::TIME_SPAN:
						return $this->_operand0->evaluate($object, 
							$queryEvaluated)->compareTo($this->_operand1->evaluate($object,
							$queryEvaluated), $queryEvaluated) > 0;
				}
					
			case QueryOperation::LESS_EQUAL:
				switch ($this->_operand0->gettype()) {
					case DataType::NUMBER:
						return $this->_operand0->evaluate($object, $queryEvaluated) <=
						  $this->_operand1->evaluate($object, $queryEvaluated);
					case DataType::DATE_TIME:
					case DataType::TIME_SPAN:
						return $this->_operand0->evaluate($object,
							$queryEvaluated)->compareTo($this->_operand1->evaluate($object,
							$queryEvaluated), $queryEvaluated) <= 0;
				}
					
			case QueryOperation::GREATER_EQUAL:
				switch ($this->_operand0->gettype()) {
					case DataType::NUMBER:
						return $this->_operand0->evaluate($object, $queryEvaluated) >=
						  $this->_operand1->evaluate($object, $queryEvaluated);
					case DataType::DATE_TIME:
					case DataType::TIME_SPAN:
						return $this->_operand0->evaluate($object,
							$queryEvaluated)->compareTo($this->_operand1->evaluate($object,
							$queryEvaluated), $queryEvaluated) >= 0;
				}
					
			case QueryOperation::EQUAL:
				switch ($this->_operand0->gettype()) {
					case DataType::DATE_TIME:
					case DataType::TIME_SPAN:
						return $this->_operand0->evaluate($object, $queryEvaluated)->equals(
						  $this->_operand1->evaluate($object, $queryEvaluated),
						  $queryEvaluated);
					default:
						return $this->_operand0->evaluate($object, $queryEvaluated) ==
						  $this->_operand1->evaluate($object, $queryEvaluated);
				}
					
			case QueryOperation::UNEQUAL:
				switch ($this->_operand0->gettype()) {
					case DataType::DATE_TIME:
					case DataType::TIME_SPAN:
						return !$this->_operand0->evaluate($object,
							$queryEvaluated)->equals($this->_operand1->evaluate($object,
							$queryEvaluated), $queryEvaluated);
					default:
						return $this->_operand0->evaluate($object) !=
						  $this->_operand1->evaluate($object);
				}

			case QueryOperation::LOGICAL_AND:
				return $this->_operand0->evaluate($object, $queryEvaluated) &&
					$this->_operand1->evaluate($object, $queryEvaluated);

			case QueryOperation::LOGICAL_OR:
				return $this->_operand0->evaluate($object, $queryEvaluated) ||
					$this->_operand1->evaluate($object, $queryEvaluated);

			case QueryOperation::CONDITIONAL:
				return $this->_operand0->evaluate($object, $queryEvaluated) ?
					$this->_operand1->evaluate($object, $queryEvaluated) :
					$this->_operand2->evaluate($object, $queryEvaluated);

			case QueryOperation::IS_NULL:
				return $this->_operand0->evaluate($object, $queryEvaluated) === null;
		}
	}
}

?>
