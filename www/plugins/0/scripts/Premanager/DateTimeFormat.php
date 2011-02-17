<?php
namespace Premanager;

/**
 * Specifies a special date/time format that is using the current environment
 */
class DateTimeFormat {
	/**
	 * Represents the long date time format (e.g. DDDD, MMMM D, YYYY, HH:MM)
	 */
	const LONG_DATE_TIME = 0x00;
	
	/**
	 * Represents the long date format (e.g. DDDD, MMMM D, YYYY)
	 */
	const LONG_DATE = 0x01;
	
	/**
	 * Represents the long time format (HH:MM)
	 */
	const LONG_TIME = 0x02;
	
	/**
	 * Represents the short date time format (e.g. YY-MM-DD HH:MM)
	 */
	const SHORT_DATE_TIME = 0x03;
	
	/**
	 * Represents the short date format (e.g. YY-MM-DD)
	 */
	const SHORT_DATE = 0x04;
	
	/**
	 * Represents the short time format (e.g. HH:MM)
	 */
	const SHORT_TIME = 0x05;
	
	/**
	 * Represents the date time phrase format (e.g. on DDDD, MMMM DDth, YYYY at HH:MM)
	 */
	const DATE_TIME_PHRASE = 0x06;
	
	/**
	 * Represents the long relative-to-now format (e.g. 7 hours, yesterday).
	 * Note: times in the future may seem equal to dates in the past.
	 */
	const SHORT_RELATIVE = 0x07;
	
	/**
	 * Represents the long relative-to-now format (e.g. 7 hours ago)
	 */
	const LONG_RELATIVE = 0x08;
}

?>