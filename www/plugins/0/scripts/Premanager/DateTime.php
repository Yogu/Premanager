<?php
namespace Premanager;

use Premanager\Debug\Debug;
use Premanager\Execution\Translation;
use Premanager\TimeSpan;

/**
 * A date time value combined with a time zone reference
 */
use Premanager\Execution\Environment;

class DateTime extends Module {
	private $_universalTimestamp;
	private $_timestamp;
	private $_timeZone;
	
	// ===========================================================================
	
	/**
	 * Specifies the format __tostring() and __construct(string) use
	 * 
	 * @var string
	 */
	const DEFAULT_FORMAT = 'Y-m-d H:i:s';
	
	// ===========================================================================
	
	/**
	 * Creates a new Premanager\DateTime object
	 * 
	 * There are several possibilities to call:
	 * 
	 * (no parameter): time is now, time zone is UTC
	 * string: datetime string in format YYYY-MM-DD[-HH-MM-SS], time zone is UTC
	 * int: timestamp, time zone is UTC
	 * TimeZone: time zone, time is now
	 * string, TimeZone: like string, time zone specified
	 * int, TimeZone: timestamp, time zone
	 * int, int, int: year, month and day component, midnight, time zone is UTC
	 * int, int, int, TimeZone: like int, int, int, time zone specified
	 * int, int, int, int, int, int: year, month, day, hour, minute second; UTC
	 * int, int, int, int, int, int, TimeZone: like above, time zone specified
	 * 
	 * @param int|string|TimeZone $arg0 one parameter: timestamp, datetime string
	 *   or time zone; else: year component
	 * @param int|TimeZone $arg1 two parameters: time zone; else: month component
	 * @param int|null $arg2 day component
	 * @param int|TimeZone $arg3 four parameters: time zone; else: hour component
	 * @param int|null $arg4 minute component
	 * @param int|null $arg5 second component
	 * @param TimeZone $arg6 time zone 
	 */
	public function __construct($arg0 = null, $arg1 = null, $arg2 = null,
		$arg3 = null, $arg4 = null, $arg5 = null, TimeZone $arg6 = null) {
		parent::__construct();
			
		if ($arg6 != null) {
			$year = $arg0;
			$month = $arg1;
			$day = $arg2;
			$hour = $arg3;
			$minute = $arg4;
			$second = $arg5;
			$timeZone = $arg6;
		} else if ($arg5 != null) {
			$year = $arg0;
			$month = $arg1;
			$day = $arg2;
			$hour = $arg3;
			$minute = $arg4;
			$second = $arg5;
		} else if ($arg3 != null) {
			$year = $arg0;
			$month = $arg1;
			$day = $arg2;
			$timeZone = $arg3;
		} else if ($arg2 != null) {
			$year = $arg0;
			$month = $arg1;
			$day = $arg2;
		} else {
			if ($arg1 != null) {
				if (!($arg1 instanceof TimeZone))
					throw new ArgumentException('$arg1 must be a TimeZone if two '.
						'arguments are specified');
				$timeZone = $arg1;
			}
			if ($arg0 === null) {
				$timestamp = time();
			} else if ($arg0 instanceof TimeZone) {
				$timeZone = $arg0;
				$timestamp = time();
			} else if (Types::isInteger($arg0)) {
				$timestamp = $arg0;
			} else {
				$obj = \DateTime::createFromFormat(self::DEFAULT_FORMAT,
					(string) $arg0);
				if (!$obj)
					throw new ArgumentException('$arg0 is neither a an integer or a '.
						'Premanager\TimeZone object, nor a vaild time string', 'arg0');
				$timestamp = $obj->getTimestamp();
			}
		}
		
		if (!$timeZone)
			$timeZone = TimeZone::getUTC();
		
		if (!$timestamp) {
			// Check component parameters
			if (!Types::isInteger($year))
				throw new ArgumentException('$arg0 must be an integer if three '.
					'or four arguments are passed', 'time');
			if (!Types::isInteger($month))
				throw new ArgumentException('$arg1 must be an integer', 'month');
			if ($month < 1 || $month > 12)
				throw new ArgumentOutOfRangeException('month', $month,
					'$arg1 must be a value between 1 and 12');
			if (!Types::isInteger($day))
				throw new ArgumentException('$arg2 must be an integer', 'day');
			if ($day < 1 || $day > self::daysInMonth($year, $month))
				throw new ArgumentOutOfRangeException('day', '$day',
					'$arg2 must be a value between 1 and '.self::daysInMonth($year,
					$month).' (the count of days in the month');
			
			if ($hour !== null || $minute !== null || $second !== null) {
				if ($hour === null || $minute === null || $second === null)
					throw new ArgumentException('Invalid arguments passed');
					
				if (!Types::isInteger($hour))
					throw new ArgumentException('$arg3 must be an integer if six or '.
						'seven parameters are specified', 'hour');
				if ($hour < 0 || $hour > 23)
					throw new ArgumentOutOfRangeException('hour', $hour,
						'$arg3 must be a value between 0 and 23 if six or seven '.
						'parameters are specified');
				if (!Types::isInteger($minute))
					throw new ArgumentException('$arg4 must be an integer', 'minute');
				if ($minute < 0 || $minute > 59)
					throw new ArgumentOutOfRangeException('minute', $minute,
						'$arg4 must be a value between 0 and 59');
				if (!Types::isInteger($second))
					throw new ArgumentException('$arg5 must be an integer', 'second');
				if ($second < 0 || $second > 59)
					throw new ArgumentOutOfRangeException('second', $second,
						'$arg5 must be a value between 0 and 59');
			} else {
				$hour = 0;
				$minute = 0;
				$second = 0;
			}
			
			$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
			if ($timestamp === false)
				throw new ArgumentException('The specified date and time is invalid');
		}
		
		// transform by time zone
		$this->_timestamp = $timestamp;
		$this->_timeZone = $timeZone;
		$this->_universalTimestamp = $timestamp -
			$timeZone->getOffset($this)->gettimestamp();
	}
	
	public static function __init() {
		// Make sure that date/time functions do not use a special timezone - these
		// classes do that part.
		date_default_timezone_set('UTC');
	}

	/**
	 * Gets the count of days in the specified month
	 * 
	 * @param int $year the year component
	 * @param int $month the month component
	 * @return int the count of days
	 */
	public static function daysInMonth($year, $month) {
		return \date('t', mktime(0, 0, 0, $month, 1, $year));
	}
	
	/**
	 * Gets the date/time which represents the current time in UTC
	 * @return Premanager\DateTime
	 */
	public static function getNow() {
		return new DateTime();
	}
	
	// ===========================================================================
	
	/**
	 * Gets the date component
	 * 
	 * @return Premanager\DateTime a DateTime object with the same date as this
	 *   object and the time of midnight (00:00:00)
	 */
	public function getDate() {
		return new DateTime($this->getyear(), $this->getmonth(), $this->getday());
	}

	/**
	 * Gets time component
	 * 
	 * @return Premanager\TimeStamp the time elapsed since midnight
	 */
	public function getTimeOfDay() {
		return new TimeSpan($this->gethour(), $this->getminute(), $this->getsecond());
	}
	
	/**
	 * Gets the year component
	 * 
	 * @return int
	 */
	public function getYear() {
		return \date('Y', $this->_timestamp);
	}
	
	/**
	 * Gets the month component
	 * 
	 * @return int
	 */
	public function getMonth() {
		return \date('n', $this->_timestamp);
	}
	
	/**
	 * Gets the day component
	 * 
	 * @return int
	 */
	public function getDay() {
		return \date('j', $this->_timestamp);
	}
	
	/**
	 * Gets the hour component
	 * 
	 * @return int
	 */
	public function getHour() {
		return \date('H', $this->_timestamp);
	}
	
	/**
	 * Gets the minute component
	 * 
	 * @return int
	 */
	public function getMinute() {
		return \date('i', $this->_timestamp);
	}
	
	/**
	 * Gets the hour component
	 * 
	 * @return int
	 */
	public function getSecond() {
		return \date('s', $this->_timestamp);
	}
	
	/**
	 * Gets the day of week
	 * 
	 * @return int a Premanager\DayOfWeek value
	 */
	public function getDayOfWeek() {
		return \date('N', $this->_timestamp);
	}
	
	/**
	 * Gets the day of the year (beginning at 1)
	 * 
	 * @return int
	 */
	public function getDayOfYear() {
		return \date('z', $this->_timestamp)+1;
	}
	
	/**
	 * Gets the number of week specified by ISO-8601
	 * 
	 * @return int
	 */
	public function getWeek() {
		return \date('W', $this->_timestamp);
	}
	
	/**
	 * Gets a timestamp representing this DateTime object
	 * 
	 * @return int
	 */
	public function getTimestamp() {
		return $this->_timestamp;
	}
	
	/**
	 * Adds the specified count of years to the value of this object and returns
	 * the result
	 * 
	 * @param int $value the count of years
	 * @return Premanager\DateTime
	 */
	public function addYears($value) {
		if (!Types::isInteger($value))
			throw new ArgumentException('$value must be an integer', 'value');
		return $this->add(new TimeSpan($value, 0, 0, 0, 0, 0));
	}
	
	/**
	 * Adds the specified count of months to the value of this object and returns
	 * the result
	 * 
	 * @param int $value the count of months
	 * @return Premanager\DateTime
	 */
	public function addMonths($value) {
		if (!Types::isInteger($value))
			throw new ArgumentException('$value must be an integer', 'value');
		return $this->add(new TimeSpan(0, $value, 0, 0, 0, 0));
	}
	
	/**
	 * Adds the specified count of days to the value of this object and returns
	 * the result
	 * 
	 * @param int $value the count of days
	 * @return Premanager\DateTime
	 */
	public function addDays($value) {
		if (!Types::isInteger($value))
			throw new ArgumentException('$value must be an integer', 'value');
		return $this->add(new TimeSpan(0, 0, $value, 0, 0, 0));
	}
	
	/**
	 * Adds the specified count of hours to the value of this object and returns
	 * the result
	 * 
	 * @param int $value the count of hours
	 * @return Premanager\DateTime
	 */
	public function addHours($value) {
		if (!Types::isInteger($value))
			throw new ArgumentException('$value must be an integer', 'value');
		return $this->add(new TimeSpan(0, 0, 0, $value, 0, 0));
	}
	
	/**
	 * Adds the specified count of hours to the value of this object and returns
	 * the result
	 * 
	 * @param int $value the count of hours
	 * @return Premanager\DateTime
	 */
	public function addMinutes($value) {
		if (!Types::isInteger($value))
			throw new ArgumentException('$value must be an integer', 'value');
		return $this->add(new TimeSpan(0, 0, 0, 0, $value, 0));
	}
	
	/**
	 * Adds the specified count of seconds to the value of this object and returns
	 * the result
	 * 
	 * @param int $value the count of seconds
	 * @return Premanager\DateTime
	 */
	public function addSeconds($value) {
		if (!Types::isInteger($value))
			throw new ArgumentException('$value must be an integer', 'value');
		return $this->add(new TimeSpan(0, 0, 0, $value));
	}
	
	/**
	 * Adds the value of the time span to the value of this object and returns the
	 * result
	 * 
	 * @param Premanager\TimeSpan $value the time span to add
	 * @return Premanager\DateTime the result
	 */
	public function add(TimeSpan $value) { 
		return new DateTime($this->_timestamp + $value->gettimestamp());
	}
	
	/**
	 * Substracts the value of the time span or date/time from the value of this
	 * object and returns the result
	 * 
	 * @param Premanager\TimeSpan|Premanager\DateTime $value the time span or
	 *   date/time to subtract
	 * @return Premanager\DateTime|Premanager\TimeSpan if $value is a date/time,
	 *   returns a time span, otherwise, returns a date/time
	 */
	public function subtract($value) { 
		if ($value instanceof TimeSpan)
			return new DateTime($this->_timestamp - $value->gettimestamp());
		else if ($value instanceof DateTime)
			return new TimeSpan($this->_timestamp - $value->_timestamp);
		else
			throw new ArgumentException('$value must be a date/time or time stamp',
				'value');
	}
	
	/**
	 * Gets a DateTime representing the UTC time of the moment specified by this
	 * object
	 * 
	 * @return Premanager\DateTime the UTC time
	 */
	public function getUniversalTime() {
		return $this->getLocalTime(TimeZone::getUTC());
	}
	
	/**
	 * Gets a DateTime representing the local time of the moment specified by this
	 * date time
	 * 
	 * @param TimeZone $timeZone the time zone
	 * @return Premanager\DateTime the local time
	 */
	public function getLocalTime(TimeZone $timeZone) {
		if ($timeZone === null)
			throw new ArgumentNullException('timeZone');
		
		return new DateTime($this->_universalTimestamp +
			$timeZone->getOffset($this->_universalTimestamp)->gettimestamp(), $timeZone);
	}
	
	/**
	 * Gets the time zone of this date/time
	 * 
	 * @return Premanager\TimeZone
	 */
	public function getTimeZone() {
		return $this->_timeZone;
	}
	
	/**
	 * Checks whether the the value of this date time equals the value of the
	 * date time specified by the parameter
	 * 
	 * @param Premanager\DateTime $other a date time to compare
	 * @return bool true, if the two date times are equal 
	 */
	public function equals(DateTime $other) {
		return $this->_timestamp == $other->_timestamp;
	}
	
	/**
	 * Compares this date time to the date time specified by the parameter
	 * 
	 * @param Premanager\DateTime $other a date time to compare
	 * @return int a negative value if this date time is less than $other, zero,
	 *   if this object is equal to $other or a positive value, if this object is
	 *   greater than $value
	 */
	public function compareTo(DateTime $other) {
		if ($this->_timestamp < $other->_timestamp)
			return -1;
		else if ($this->_timestamp == $other->_timestamp)
			return 0;
		else
			return 1;
	}

	/**
	 * Converts the date time value to a string
	 * 
	 * @param string $format a formatting mask
	 * @return string the formatted string. If not specified, the long date
	 *   time format of the current environment language is used.
	 */
	public function format($format = null) {
		static $localizer;
		static $dateFormatCache;
		static $midnight;

		if (!$localizer)
			$localizer = array(
				'Monday'	=> Translation::defaultGet('Premanager', 'dateMonday'),
				'Tuesday'	=> Translation::defaultGet('Premanager', 'dateTuesday'),
				'Wednesday'	=> Translation::defaultGet('Premanager', 'dateWednesday'),
				'Thursday'	=> Translation::defaultGet('Premanager', 'dateThursday'),
				'Friday'	=> Translation::defaultGet('Premanager', 'dateFriday'),
				'Saturday'	=> Translation::defaultGet('Premanager', 'dateSaturday'),  
				'Sunday'	=> Translation::defaultGet('Premanager', 'dateSunday'),
				
				'Mon'	=> Translation::defaultGet('Premanager', 'dateMon'),
				'Tue'	=> Translation::defaultGet('Premanager', 'dateTue'),
				'Wed'	=> Translation::defaultGet('Premanager', 'dateWed'),
				'Thu'	=> Translation::defaultGet('Premanager', 'dateThu'),
				'Fri'	=> Translation::defaultGet('Premanager', 'dateFri'),
				'Sat'	=> Translation::defaultGet('Premanager', 'dateSat'),  
				'Sun'	=> Translation::defaultGet('Premanager', 'dateSun'),
				
				'January' => Translation::defaultGet('Premanager', 'dateJanuary'),
				'February' => Translation::defaultGet('Premanager', 'dateFebruary'),
				'March' => Translation::defaultGet('Premanager', 'dateMarch'),
				'April' => Translation::defaultGet('Premanager', 'dateApril'),
				'May' => Translation::defaultGet('Premanager', 'dateMay'),
				'June' => Translation::defaultGet('Premanager', 'dateJune'),
				'July' => Translation::defaultGet('Premanager', 'dateJuly'),
				'August' => Translation::defaultGet('Premanager', 'dateAugust'),
				'September' => Translation::defaultGet('Premanager', 'dateSeptember'),
				'October' => Translation::defaultGet('Premanager', 'dateOctober'),
				'November' => Translation::defaultGet('Premanager', 'dateNovember'),
				'December' => Translation::defaultGet('Premanager', 'dateDecember'),  
				
				'Jan' => Translation::defaultGet('Premanager', 'dateJan'),
				'Feb' => Translation::defaultGet('Premanager', 'dateFeb'),
				'Mar' => Translation::defaultGet('Premanager', 'dateMar'),
				'Apr' => Translation::defaultGet('Premanager', 'dateApr'),
				'MayShort' => Translation::defaultGet('Premanager', 'dateMayShort'),
				'Jun' => Translation::defaultGet('Premanager', 'dateJun'),
				'Jul' => Translation::defaultGet('Premanager', 'dateJul'),
				'Aug' => Translation::defaultGet('Premanager', 'dateAug'),
				'Sep' => Translation::defaultGet('Premanager', 'dateSep'),
				'Oct' => Translation::defaultGet('Premanager', 'dateOct'),
				'Nov' => Translation::defaultGet('Premanager', 'dateNov'),
				'Dec' => Translation::defaultGet('Premanager', 'dateDec'));
			
		switch ($format) {
			case DateTimeFormat::LONG_RELATIVE:
			case 'long-relative':
				return self::getNow()->subtract($this)->
					format(TimeSpanFormat::LONG_RELATIVE_TO_NOW);
					
			case DateTimeFormat::SHORT_RELATIVE:
			case 'short-relative':
				return self::getNow()->subtract($this)->
					format(TimeSpanFormat::SHORT_RELATIVE_TO_NOW);
			
			case DateTimeFormat::LONG_DATE:
			case 'long-date':
				$format = Environment::getCurrent()->getLanguage()->getLongDateFormat();
				break;
				
			case DateTimeFormat::LONG_TIME:
			case 'long-time':
				$format = Environment::getCurrent()->getLanguage()->getLongTimeFormat();
				break;
				
			case DateTimeFormat::SHORT_DATE_TIME:
			case 'short-date-time':
				$format =
					Environment::getCurrent()->getLanguage()->getShortDateTimeFormat();
				break;
				
			case DateTimeFormat::SHORT_DATE:
			case 'short-date':
				$format =
					Environment::getCurrent()->getLanguage()->getShortDateFormat();
				break;
				
			case DateTimeFormat::SHORT_TIME:
			case 'short-time':
				$format =
					Environment::getCurrent()->getLanguage()->getShortTimeFormat();
				break;
				
			case DateTimeFormat::DATE_TIME_PHRASE:
			case 'date-time-phrase':
				$format =
					Environment::getCurrent()->getLanguage()->getDateTimePhraseFormat();
				break;
			
			default:
				if (!is_string($format))
					$format =
						Environment::getCurrent()->getLanguage()->getLongDateTimeFormat();
		}
			
		// If we are here, an absolute format is selected.
		if (!isset($dateFormatCache[$format])) {
			// Check wheater date part shall be replaced by e.g. 'Today'
			$dateFormatCache[$format] = array(              
				'isShort' => Strings::indexOf($format, '|') !== false,
				'isPhrase' => strpos($format, '|~') !== false,
				'shortFormat' => Strings::substring($format, 0,
			    Strings::indexOf($format, '|')) . '||' .
					Strings::substring(Strings::strrchr($format, '|'), 1),
				'longFormat' => ltrim(str_replace('|', '', $format), '~')); 
			
			$dateFormatCache[$format]['localizer'] = $localizer;  
				
			// Short representation of month in format? date() converts May in 
			// both short and long representation into 'May', so this is a
			// workaround
			if ((Strings::indexOf($format, '\M') === false &&
				Strings::indexOf($format, 'M') !== false)
				|| (Strings::indexOf($format, '\r') === false &&
				Strings::indexOf($format, 'r') !== false)){
				$dateFormatCache[$format]['localizer']['May'] = 
					$dateFormatCache[$format]['localizer']['MayShort'];
			}
		}
		
		if (!$midnight)
			$midnight = DateTime::getNow()->getdate();
			
		// Use short representation (with YESTERDAY, TODAY or TOMORROW instead
		// of the part embedded in |), if this date/time is in the correct range
		if ($dateFormatCache[$format]['isShort'] &&
			$this->compareTo($midnight->subtract(TimeSpan::fromDays(1))) >= 0 && 
			$this->compareTo($midnight->add(TimeSpan::fromDays(2))) <= 0)
		{
			$p = $dateFormatCache[$format]['isPhrase'] ? 'Phrase' : '';
			if ($this->compareTo($midnight->add(TimeSpan::fromDays(1))) > 0) {
				$day = Translation::defaultGet('Premanager', 'tomorrow'.$p);
			} else if ($this->compareTo($midnight) > 0) {
				$day = Translation::defaultGet('Premanager', 'today'.$p);
			} else {
				$day = Translation::defaultGet('Premanager', 'yesterday'.$p);
			}
			
			// Return the day component followed by the short time component (the
			// part that is not embedded in | chars)
			return str_replace('||', $day,
				strtr(date($dateFormatCache[$format]['shortFormat'],
					$this->_timestamp),
				$dateFormatCache[$format]['localizer']));
		}

		// Format and localize    
		return strtr(date($dateFormatCache[$format]['longFormat'],
			$this->_timestamp), $dateFormatCache[$format]['localizer']);
	}
	
	/**
	 * Converts this date/time into a string using the 
	 * Premanager\DateTime::DEFAULT_FORMAT format string
	 * 
	 * @return tring the formatted date/time string
	 */
	public function __tostring() {
		return date(self::DEFAULT_FORMAT, $this->_timestamp);
	}
}

DateTime::__init();

?>