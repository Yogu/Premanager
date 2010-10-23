<?php 
namespace Premanager\IO;

use Premanager\Debug\Debug;

use Premanager\Execution\Options;

use Premanager\Execution\Page;
use Premanager\Execution\Environment;
use Premanager\InvalidOperationException;
use Premanager\ArgumentException;
use Premanager\URL;
use Premanager\TimeSpan;
use Premanager\DateTime;

class Output {
	private static $_statusCode;
	private static $_page;
	private static $_finished;
	
	/**
	 * Sends a cookie
	 * 
	 * @param string $name the cookie name
	 * @param string $value the new value
	 * @param Premanager\TimeSpan|null $expire the time span between now and the
	 *   expiration date/time or null if the cookie should only be valid during
	 *   the current session 
	 */
	public static function setCookie($name, $value, $expire = null) {
		// Get trunk domain and check whether it contains two parts
		$url = new URL(Config::getEmptyURLPrefix());
		$counts = count_chars($url->gethost(), 1);
		if ($counts[46] == 1)
			$serverName = '.'.$url->gethost();
		else
			$serverName = null;
			
		if ($expire === null) {
			$expirationTime = 0;
		} else if ($expire instanceof TimeSpan) {
			$expirationTime = DateTime::getNow()->add($expire)->getTimestamp();
		} else
			throw new ArgumentException('$exprie must be either null or a '.
				'Premanager\TimeSpan', 'expire');

		$prefix = Options::defaultGet('Premanager', 'cookiePrefix');
		if (!setcookie($prefix.$name, $value,
			$expirationTime, $url->getpath(), $serverName)) {
			throw new InvalidOperationException('Page output has already started');
		}
		$_COOKIE[$prefix.$name] = $value;
	}
	
	/**
	 * Deletes a cookie
	 * 
	 * @param $name the cookie name
	 */
	public static function deleteCookie($name) {
		// Cookie expires yesterday
		self::setCookie($name, '', new TimeSpan(-1, 0, 0, 0));
	} 

	/**
	 * Redirects the client to another url and exits the script
	 * 
	 * @param string|null $location the new location or null to redirect to the
	 *   request url
	 * @param string $code the HTTP status code, by default 303 See other
	 */
	public static function redirect($location = null, $code = 303) {
		if ($location === null)
			$location = Request::getRequestURL();
		elseif (preg_match('/^[a-zA-Z0-9+.-]\:/', $location) === false)
			$location = Environment::getCurrent()->geturlPrefix().$location;

		header("Location: $location", true, $code);
		echo '<?xml version="1.0" encoding="utf-8" ?'.'>'.
			'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" '.
			'"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.
			'<html xmlns="http://www.w3.org/1999/xhtml"><head>'.
			'<title>Redirection</title><meta http-equiv="refresh" '.
			'content="0;url='.htmlspecialchars($location).'" />'.
			'<script type="text/javascript">document.location=\''.
			addslashes($location).'\';'.
			'</script></head><body><h1>Redirection</h1>'.
			'<p>You have been redirected to <a href="'.htmlspecialchars($location).
			'">'.htmlspecialchars($location).'</a>.</p></body></html>';
		exit;
	}
	
	public static function select($object, $statusCode = StatusCode::OK) {
		if ($object instanceof Page) {
			self::$_page = $object;
			self::$_statusCode = $statusCode;
		}
	}
	
	public static function finish() {
		if (!self::$_finished) {
			// Headers
			switch (self::$_statusCode) {
				case StatusCode::NOT_FOUND:
					$statusHeader = '404 Not Found';
					break;
				case StatusCode::FORBIDDEN:
					$statusHeader = '403 Forbidden';
					break;
				default;
					$statusHeader = '200 OK';	
			}
			
			header('HTTP/1.1 '.$statusHeader);
			
			if (self::$_page) {
				header('Content-Type: text/html; charset=UTF-8');
				echo self::$_page->getHTML(); 
			} else
				throw new InvalidOperationException('The output can not be finished '.
					'because no resource has been selected');
		}
	}
}

?>
