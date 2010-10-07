<?php
namespace Premanager;

/**
 * A time span 
 */
class TimeSpan extends Module {
	private $_timestamp;
	
	/**
	 * The day component
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $days = Module::PROPERTY_GET;
	
	/**
	 * The hour component
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $hours = Module::PROPERTY_GET;
	
	/**
	 * The minute component
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $minutes = Module::PROPERTY_GET;
	
	/**
	 * The second component
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $seconds = Module::PROPERTY_GET;
	
	/**
	 * The value of this object in whole days and fractions of days
	 * 
	 * This property is read-only.
	 * 
	 * @var float
	 */
	public $totalDays = Module::PROPERTY_GET;
	
	/**
	 * The value of this object in whole hours and fractions of hours
	 * 
	 * This property is read-only.
	 * 
	 * @var float
	 */
	public $totalHours = Module::PROPERTY_GET;
	
	/**
	 * The value of this object in whole minutes and fractions of minutes
	 * 
	 * This property is read-only.
	 * 
	 * @var float
	 */
	public $totalMinutes = Module::PROPERTY_GET;
	
	/**
	 * The number of seconds representing this timespan (equivalent to $timestamp)
	 * 
	 * This property is read-only.
	 * 
	 * @var float
	 */
	public $totalSeconds = Module::PROPERTY_GET;
	
	/**
	 * A timestamp representing this TimeSpan object (equivalent to $totalSeconds)
	 * 
	 * This property is read-only.
	 * 
	 * @var int
	 */
	public $timestamp = Module::PROPERTY_GET;
	
	/**
	 * Creates a new Premanager\TimeSpan object
	 * 
	 * You can either specify one, three or four parameters.
	 * 
	 * If there is one parameter, the parameter is excepted to be a timestamp.
	 * 
	 * If there are three parameters, they are excepted to be hours, minutes
	 * and seconds.
	 * 
	 * If there are four parameters, they are excepted to be days, hours, minutes
	 * and seconds.
	 * 
	 * @param int $arg0 1 parameter: timestamp, 3 parameters: hours, 4 parameters:
	 *   days
	 * @param int|null $arg1 3 parameters: minutes, 4 parameters: hours 
	 * @param int|null $arg2 3 parameters: seconds, 4 parameters: minutes
	 * @param int|null $arg3 4 parameters: seconds
	 */
	public function __construct($arg0, $arg1 = null, $arg2 = null, $arg3 = null) {
		parent::__construct();
		if ($arg3 !== null) {
			// days - hours - minutes - seconds
			$this->_timestamp = $arg0 * 60*60*24 + $arg1 * 60*60 + $arg2 * 60 + $arg3;
		} else if ($arg2 !== null) {
			// hours - minutes - seconds
			$this->_timestamp = $arg0 * 60*60 + $arg1 * 60 + $arg2;
		} else if ($arg1 !== null) {
			throw new ArgumentException('This constructor does not accept two '.
				'parameters');
		} else if ($arg0 !== null) {
			// timestamp
			$this->_timestamp = $arg0;
		} else {
			throw new ArgumentException('This constructor needs at least one '.
				'parameter');
		}
	}
	
	/**
	 * Gets a time span that represents the specified count of days
	 * 
	 * @param int $value the count of days
	 * @return Premanager\TimeSpan a time span representing the count of days
	 */
	public static function fromDays($value) {
		return new TimeSpan($value, 0, 0, 0);
	}
	
	/**
	 * Gets a time span that represents the specified count of hours
	 * 
	 * @param int $value the count of hours
	 * @return Premanager\TimeSpan a time span representing the count of hours
	 */
	public static function fromHours($value) {
		return new TimeSpan($value, 0, 0);
	}
	
	/**
	 * Gets a time span that represents the specified count of minutes
	 * 
	 * @param int $value the count of days
	 * @return Premanager\TimeSpan a time span representing the count of minutes
	 */
	public static function fromMinutes($value) {
		return new TimeSpan(0, $value, 0);
	}
	
	/**
	 * Gets a time span that represents the specified count of seconds
	 * 
	 * @param int $value the count of days
	 * @return Premanager\TimeSpan a time span representing the count of seconds
	 */
	public static function fromSeconds($value) {
		return new TimeSpan(0, 0, $value);
	}
	
	/**
	 * Gets the day component
	 * 
	 * @return int
	 */
	public function getDays() {
		return Math::intDivide($this->_timestamp, 60*60*24);
	}
	
	/**
	 * Gets the hour component
	 * 
	 * @return int
	 */
	public function getHours() {
		// The left expression subtracts that seconds that belong to the day
		// component
		return Math::intDivide(
			$this->_timestamp - $this->_timestamp % (60*60*24), 60*60);
	}
	
	/**
	 * Gets the minute component
	 * 
	 * @return int
	 */
	public function getMinutes() {
		// The left expression subtracts that seconds that belong to the day and
		// and hour components
		return Math::intDivide(
			$this->_timestamp - $this->_timestamp % (60*60), 60);
	}
	
	/**
	 * Gets the second component
	 * 
	 * @return int
	 */
	public function getSeconds() {
		// Subtract that seconds that belong to the day, hour and minute components
		return $this->_timestamp - $this->_timestamp % 60;
	}
	
	/**
	 * Gets the value of this time span in whole days and fractions of days
	 * 
	 * @return float
	 */
	public function getTotalDays() {
		return $this->_timestamp / (60*60*24);
	}
	
	/**
	 * Gets the value of this time span in whole hours and fractions of hours
	 * 
	 * @return float
	 */
	public function getTotalHours() {
		return $this->_timestamp / (60*60);
	}
	
	/**
	 * Gets the value of this time span in whole minutes and fractions of minutes
	 * 
	 * @return float
	 */
	public function getTotalMinutes() {
		return $this->_timestamp / 60;
	}
	
	/**
	 * Gets the value of this time span in seconds
	 * 
	 * @return int
	 */
	public function getTotalSeconds() {
		return $this->_timestamp;
	}
	
	/**
	 * Gets a timestamp representing this TimeSpan object
	 * 
	 * @return int
	 */
	public function getTimestamp() {
		return $this->_timestamp;
	}
	
	/**
	 * Adds the specified count of days to the value of this object and returns
	 * the result
	 * 
	 * @param int $value the count of days
	 * @return Premanager\TimeSpan
	 */
	public function addDays($value) {
		if (!Types::isInteger($value))
			throw new ArgumentException('$value must be an integer', 'value');
		return $this->add(new TimeSpan($value, 0, 0, 0));
	}
	
	/**
	 * Adds the specified count of hours to the value of this object and returns
	 * the result
	 * 
	 * @param int $value the count of hours
	 * @return Premanager\TimeSpan
	 */
	public function addHours($value) {
		if (!Types::isInteger($value))
			throw new ArgumentException('$value must be an integer', 'value');
		return $this->add(new TimeSpan($value, 0, 0));
	}
	
	/**
	 * Adds the specified count of hours to the value of this object and returns
	 * the result
	 * 
	 * @param int $value the count of hours
	 * @return Premanager\TimeSpan
	 */
	public function addMinutes($value) {
		if (!Types::isInteger($value))
			throw new ArgumentException('$value must be an integer', 'value');
		return $this->add(new TimeSpan(0, $value, 0));
	}
	
	/**
	 * Adds the specified count of seconds to the value of this object and returns
	 * the result
	 * 
	 * @param int $value the count of seconds
	 * @return Premanager\TimeSpan
	 */
	public function addSeconds($value) {
		if (!Types::isInteger($value))
			throw new ArgumentException('$value must be an integer', 'value');
		return $this->add(new TimeSpan(0, 0, $value));
	}
	
	/**
	 * Adds the value of the time span to the value of this object and returns the
	 * result
	 * 
	 * @param Premanager\TimeSpan $value the time span to add
	 * @return Premanager\TimeSpan the result
	 */
	public function add(TimeSpan $value) { 
		return new TimeSpan($this->_timestamp + $value->_timestamp);
	}
	
	/**
	 * Substracts the value of the time span from the value of this object and
	 * returns the result
	 * 
	 * @param Premanager\TimeSpan $value the time span to subtract
	 * @return Premanager\TimeSpan the result
	 */
	public function subtract(TimeSpan $value) { 
		return new TimeSpan($this->_timestamp - $value->_timestamp);
	}
	
	/**
	 * Changes the sign of this TimeSpan and returns the result
	 * 
	 * @return Premanager\TimeSpan the result
	 */
	public function negate() {
		return new TimeSpan(-$this->_timestamp);
	}
	
	/**
	 * Checks whether the the value of this time span equals the value of the
	 * time span specified by the parameter
	 * 
	 * @param Premanager\TimeSpan $other a time span to compare
	 * @return bool true, if the two time spans are equal 
	 */
	public function equals(TimeSpan $other) {
		return $this->_timestamp == $other->_timestamp;
	}
	
	/**
	 * Compares this time span to the time span specified by the parameter
	 * 
	 * @param Premanager\TimeSpan $other a time span to compare
	 * @return int a negative value if this object is less than $other, zero, if
	 *   this object is equal to $other or a positive value, if this object is
	 *   greater than $value
	 */
	public function compareTo(TimeSpan $other) {
		if ($this->_timestamp < $other->_timestamp)
			return -1;
		else if ($this->_time == $other->_timestamp)
			return 0;
		else
			return 1;
	}

	/**
	 * Converts the time span value to a string
	 * 
	 * @param bool $longFormat if true, a long format like "3 seconds ago" is used
	 *   (otherwise it 
	 * @return string the formatted string
	 */
	public function format($longFormat) {
		switch ($format) {
			case TimeSpanFormat::SHORT_RELATIVE_TO_NOW:
				$pattern = array('date%Ago', 'dateInX%');
				$suffix = 'Short';
				break;
				
			case TimeSpanFormat::LONG_RELATIVE_TO_NOW:
				$pattern = array('date%Ago', 'dateInX%');
				$suffix = 'Long';
				break;
				
			case TimeSpanFormat::SHORT:
				$pattern = 'date%';
				$suffix = 'Short';
				break;
				
			default:
				$pattern = 'date%';
				$suffix = 'Long';
		}
		
		$time = $this->_timestamp;
		if ($time >= 31536000) {
			$precision = 'Years'; $num = Math::intDivide($time, 31536000);
		} else if ($time >= 2592000) {
			$precision = 'Months'; $num = Math::intDivide($time, 2592000);
		} else if ($time >= 86400) {
			$precision = 'Days'; $num = Math::intDivide($time, 86400);
		} else if ($time >= 3600) {
			$precision = 'Hours'; $num = Math::intDivide($time, 3600);
		} else if ($time >= 60) {
			$precision = 'Minutes'; $num = Math::intDivide($time, 60);
		} else if ($time > 0) {
			$precision = 'Seconds'; $num = $time;
		} else if ($time == 0) {
			if (is_array($pattern))
				$stringName = 'dateRelativeToday';
			else
				$precision = 'Seconds';
			$num = null;
		} else if ($time > -60) {
			$precision = 'Seconds'; $num = -$time;
		} else if ($time > -3600) {
			$precision = 'Minutes'; $num = -Math::intDivide($time, 60);
		} else if ($time > -86400) {
			$precision = 'Hours'; $num = -Math::intDivide($time, 3600);
		} else if ($time > -2592000) {
			$precision = 'Days'; $num = -Math::intDivide($time, 86400);
		} else if ($time > -31536000) {
			$precision = 'Months'; $num = -Math::intDivide($time, 2592000);
		} else {
			$precision = 'Years'; $num = -Math::intDivide($time, 31536000);
		}
			
		if (!$stringName) {
			if (is_array($pattern))
				$pattern = $pattern[$this->_timestamp < 0];
			$stringName = str_replace('%', $precision, $pattern);
		}
		$stringName .= $suffix;
		
		return
			Translation::defaultGet('Premanager', $stringName, array('num' => $num));
	}
}

?>