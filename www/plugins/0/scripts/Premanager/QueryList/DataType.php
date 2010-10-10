<?php
namespace Premanager\QueryList;

/**
 * Defines the type of a field or expression
 */
class DataType {
	/**
	 * Specifies that the data type is not known or irrelevant
	 * @var int
	 */
	const NONE = 0;
	/**
	 * An integer or float
	 * @var int
	 */
	const NUMBER = 1;
	/**
	 * A bool value (true of false)
	 * @var int
	 */
	const BOOLEAN = 2;
	/**
	 * A unicode string
	 * @var int
	 */
	const STRING = 3;
	/**
	 * A point of time
	 * @var int
	 */
	const DATE_TIME = 4;
	/**
	 * The difference between two time spans
	 * @var int
	 */
	const TIME_SPAN = 5;
}

?>
