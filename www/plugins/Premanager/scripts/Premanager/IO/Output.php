<?php
namespace Premanager\IO;

use Premanager\InvalidEnumArgumentException;
use Premanager\Execution\Response;
use Premanager\Execution\Redirection;
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
	private static $_sent;

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

		$prefix = Options::defaultGet('Premanager', 'cookie.prefix');
		if (!setcookie($prefix.$name, $value,
			$expirationTime, $url->getpath(), $serverName)) {
			throw new InvalidOperationException('Failed to set cookie: page output '.
				'has already been started');
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
	 * Sends the response data
	 * 
	 * @param Premanager\Execution\Response $response the object holding the data
	 *   to be sent
	 * @throws Premanager\InvalidOperationException the response has already been
	 *   sent
	 */
	public static function send(Response $response) {
		if (self::$_sent)
			throw new InvalidOperationException('The response has already been sent');

		$response->send();
		self::$_sent = true;
	}

	/**
	 * Redirects the client to another url and exits the script
	 * 
	 * @param string|null $location the new location or null to redirect to the
	 *   request url
	 * @param string $code the HTTP status code, by default 303 See other
	 * @throws Premanager\InvalidOperationException the response has already been
	 *   sent
	 */
	public static function redirect($location = null, $code = 303) {
		self::send(new Redirection(($location), $code));
		exit;
	}
}

?>
