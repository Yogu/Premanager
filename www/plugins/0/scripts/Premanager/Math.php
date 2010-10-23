<?php
namespace Premanager;

use Premanager\Debug\Debug;

/**
 * Provides methods related to mathematical operations
 */
class Math {
	public static function intDivide($dividend, $divisor) {
		return ($dividend - ($dividend % $divisor)) / $divisor;
	}
}