<?php 
namespace Premanager\IO;

use Premanager\DateTime;
use Premanager\Execution\BackendPageNotFoundNode;
use Premanager\Debug\Debug;
use Premanager\Execution\PageNotFoundNode;
use Premanager\Models\Language;
use Premanager\Models\Plugin;
use Premanager\Execution\Edition;
use Premanager\Execution\Environment;
use Premanager\Strings;
use Premanager\URL;
use Premanager\NotImplementedException;
use Premanager\Execution\Options;
use Premanager\Execution\Translation;
use Premanager\Execution\PageNode;
use Premanager\FormatException;

class Request {
	private static $_userAgent;
	private static $_ip;
	private static $_requestURL;
	private static $_requestURLInfo;
	private static $_isRefererInternal;
	private static $_refererInfo = false;
	private static $_isValidated;
	private static $_isValidating;
	private static $_postValidated;
	private static $_postValues;
	
	/**
	 * Gets the complete url the user requested (e.g. http://example.com/forum/)
	 * 
	 * @return string the request url
	 */
	public static function getRequestURL() {
		if (self::$_requestURL === null) {
			$https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off';
			self::$_requestURL = ($https ? 'https' : 'http').'://'.
				$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return self::$_requestURL;
	}
	
	/**
	 * Gets an object containing information about the request url
	 * 
	 * @return Premanager\IO\URLInfo the url info object
	 */
	public static function getRequestURLInfo() {
		if (self::$_requestURLInfo === null) {
			self::validateURL();
		}
		return self::$_requestURLInfo;
	}
	
	/**
	 * Gets the requested page node
	 * 
	 * @return Premanager\Execution\PageNode
	 */
	public static function getPageNode() {
		return self::getRequestURLInfo()->getPageNode();
	}
	
	/**
	 * Gets the project that owns the requested page node
	 * 
	 * @return Premanager\Models\Project
	 */
	public static function getProject() {
		return self::getRequestURLInfo()->getProject();
	}
	
	/**
	 * Gets the requested language
	 * 
	 * @return Premanager\Models\Language
	 */
	public static function getLanguage() {
		return self::getRequestURLInfo()->getLanguage();
	}
	
	/**
	 * Gets the requested edition
	 * 
	 * @return int (enum Premanager\Execution\Edition)
	 */
	public static function getEdition() {
		return self::getRequestURLInfo()->getEdition();
	}
	
	/**
	 * Checks whether the request is currently analyzed which means that the
	 * methods getPageNode(), getProject(), getLanguage() and getEdition() do not
	 * work properly
	 */
	public static function isAnalyzing() {
		return self::$_isValidating;
	}
	
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
	 * Checks whether the visitor was referred from an internal page
	 * 
	 * @return bool true if the visitor was referred from an internal page
	 */ 
	public static function isRefererInternal() {
		if (self::$_isRefererInternal === null) {
			$prefix = new URL(Config::getEmptyURLPrefix());
			$prefix = $prefix->gethost().$prefix->getpath();
			// Check wehater the referer is in a subfolder of Config::$urlTrunk
			self::$_isRefererInternal = preg_match('/[a-zA-Z0-9+.-]*\:\/\/'.
				'[a-zA-Z0-9.-]*'.str_replace('/', '\/', $prefix).
				'.*/', self::getReferer());
		}
		return self::$_isRefererInternal;
	}
	
	/**
	 * Gets an object containing information about the referer url
	 * 
	 * @return Premanager\IO\URLInfo the url info object or null if the referer is
	 *   not internal
	 */
	public static function getRefererInfo() {
		if (self::$_refererInfo === false) {
			if (self::isRefererInternal())
				self::$_refererInfo = null;
			else
				self::$_refererInfo = new URLInfo(self::getRequestURL());
		}
		return self::$_refererInfo;
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
	 * @param string $name the name
	 * @return string the value
	 */
	public static function getPOST($name) {
		$post = self::getPOSTValues();
		return array_key_exists($name, $post) ? $post[$name] : null;
	}
	
	/**
	 * Gets an array of all POST parameters
	 * 
	 * @return array an array(name => value) of all POST parameters
	 */
	public static function getPOSTValues() {
		static $post;
		if (!is_array($post)) {
			if (self::$_postValidated === null) {
				if (!Environment::getCurrent()->getSession())
					self::$_postValidated = true;
				else {
					// pretend post data to be validated for accessing the validator
					self::$_postValidated = true;
					$validator = self::getPOST('postValidator');
					self::$_postValidated =
						$validator == Environment::getCurrent()->getSession()->getKey();
				}
			}
			if (!self::$_postValidated)
				return array();
			
			$post = $_POST;
			if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
				$post = self::deepStripslashes($post);
		}
		
		return $post;
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
	public static function getUploadFileName($name) {
		return isset($_FILES[$name]) ? $_FILES[$name]['tmp_name'] : null;		
	}
	
	/**
	 * Tries to find out which ressource the visitor wants to access, gets the url
	 * for that ressource and redirects to it if unlike the actual request url.
	 * 
	 * Redirecting means instantly finishing the output and terminating the
	 * script. Make sure that this method has been called before critical code.
	 */
	public static function validateURL() {
		if (!self::$_isValidated) {
			// Don't call this method twice!
			self::$_isValidated = true;
			self::$_isValidating = true;
			
			try {
				self::$_requestURLInfo = new URLInfo(self::getRequestURL());
				
				// Compare the url of the page node to the request url
				$calculatedURL = Environment::getCurrent()->getURLPrefix() .
					self::$_requestURLInfo->getPageNode()->getFullURL();
				if ($calculatedURL != self::getRequestURL())
					Output::redirect($calculatedURL, 301 /* moved permanently */);
			} catch (\Exception $e) {
				self::$_isValidating = false;
				throw $e;
			}
			self::$_isValidating = false;
		}
	}
	
	/**
	 * Applies stripslashes to all items and subitems and so on of value
	 * 
	 * @param string $value
	 * @return string
	 */
	public static function deepStripslashes($value) {
		if (is_array($value)) {
			foreach ($value as &$item) {
				$item = self::deepStripslashes($item);
			}
			return $value;
		} else
			return stripslashes($value);
	}
}

?>
