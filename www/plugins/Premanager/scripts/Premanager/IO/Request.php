<?php 
namespace Premanager\IO;

use Premanager\Execution\Options;

class Request {
	private static $_userAgent;
	
	/**
	 * Gets the client's ip address
	 * 
	 * @return string
	 */
	public static function getIP() {
		if (self::$_ip === null) {
			if (\trim($_SERVER['HTTP_X_FORWARDED_FOR']) == '') 
				$ip = $_SERVER['REMOTE_ADDR'];    
			else
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				
			// To avoid sql injection
			if (\preg_match('/[a-zA-Z0-9\.\:\[\]]*/', $ip))
				self::$_ip = $ip;
			else
				self::$_ip = '';
		}
		return self::$_ip;
	}
	
	/**
	 * Gets the user agent
	 * 
	 * @return string
	 */
	public static function getUserAgent() {
		return $_SERVER['HTTP_USER_AGENT'];
	}
	
	/**
	 * Gets the referer url
	 * 
	 * @return string
	 */
	public static function getReferer() {
		return $_SERVER['HTTP_REFERER'];
	}
	
	/**
	 * Gets the value of a GET parameter
	 * 
	 * @param string $name
	 * @return string
	 */
	public static function getGET($name) {
		$value = \array_key_exists($name, $_GET) ? $_GET[$name] : null;
		if (\function_exists('get_magic_quotes_gpc') && \get_magic_quotes_gpc())
			$value = $this->deepStripslashes($value);
		return $value;	
	}
	
	/**
	 * Gets the value of a POST parameter
	 * 
	 * @param string $name
	 * @return string
	 */
	public static function getPOST($name) {
		$value = \array_key_exists($name, $_POST) ? $_POST[$name] : null;
		if (\function_exists('get_magic_quotes_gpc') && \get_magic_quotes_gpc())
			$value = $this->deepStripslashes($value);
		return $value;	
	}
	
	/**
	 * Gets the value of a cookie
	 * 
	 * @param string $name
	 * @return string
	 */
	public static function getCookie($name) {
		$prefix = Options::defaultGet('Premanager', 'cookiePrefix');
		$value = \array_key_exists($prefix.$name, $_COOKIE) ?
			$_COOKIE[$prefix.$name] : null;
		if (\function_exists('get_magic_quotes_gpc') && \get_magic_quotes_gpc())
			$value = $this->deepStripslashes($value);
		return $value;	
	}
	
	/**
	 * Gets the temporary fie name of an upload
	 * 
	 * @param string $name
	 * @return string|null
	 */
	public static function uploadFileName($name) {
		return isset($_FILES[$name]) ? $_FILES[$name]['tmp_name'] : null;		
	}  
	
	/**
	 * Applies stripslashes to all items and subitems and so on of value
	 * 
	 * @param string $value
	 * @return string
	 */
	public static function deepStripslashes($value) {
		if (\is_array($value))
			return \stripslashes($value);
		else {
			foreach ($value as &$item) {
				$item = $this->deepStripslashes($item);
			}
			return $value;
		}
	}
}

?>