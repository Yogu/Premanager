<?php
namespace Premanager\Modeling;

/**
 * Specifies the direction in which to sort elements of a list	
 */
class SortDirection {
	/**
	 * Sorts a list from smallest to highest (for example from A to Z)
	 * @var int
	 */
	const ASCENDING = 0;
	
	/**
	 * Sorts a list from highest to lowest (for example from Z to A)
	 * @var int
	 */
	const DESCENDING = 1;
}

?>