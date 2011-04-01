<?php
namespace Premanager\QueryList;

use Premanager\Debug\Debug;
use Premanager\IO\DataBase\DataBase;
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
	
	// ===========================================================================
	
	/**
	 * Creates a new QueryExpression.
	 * 
	 * @param Premanager\QueryList\ModelDescriptor $objectType the type of "this"
	 * @param mixed $operation the operation
	 *   (enum Premanager\QueryList\QueryOperation). NONE menas that $operand0 is
	 *   a literal
	 * @param Premanager\QueryList\QueryExpression $operand0 the first operand.
	 *   Needs to be specified if $operation is an operation!
	 * @param Premanager\QueryList\QueryExpression|Premanager\QueryList\MemberInfo
	 *   $operand1 member, if $operatoin is MEMBER, the second operand otherwise
	 * @param Premanager\QueryList\QueryExpression $operand2 the third operand,
	 *   if needed
	 */
	public function __construct(ModelDescriptor $objectType, $operation,
	  $operand0 = null, $operand1 = null, QueryExpression $operand2 = null) {
		parent::__construct();
		
	  $this->_objectType = $objectType;
	  
		switch ($operation) {
			case QueryOperation::MEMBER:
				if (!$operand1 || !($operand1 instanceof MemberInfo))
					throw new ArgumentException('Invalid operands for the MEMBER '.
						'operator');
				if ($operand0) {
					if (!($operand0->_type instanceof ModelDescriptor))
						throw new ArgumentException(
							'Invalid operands for the MEMBER operator');
					if ($operand1->getModelDescriptor() != $operand0->_type)
						throw new ArgumentException('The member is none of the '.
							'operand\'s model', 'operand1');
				} else {
					if ($operand1->getModelDescriptor() != $this->_objectType)
						throw new ArgumentException('The member is none of the '.
							'object model of this expression', 'operand1');
				}
				$this->_operand0 = $operand0;
				$this->_memberInfo = $operand1;
				$this->_type = $this->_memberInfo->getType();
				break;
				
			case QueryOperation::THIS:
				$this->_type = $this->_objectType;
				break;
				
			case QueryOperation::NOT:
				if (!$operand0 || $operand0->_type != DataType::BOOLEAN)
					throw new ArgumentException(
						'Invalid operands for the NOT operator');
				$this->_operand0 = $operand0;
				$this->_type = DataType::BOOLEAN;
				break;
				
			case QueryOperation::NEGATE:
				switch ($operand0 ? $operand0->_type : 0) {
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
					$operand0->_type != DataType::NUMBER || 
					$operand1->_type != DataType::NUMBER)
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
				
				if ($operand0->_type == DataType::NUMBER && 
					$operand1->_type == DataType::NUMBER)
					$this->_type = DataType::NUMBER;
				else if ($operand0->_type == DataType::TIME_SPAN && 
					$operand1->_type == DataType::TIME_SPAN)
					$this->_type = DataType::TIME_SPAN;
				else if ($operand0->_type == DataType::DATE_TIME && 
					$operand1->_type == DataType::TIME_SPAN)
					$this->_type = DataType::DATE_TIME;
					
				$this->_operand0 = $operand0;
				$this->_operand1 = $operand1;
				break;
				
			case QueryOperation::ADD:
				if (!$operand0 || !$operand1 ||
					!($operand1 instanceof QueryExpression))
					throw new ArgumentException(
						'Invalid operands for the arithmetic operator');
				
				if ($operand0->_type == DataType::NUMBER && 
					$operand1->_type == DataType::NUMBER)
					$this->_type = DataType::NUMBER;
				else if ($operand0->_type == DataType::TIME_SPAN && 
					$operand1->_type == DataType::TIME_SPAN)
					$this->_type = DataType::TIME_SPAN;
				else if ($operand0->_type == DataType::DATE_TIME && 
					$operand1->_type == DataType::TIME_SPAN)
					$this->_type = DataType::DATE_TIME;
				else if ($operand0->_type == DataType::TIME_SPAN && 
					$operand1->_type == DataType::DATE_TIME) {
					$this->_type = DataType::DATE_TIME;
					
					// Change operand0 and operand1 so that the first operand is a
					// date/time (normalize)
					$tmp = $operand1;
					$operand1 = $operand0;
					$operand0 = $tmp;
				} else if ($operand0->_type == DataType::STRING && 
					$operand1->_type == DataType::STRING)
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
					!(($operand0->_type == DataType::NUMBER && 
					$operand1->_type == DataType::NUMBER) ||
					($operand0->_type == DataType::TIME_SPAN && 
					$operand1->_type == DataType::TIME_SPAN) ||
					($operand0->_type == DataType::DATE_TIME && 
					$operand1->_type == DataType::DATE_TIME)))
					throw new ArgumentException(
						'Invalid operands for the comparing operator');
					
				$this->_operand0 = $operand0;
				$this->_operand1 = $operand1;
				$this->_type = DataType::BOOLEAN;
				break;
				
			case QueryOperation::EQUAL:		
			case QueryOperation::UNEQUAL:
				if (!$operand0 || (!$operand0->_memberInfo && (!$operand1 ||
					!($operand1 instanceof QueryExpression) ||
					$operand0->_type != $operand1->_type)))
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
					$operand0->_type != DataType::BOOLEAN ||
					$operand1->_type != DataType::BOOLEAN)
					throw new ArgumentException(
						'Invalid operands for the logical operator');
				$this->_operand0 = $operand0;
				$this->_operand1 = $operand1;
				$this->_type = DataType::BOOLEAN;
				break;
				
			case QueryOperation::CONDITIONAL:		
				if (!$operand0 || !$operand1 || !$operand2 ||
					!($operand1 instanceof QueryExpression) ||
					$operand0->_type != DataType::BOOLEAN ||
					$operand1->_type != $operand2->_type)
					throw new ArgumentException(
						'Invalid operands for the CONDITIONAL operator');
				$this->_operand0 = $operand0;
				$this->_operand1 = $operand1;
				$this->_operand2 = $operand2;
				$this->_type = $operand1->_type;
				break;
				
			case QueryOperation::IS_NULL:		
				if (!$operand0)
					throw new ArgumentException(
						'Invalid operands for the IS_NULL operator');
				$this->_operand0 = $operand0;
				$this->_type = DataType::BOOLEAN;
				break;
				
			case QueryOperation::NONE:
				if (Types::isInteger($operand0) || is_float($operand0))
					$this->_type = DataType::NUMBER;
				else if (is_bool($operand0))
					$this->_type = DataType::BOOLEAN;
				else if (is_string($operand0))
					$this->_type = DataType::STRING;
				else if ($operand0 instanceof DateTime)
					$this->_type = DataType::DATE_TIME;
				else if ($operand0 instanceof TimeSpan)
					$this->_type = DataType::TIME_SPAN;
				else if ($operand0 instanceof Model) {
					$class = get_class($operand0);
					$func = array($class, 'getDescriptor');
					if (!is_callable($func))
						throw new ArgumentException('The class of the argument ('.$class.
							') must implement the static getDescriptor() method', 'operand0');
					$this->_type = call_user_func($func);
					if (!$this->_type instanceof ModelDescriptor)
						throw new ArgumentException('The static method getDescriptor() of '.
							'the class of the argument ('.$class.') does not return a '.
							'Premanager\QueryList\ModelDescriptor.', 'operand0');
				} else
					throw new ArgumentException('Operation NONE expects '.
						'a number, bool, string, Premanager\DateTime, '.
						'Premanager\TimeSpan or model for the first operator.',
						'operand0');
				$this->_value = $operand0;
				$this->_operand0 = null;
				break;
			
			default:
				throw new InvalidEnumArgumentException('operation', $operation,
					'Premanager\QueryList\QueryOperation');
		}
		$this->_operation = $operation;
	}
	
	// ===========================================================================
	
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
	 * Gets a query string for the expression part that can be represented as sql
	 * 
	 * @return string the query or null if this expression can not be represented
	 *   in a query
	 */
	public function getQuery() {
		switch ($this->_operation) {
			case QueryOperation::NONE:
				if ($this->_type instanceof ModelDescriptor)
					return $this->_value->getID();
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
				if ($this->_operand0 == null)
					return $this->_memberInfo->getFieldQuery();
				break;
				
			case QueryOperation::THIS:
				if ($this->_operand0 == null)
					return 'item.id';
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
					switch ($this->_operand0->_type) {
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
					switch ($this->_operand0->_type) {
						case DataType::DATE_TIME:
							return 'DATE_SUB(('.$op0.'), INTERVAL ('.$op1.') SECOND)';
						default:
							return '('.$op0.') - ('.$op1.')';
					}
				}
				break;
				
			case QueryOperation::EQUAL:
				if ($this->_operand0->_type instanceof ModelDescriptor) {
					if (!$this->_operand0->_operation &&
						$field = $this->_operand1->getQuery())
					{
						$id = $this->_operand0->_value->getID();
						return '('.$field.') = '.DataBase::escape($id); 
					}
					else if (!$this->_operand1->_operation &&
						$field = $this->_operand0->getQuery())
					{
						$id = $this->_operand1 ? $this->_operand1->_value->getID() : 0;
						return '('.$field.') = '.DataBase::escape($id); 
					}
				}
				
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
		return null;
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

			case QueryOperation::THIS:
				return $object;
					
			case QueryOperation::NOT:
				return !$this->_operand0->evaluate($object, $queryEvaluated);
					
			case QueryOperation::NEGATE:
				switch ($this->_operand0->_type) {
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
				switch ($this->_operand0->_type) {
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
				switch ($this->_operand0->_type) {
					case DataType::NUMBER:
						return $this->_operand0->evaluate($object, $queryEvaluated) -
						  $this->_operand1->evaluate($object, $queryEvaluated);
					case DataType::TIME_SPAN:
					case DataType::DATE_TIME:
						return $this->_operand0->evaluate($object, $queryEvaluated)->
							subtract($this->_operand1->evaluate($object, $queryEvaluated));
				}
					
			case QueryOperation::LESS:
				switch ($this->_operand0->_type) {
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
				switch ($this->_operand0->_type) {
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
				switch ($this->_operand0->_type) {
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
				switch ($this->_operand0->_type) {
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
				switch ($this->_operand0->_type) {
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
				switch ($this->_operand0->_type) {
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
