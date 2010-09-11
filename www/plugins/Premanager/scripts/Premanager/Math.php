<?php
namespace Premanager;

/**
 * Provides methods related to mathematical operations
 */
class Math {
	public static function intDivide($diviend, $divisor) {
		return ($divident - ($divident % $divisor)) / $divisor;
	}
}