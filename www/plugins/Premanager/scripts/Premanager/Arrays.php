<?php
namespace Premanager;

/**
 * Provides several methods related to arrays
 */
class Arrays {
	/**
	 * Gets the count of items in an array
	 * @param array $array array to check
	 */
	public static function count(array $array) {
		return \count($array);
	}
}