<?php
namespace Premanager;

/**
 * A time zone
 */
class TimeZone extends Module {
	private $_object;
	
	public function __construct($identifier) {
		parent::__construct();
		
		try {
			$this->_object = new \DateTimeZone($identifier);
		} catch (Exception $e) {
			throw new ArgumentException('Invalid timezone identifier', 'identifier');
		}
	}
	
	public function getName() {
		return $this->_object->getName();
	}
	
	/**
	 * Gets the offset of this time zone to the specified date/time
	 * 
	 * @param DateTime $time the date/time
	 * @return TimeSpan the offset
	 */
	public function getOffset(DateTime $time) {
		$transitions = $this->_object->getTransitions($time->getTimestamp(),
			$time->getTimestamp());
		return new TimeSpan($transitions[0]['offset']);
	}
	
	/**
	 * Gets the universal time zone
	 * 
	 * @return Premanager\TimeZone the universal time zone
	 */
	public static function getUTC() {
		return new TimeZone('UTC');
	}
}

?>