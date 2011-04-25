<?php
namespace Premanager\Modeling;

class QueryOperation {
	const NONE = 0;
	
	/**
	 * The member access operator
	 * 
	 * Accesses a member of an object
	 * - MODEL.MEMBER: Accesses a member of an object
	 * - null.MEMBER: Accesses a member of this
	 * @var int
	 */
	const MEMBER = 1;
	
	/**
	 * The this operator
	 * 
	 * Accesses the model itself. Does not have any parameters.
	 * @var int
	 */
	const THIS = 19;
	
	/**
	 * The bool not operator
	 * 
	 * - !BOOLEAN: negates a bool value
	 * @var int
	 */
	const NOT = 2;
	
	/**
	 * The number operator
	 * 
	 * - -NUMBER: Multiplies a number with (-1) 
	 * - -TIME_STAMP: Changes the sign (2 hours gets -2 hours)
	 * @var int
	 */
	const NEGATE = 3;
	
	/**
	 * The multiply operator
	 * 
	 * - NUMBER * NUMBER: Multiplies two numbers
	 * @var int
	 */
	const MULTIPLY = 4;
	
	/**
	 * The divide operator
	 * 
	 * - NUMBER / NUMBER: Divides a number by another number
	 * @var int
	 */
	const DIVIDE = 5;
	
	/**
	 * The modulus operator
	 * 
	 * - NUMBER % NUMBER: Calculates the modulus of a division
	 * @var int
	 */
	const MODULUS = 6;
	
	/**
	 * The add and string-concat operator
	 * 
	 * - NUMBER + NUMBER: Adds two numbers
	 * - STRING + STRING: Concats two strings
	 * - TIME_SPAN + TIME_SPAN: Adds two time spans 
	 * - DATE_TIME + TIME_SPAN: Adds a time span to a date/time
	 * @var int
	 */
	const ADD = 7;
	
	/**
	 * The subtract operator
	 * 
	 * - NUMBER - NUMBER: Subtracts two numbers
	 * - TIME_SPAN - TIME_SPAN: Subtracts two time spans
	 * - DATE_TIME - TIME_SPAN: Subtracts a time span off a date/time
	 * @var int
	 */
	const SUBTRACT = 8;
	
	/**
	 * The less-than operator
	 * 
	 * - NUMBER < NUMBER: Determines whether a number is less than another
	 * - TIME_SPAN < TIME_SPAN: Determines whether a time span is less than
	 *   another
	 * - DATE_TIME < DATE_TIME: Determines whether a date/time is earlier than
	 *   another
	 * @var int
	 */
	const LESS = 9;
	
	/**
	 * The greater-than operator
	 * 
	 * - NUMBER > NUMBER: Determines whether a number is greater than another
	 * - TIME_SPAN < TIME_SPAN: Determines whether a time span is greater than
	 *   another
	 * - DATE_TIME > DATE_TIME: Determines whether a date/time is later than
	 *   another
	 * @var int
	 */
	const GREATER = 10;
	
	/**
	 * The less-than-or-equal operator
	 * 
	 * - NUMBER <= NUMBER: Determines whether a number is less than another or
	 *   equals it
	 * - TIME_SPAN <= TIME_SPAN: Determines whether a time span is less than
	 *   another or equals it
	 * - DATE_TIME <= DATE_TIME: Determines whether a date/time is earlier than
	 *   another or equals it
	 * @var int
	 */
	const LESS_EQUAL = 11;
	
	/**
	 * The greater-than-or-equal operator
	 * 
	 * - NUMBER >= NUMBER: Determines whether a number is greater than another or
	 *   equals it
	 * - TIME_SPAN >= TIME_SPAN: Determines whether a time span is greater than
	 *   another or equals it
	 * - DATE_TIME >= DATE_TIME: Determines whether a date/time is later than
	 *   another or equals it
	 * @var int
	 */
	const GREATER_EQUAL = 12;
	
	/**
	 * The equal operator
	 * 
	 * - NUMBER == NUMBER: Determines whether a number equals another
	 * - BOOLEAN == BOOLEAN: Determines whether a boolean equals another
	 * - STRING == STRING: Determines whether a string equals another
	 * - DATE_TIME == DATE_TIME: Determines whether a date/time equals another
	 * - TIME_SPAN == TIME_SPAN: Determines whether a time span equals another
	 * - MODEL == MODEL: Determines whether a model is the same as another
	 * @var int
	 */
	const EQUAL = 13;
	
	/**
	 * The unequal operator
	 * 
	 * - NUMBER == NUMBER: Determines whether a number does not equal another
	 * - BOOLEAN == BOOLEAN: Determines whether a boolean does not equal another
	 * - STRING == STRING: Determines whether a string does not equal another
	 * - DATE_TIME == DATE_TIME: Determines whether a date/time does not equal
	 *   another
	 * - TIME_SPAN == TIME_SPAN: Determines whether a time span does not equal
	 *   another
	 * - MODEL == MODEL: Determines whether a model is not the same as another
	 * @var int
	 */
	const UNEQUAL = 14;
	
	/**
	 * The logical and operator
	 * 
	 * - BOOLEAN && BOOLEAN: Returns true if both operators are true, false
	 *   otherwise
	 * @var int
	 */
	const LOGICAL_AND = 15;
	
	/**
	 * The logical or operator
	 * 
	 * - BOOLEAN && BOOLEAN: Returns true if at least one of the operators are
	 *   true, false otherwise
	 * @var int
	 */
	const LOGICAL_OR = 16;
	
	/**
	 * The ternary conditional operator
	 * 
	 * - BOOL ? TYPE0 : TYPE0 (TYPE0 is a various type but two times the same):
	 *   Returns the second operand if the first is true, the third one otherwise 
	 * @var int
	 */
	const CONDITIONAL = 17;
	
	/**
	 * Tests an expression to be null
	 * 
	 * - IS_NULL(TYPE0) (TYPE0 is a various type):
	 *   Returns true if the operator is null, false otherwise 
	 * @var int
	 */
	const IS_NULL = 18;
}
