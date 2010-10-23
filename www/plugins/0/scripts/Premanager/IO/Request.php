<?php 
namespace Premanager\IO;

use Premanager\Debug\Debug;

use Premanager\Execution\PageNotFoundNode;
use Premanager\Models\Language;
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
	private static $_relativeRequestURL;
	private static $_isRefererInternal;
	private static $_pageNode;
	private static $_project;
	private static $_language;
	private static $_edition;
	private static $_isValidated;
	private static $_isValidating;
	private static $_postValidated;
	
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
	 * Gets the requested page node
	 * 
	 * @return Premanager\Execution\PageNode
	 */
	public static function getPageNode() {
		if (self::$_pageNode === null) {
			self::validateURL();
		}
		return self::$_pageNode;
	}
	
	/**
	 * Gets the project that owns the requested page node
	 * 
	 * @return Premanager\Models\Project
	 */
	public static function getProject() {
		if (self::$_project === null) {
			self::validateURL();
		}
		return self::$_project;
	}
	
	/**
	 * Gets the requested language
	 * 
	 * @return Premanager\Models\Language
	 */
	public static function getLanguage() {
		if (self::$_language === null) {
			self::validateURL();
		}
		return self::$_language;
	}
	
	/**
	 * Gets the requested edition
	 * 
	 * @return int (enum Premanager\Execution\Edition)
	 */
	public static function getEdition() {
		if (self::$_edition === null) {
			self::validateURL();
		}
		return self::$_edition;
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
		if (self::$_postValidated === null) {
			if (!Environment::getCurrent()->getsession())
				self::$_postValidated = true;
			else {
				// pretend post data to be validated for accessing the validator
				self::$_postValidated = true;
				$validator = self::getPOST('postValidator');
				self::$_postValidated =
					$validator == Environment::getCurrent()->getsession()->key;
			}
		}
		if (!self::$_postValidated )
			return null;
		
		$value = array_key_exists($name, $_POST) ? $_POST[$name] : null;
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
				try {
					$trunkURL = new URL(Config::getEmptyURLPrefix());
				} catch (FormatException $e) {
					throw new CorruptDataException('The url prefix is not a valid url: '.
						Config::getEmptyURLPrefix());
				}
				
				// The request url might not be a valid url...
				try {
					$requestURL = new URL(self::getRequestURL());
				} catch (FormatException $e) {
					Output::selectInputError(Translation::defaultGet('Premanager',
						'invalidRequestURI'));
				}
				
				// We can't use the URL class here because the template contains
				// characters ({ and }) that are now allowed in a url
				preg_match('/[^:]+\:\/\/(?P<host>[^\/]+)(?<path>.*)/i',
					Config::getURLTemplate(), &$matches);
				$templateHost = $matches['host'];
				$templatePath = $matches['path'];
					
				// Goes through all elements and checks if they match to any available
				// template.
				// Returns the index of the element after the last used one
				$walker = function($elements, $templates, $trunkElements, $breakOnFailure,
					&$language, &$edition) {
					if (count($elements) > count($trunkElements))
						array_splice(&$elements, -count($trunkElements));
					if (count($templates) > count($trunkElements))
						array_splice(&$templates, -count($trunkElements));
					
					foreach ($elements as $elementKey => $element) {
						foreach ($templates as $templateKey => $template) {
							// Check if the lement matches the template. Test the strongest
							// defined template at first.
							$ok = false;
							switch ($template) {
								case '{edition}':
									switch ($element) {
										case 'mobile':
											$edition = Edition::MOBILE;
											$ok = true;
											break;
										case 'print':
											$edition = Edition::PRINTABLE;
											$ok = true;
											break;
									}
									break;
									
								case '{language}':
									$lang = Language::getByName($element);
									if ($lang) {
										$language = $lan;
										$ok = true;
									}
									break;
							}
							
							// If the element matches the template, 
							if ($ok) {
								unset($templates[$templateKey]);
								break;
							}
						}
						
						if ($breakOnFailure && !$ok)
							return $elementKey;
					}
					return count($elements);
				};
				
				// requestURL - emptyURLPrefix = significant data
				// urlTemplate - emptyURLPrefix = template for the data
				
				$edition = Edition::COMMON;
				
				// Domain part
				$trunkElements = explode('.', $trunkURL->gethost());
				$elements = explode('.', $requestURL->gethost());
				$templates = explode('.', $templateHost);
				call_user_func($walker, $elements, $templates, $trunkElements, false,
					&$language, &$edition);
				
				// Path part
				$trunkElements = explode('/', trim($trunkURL->getpath(), '/'));
				$elements = explode('/', trim($requestURL->getpath(), '/'));
				$templates = explode('/', trim($templatePath, '/'));
				$pathElementIndex = call_user_func($walker, $elements, $templates,
					$trunkElements, true, &$language, &$edition);
	
				if (!$language) {
					foreach (self::parseHTTPLanguageHeader() as $code) {
						if ($lang = Language::getByName($code)) {
							$language = $lang;
							break;
						}
					}
					
					if (!$language)
						$language = Language::getDefault();
				}
					
				self::$_language = $language;
				self::$_edition = $edition;
				
				// Find the path to the page node
				array_splice($elements, 0, $pathElementIndex);
				
				self::$_relativeRequestURL = implode('/', $elements);
				
				if (!$node = PageNode::fromPath($elements, &$impact)) {
					$node = new PageNotFoundNode($impact,
						Strings::substring(self::$_relativeRequestURL,
							Strings::length($impact->getURL())));
				}
				self::$_pageNode = $node;
				self::$_project = $node->getProject();
				
				// Compare the url of the page node to the request url
				$calculatedURL =
					Environment::getCurrent()->geturlPrefix().self::$_pageNode->geturl();
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
		if (\is_array($value))
			return \stripslashes($value);
		else {
			foreach ($value as &$item) {
				$item = $this->deepStripslashes($item);
			}
			return $value;
		}
	}
	
	/**
	 * Gets an array of languages given by the HTTP accept-language header in the
	 * correct order
	 * 
	 * @return array an array of language-country codes (e.g. 'en', 'de-at')
	 */
	private static function parseHTTPLanguageHeader() {
		// Thanks to
		// http://www.thefutureoftheweb.com/blog/use-accept-language-header
		$langs = array();
		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			// break up string into pieces (languages and q factors)
			preg_match_all(
				'/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i',
				$_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
				
			if (count($lang_parse[1])) {
				// create a list like "en" => 0.8
				$langs = array_combine($lang_parse[1], $lang_parse[4]);
				
				// set default to 1 for any without q factor
				foreach ($langs as $lang => $val) {
					if ($val === '') $langs[$lang] = 1;
				}
				
				// sort list based on value
				arsort($langs, SORT_NUMERIC);
			}
		}
		return array_keys($langs);
	}
}

?>
