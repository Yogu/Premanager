<?php
namespace Premanager\Execution;

/**
 * Defines the position for a new block to be appended to a page
 */
class PageBlockAppendMode {
	/**
	 * Appends the block at the bottom of block list
	 * @var int
	 */
	const BOTTOM = 0;
	
	/**
	 * Appends the block at the top of block list
	 * @var int
	 */
	const TOP = 1;
	
	/**
	 * Appends the block near to main bloock
	 * @var int
	 */
	const NEAREST = 2;
	
	/**
	 * Appends the block at the most far away position from main block
	 * @var int
	 */
	const FAREST = 3; 
}