<?php
namespace Premanager;

/**
 * Specifies a time span format that is using the current environment
 */
class TimeSpanFormat {
	/**
	 * Represents the short time span format (e.g. 2 min)
	 */
	const SHORT = 0x02;
	
	/**
	 * Represents the long time span format (e.g. 2 minutes)
	 */
	const LONG = 0x03;
	
	/**
	 * Represents the long relative-to-now format (e.g. 7 hours, yesterday).
	 * Note: times in the future may seem equal to dates in the past.
	 */
	const SHORT_RELATIVE_TO_NOW = 0x02;
	
	/**
	 * Represents the long relative-to-now format (e.g. 7 hours ago)
	 */
	const LONG_RELATIVE_TO_NOW = 0x03;
}

?>