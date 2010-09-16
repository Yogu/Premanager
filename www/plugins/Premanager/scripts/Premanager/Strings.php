<?php
namespace Premanager;

/**
 * Provides several methods related to utf-8 strings
 */
class Strings {
	/**
	 * Gets the length of a string
	 * 
	 * @param string $str
	 * @return int
	 */
	public static function length($str) {
		return mb_strlen($str, 'utf-8');
	}
	
	/**
	 * Transforms a string to upper case
	 * 
	 * @param string $str
	 * @return string
	 */
	public static function toUpper($str) {
		return mb_strtoupper($str, 'utf-8');
	}
	
	/**
	 * Transforms a string to upper case
	 * 
	 * @param string $str
	 * @return string
	 */
	public static function toLower($str) {
		return mb_strtolower($str, 'utf-8');
	}
	
	/**
	 * Gets a part of a string
	 * 
	 * @param string $str
	 * @param int $start the start index of the part
	 * @param int $length the length of the part
	 * @return string
	 */
	public static function substring($str, $start, $length = null) {
		if (func_num_args() == 2)
			return mb_substr($str, $start, self::length($str), 'utf-8');
		else
			return mb_substr($str, $start, $length, 'utf-8');
	}
	
	/**
	 * Gets the index of the first occurance of a string in another string
	 * Enter description here ...
	 * @param string $haystack the string being checked
	 * @param string $needle the string to find
	 * @param int $offset the search offset
	 */
	public static function indexOf($haystack, $needle, $offset = 0) {
		return mb_strpos($haystack, $needle, $offset, 'utf-8');
	}
	
	/**
	 * Finds the last occurance of a needle and returns the part beginning at the
	 * index of the occurance.
	 * 
	 * @param string $haystack the string to search in
	 * @param string $needle the string to find
	 * @return string the part beginning at the last occurance of $needle
	 */
	public static function strrchr($haystack, $needle) {
		return mb_strrchr($haystack, $needle, false, 'utf-8');
	}
	
	// 
	/**
	 * Replaces whitespace sequences by a single space and removes control chars
	 * 
	 * @param string $name
	 * @return string
	 */
	public static function normalize($name) {
		return trim(preg_replace('/\x00-\x1F/', '',
			preg_replace('/[\s]+/', ' ', $name)));
	}
	
	// Returns a lower-case, trimmed and escaped version of $name which can be
	// compared to LOWER()-converted data base names
	/**
	 * Gets the normalizes lower-case variant of a string
	 * 
	 * @param string $name
	 * @return string
	 */
	public static function unitize($name) {
	 	return self::toLower(self::normalize($name));
	}   
}

?>
