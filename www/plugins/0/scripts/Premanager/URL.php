<?php
namespace Premanager;

use Premanager\Module;
use Premanager\Models\Project;
use Premanager\Models\Language;
use Premanager\IO\Config;
use Premanager\Execution\Environment;
use Premanager\Execution\Edition;

/**
 * Provides access to the parts of a url
 * 
 * A url can contain following parts:
 * protocol://user:password@host:port/path?query#fragment
 */
class URL extends Module {
	private $_originalURL;
	private $_scheme;
	private $_userinfo;
	private $_host;
	private $_port;
	private $_path;
	private $_query;
	private $_fragment;
	private $_authority;
	private $_url;

	// ===========================================================================
	
	/**
	 * Creates a new URL using its string representation
	 * 
	 * @param string $url the url
	 * @throws Premanager\FormatException if $url is not a valid url
	 */
	public function __construct($url) {
		parent::__construct();
		
		$this->_originalURL = $url;
		
		/*
		 * source code for $pattern
		 * 
		 * source: ABNF from http://tools.ietf.org/html/rfc3986#appendix-A
		 * 
		 * partially translated into a regular expression. For example, ip addresses
		 * instead of domian names are not supported.
		
		$unreserved = '[0-9a-z_\.~\-]';
		$genDelim = '[\:\/\?\#\[\]\@]';
		$subDelim = '[\!\$\&\'\(\)\*\+\,\;\=]';
		$ptcEncoded = '(?:%[a-f0-9])';
		$pchar = '(?:'.$unreserved.'|'.$subDelim.'|'.$ptcEncoded.'|[\:\@])';
		
		$scheme = '(?:(?P<scheme>[a-z][a-z0-9+.-]*)\:\/\/)';
		$query = '(?:\?(?P<query>(?:'.$pchar.'|[\/\?])*))?';
		$fragment = '(?:\#(?P<fragment>(?:'.$pchar.'|[\/\?])*))?';
		
		$domainPart = '[0-9a-z](?:[0-9a-z-]*[0-9a-z])?';
		$domain = '(?:'.$domainPart.'\.)+[a-z]{2,}';
		$host = $domain; // ips not allowed yet
		$port = '(?:[0-9]+)';
		$userinfo = '(?:'.$unreserved.'|'.$subDelim.'|'.$ptcEncoded.'|\:)*';
		$authority = '(?:(?P<userinfo>'.$userinfo.')\@)?(?P<host>'.$host.')'.
			'(?:\:(?P<port>'.$port.'))?';
		
		$segment = '(?:'.$pchar.'*)';
		$path = '(?P<path>(?:\/'.$segment.'*)*)';
		
		$pattern = '/^'.$scheme.$authority.$path.$query.$fragment.'$/i';
		
		 *
		 */
		
		// the compiled and optimized url regular expression pattern
		$pattern =
			'/^(?:(?P<scheme>[a-z][a-z0-9+.-]*)\\:\\/\\/)(?:(?P<userinfo>(?:[0-9a-z_'.
			'\\.~\\-\\!\\$\\&\'\\(\\)\\*\\+\\,\\;\\=]|(?:%[a-f0-9])|\\:)*)\\@)?(?P<h'.
			'ost>(?:[0-9a-z](?:[0-9a-z-]*[0-9a-z])?\\.)+[a-z]{2,})(?:\\:(?P<port>(?:'.
			'[0-9]+)))?(?P<path>(?:\\/(?:(?:[0-9a-z_\\.~\\-\\!\\$\\&\'\\(\\)\\*\\+\\'.
			',\\;\\=\\:\\@]|(?:%[a-f0-9]))*)*)*)(?:\\?(?P<query>(?:(?:[0-9a-z_\\.~\\'.
			'-\\!\\$\\&\'\\(\\)\\*\\+\\,\\;\\=\\:\\@\\/\\?]|(?:%[a-f0-9])))*))?(?:\\'.
			'#(?P<fragment>(?:[0-9a-z_\\.~\\-\\!\\$\\&\'\\(\\)\\*\\+\\,\\;\\=\\:\\@'.
			'\\/\\?]|(?:%[a-f0-9])))*)?$/i';
		
		if (!preg_match($pattern, $url, $matches))
			throw new FormatException('Invalid url: '.$url);
		$this->_scheme = (string) $matches['scheme'];
		$this->_userinfo = (string) $matches['userinfo'];
		$this->_host = (string) $matches['host'];
		$this->_port = (string) $matches['port'];
		$this->_path = $matches['path'] ? $matches['path'] : '/';
		$this->_query = (string) $matches['query'];
		$this->_fragment = (string) $matches['fragment'];
	}
	
	/**
	 * Gets a url string using the url template
	 * 
	 * If a paramter is null it is replaced by the environment property
	 * 
	 * @param Premanager\Models\Language|null $language
	 * @param int|null $edition (enum Premanager\Execution\Edition
	 * @return string the url
	 */
	public static function fromTemplate($language = null, $edition = null) {
		if ($language === null)
			$language = Environment::getCurrent()->getlanguage();
		else if (!($language instanceof Language))
			throw new ArgumentException('$language must be null or a '.
				'Premanager\Models\Language', 'language');
			
		if ($edition === null)
			$edition = Environment::getCurrent()->getedition();
			
		switch ($edition) {
			case Edition::MOBILE:
				$editionString = 'mobile';
				break;
			case Edition::PRINTABLE:
				$editionString = 'print';
				break;
			default:
				$editionString = '';		
		}
		
		return self::fromTemplateUsingStrings($language->getname(), $editionString);
	}
	
	/**
	 * Gets a url string using the url template
	 * 
	 * This method is identical to fromTemplate but it expects the arguments to
	 * be strings. These strings are directly inserted into the template.
	 * 
	 * @param string $language the language name
	 * @param string $edition the edition identifier
	 * @return string the url
	 */
	public static function fromTemplateUsingStrings($language, $edition) {
		$template = Config::getURLTemplate();
		
		// first split scheme away
		preg_match('/^([a-z][a-z0-9+.-]*)\:\/\/(.*)/i', $template, $matches);
		$scheme = $matches[1];
		$template = $matches[2];
		
		$template = str_replace('{language}', $language, $template);
		$template = str_replace('{edition}', $edition, $template);
		$template = str_replace('..', '.', $template);            
		$template = str_replace('//', '/', $template);
		$template = trim($template, "./").'/';
		return $scheme.'://'.$template;
	}

	// ===========================================================================
	
	/**
	 * Gets the scheme part
	 * 
	 * @return string
	 */
	public function getScheme() {
		return $this->_scheme;
	}
	
	/**
	 * Gets the userinfo part
	 * 
	 * @return string
	 */
	public function getUserinfo() {
		return $this->_userinfo;
	}
	
	/**
	 * Gets the host part
	 * 
	 * @return string
	 */
	public function getHost() {
		return $this->_host;
	}
	
	/**
	 * Gets the port part
	 * 
	 * @return string
	 */
	public function getPort() {
		return $this->_port;
	}
	
	/**
	 * Gets the path part
	 * 
	 * @return string
	 */
	public function getPath() {
		return $this->_path;
	}
	
	/**
	 * Gets the query part
	 * 
	 * @return string
	 */
	public function getQuery() {
		return $this->_query;
	}
	
	/**
	 * Gets the fragment part
	 * 
	 * @return string
	 */
	public function getFragment() {
		return $this->_fragment;
	}
	
	/**
	 * Gets user, password, host and port as string
	 * 
	 * @return string
	 */
	public function getAuthority() {
		if ($this->_authority === null) {
			$this->_authority = '';
			if ($this->_userinfo)
				$this->_authority .= $this->_userinfo.'@';
			$this->_authority .= $this->_host;
			if ($this->_port)
				$this->_authority .= ':'.$this->_port;
		}
		return $this->_authority;
	}
	
	/**
	 * Gets the path and query as string
	 * 
	 * @return string
	 */
	public function getPathAndQuery() {
		$result = $this->_path;
		if ($this->_query)
			$result .= '?' . $this->_query;
		return $result;
	}
	
	/**
	 * Gets the complete url
	 * 
	 * @return string
	 */
	public function getURL() {
		if ($this->_url === null) {
			$this->_url = $this->_scheme.'://'.$this->getAuthority().
				$this->getPathAndQuery();
			if ($this->_fragment)
				$this->_url .= '#'.$this->_fragment;
		}
		return $this->_url;
	}
}

?>
