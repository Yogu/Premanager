<?php
namespace Premanager;

/**
 * Provides methods related to type testing
 */
class Types {
	/**
	 * Checks whether the argument is an integer or the string representation of
	 * an integer
	 * 
	 * @param mixed $value
	 * @return bool
	 */
	public static function isInteger($value) {
		// If a string equals its integer representation, it must equal the integer
		// itself
		return is_int($value) || (int)$value == $value;
	}

	/**
	 * Checks whether the argument is numeric
	 * 
	 * @param mixed $value
	 * @return bool
	 */
	public static function isNumeric($value) {
		return is_numeric($value);
	}

	/**
	 * Checks whether the argument is a float
	 * 
	 * @param mixed $value
	 * @return bool
	 */
	public static function isFloat($value) {
		return is_float($value);
	}

	/**
	 * Checks whether the argument is a string
	 * 
	 * @param mixed $value
	 * @return bool
	 */
	public static function isString($value) {
		return is_string($value);
	}
	
	/**
	 * Checks whether the argument is an array
	 * 
	 * @param mixed $value
	 * @return bool
	 */
	public static function isArray($value) {
		return is_array($value);
	}
	
	/**
	 * Checks whether the argument is a boolean
	 * 
	 * @param mixed $value
	 * @return bool
	 */
	public static function isBool($value) {
		return is_bool($value);
	}
}

?>
