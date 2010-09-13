<?php
namespace Premanager\Execution;

use Premanager\IO\Output;
use Premanager\Strings;
use Premanager\URL;
use Premanager\IO\Request;
use Premanager\IO\Config;

/**
 * Analyses the request url and auto-corrects common errors
 */
class RequestURL {
	private static $_isValidated;
	private static $_relative;
	
	/**
	 * Checks if the url contains common errors and redirects to the corrected url
	 * if there are some
	 * 
	 * - 
	 */
	public static function validate() {
		// Make Client::$requestURL relative to Config::$urlPrefix
		$requestURL = new URL(Request::getRequestURL());
		$prefixURL = new URL(Environment::getCurrent()->urlPrefix);
		
		// Check if we need to redirect
		if ($requestURL->host != $prefixURL->host ||
			Strings::length($requestURL->path) < Strings::length($prefixURL->path) ||
			preg_replace('![/]+!', '/', $_SERVER["REQUEST_URI"]) !=
			$_SERVER["REQUEST_URI"]) {
			
			$path = Strings::length($requestURL->path) <
				Strings::length($prefixURL->path) ?
				$prefixURL->path : $requestURL->path; 
			Output::redirect("http://$targetDomain/$path");
		}
		Client::$internalRequestURL =
			substr($actualPath, strlen($targetPath));
		if (($pos = strpos(Client::$internalRequestURL, '?')) !== false) {      
			Client::$requestURLQuery = substr(Client::$internalRequestURL, $pos+1);
			Client::$internalRequestURL =
				substr(Client::$internalRequestURL, 0, $pos);
				
			foreach (explode('&', Client::$requestURLQuery) as $str) {
				$s = explode('=', $str, 2);
				Client::$getValues[$s[0]] = ($s[1] !== null) ? $s[1] : '';
			}
		}

		// Check wehater Client::$referer is in a subfolder of Config::$urlTrunk
		Client::$refererIsInternal = preg_match('![a-zA-Z0-9]*:/*[a-zA-Z0-9_.-]*'.
			Config::$urlTrunk.'.*!', Client::$referer);
	}
	
	/**
	 * Returns the url part that is left when cutting the url prefix off the
	 * request url
	 * 
	 * If validate() has not been called yet, this method call may invoke a
	 * redirection to a validated path. If this is the case, the script is
	 * terminated before returning. Make sure calling validate() before running
	 * critical code.
	 * 
	 * @return string
	 */
	public static function getRelative() {
		if (self::$_relative === null) {
			if (!self::$_isValidated)
				self::validate();

			
		}
	}
}

?>
