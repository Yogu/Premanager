<?php
namespace Premanager;

use Premanager\Models\Project;
use Premanager\Models\Language;
use Premanager\IO\Config;
use Premanager\Execution\Environment;
use Premanager\Execution\Edition;

/**
 * Provides access to the parts of a url
 */
class URL {
	private $_url;
	private $_host;
	private $_path;
	
	/**
	 * The host part
	 * 
	 * @var string
	 */
	public $host = Module::PROPERTY_GET;
	
	/**
	 * The path part
	 * 
	 * @var string
	 */
	public $path = Module::PROPERTY_GET;
	
	/**
	 * Creates a new URL using its string representation
	 * 
	 * @param string $url the url
	 */
	public function __construct($url) {
		$this->_url = $url;
	}
	
	/**
	 * Gets the host part
	 * 
	 * @return string
	 */
	public function getHost() {
		if ($this->_host === null)
			$this->splitIntoParts();
		return $this->_host;
	}
	
	/**
	 * Gets the path part
	 * 
	 * @return string
	 */
	public function getPath() {
		if ($this->_path === null)
			$this->splitIntoParts();
		return $this->_path;
	}
	
	/**
	 * Gets a url string using the url template
	 * 
	 * If a paramter is null it is replaced by the environment property
	 * 
	 * @param Premanager\Models\Language|null $language
	 * @param int|null $edition (enum Premanager\Execution\Edition)
	 * @param Premanager\Models\Project|null $project
	 * @return string the url
	 */
	public static function fromTemplate($language = null, $edition = null,
		$project = null) {
		$template = Config::getURLTemplate();
	
		if ($language === null)
			$language = Environment::getCurrent()->language;
		else if (!($language instanceof Language))
			throw new ArgumentException('$language must be null or a '.
				'Premanager\Models\Language', 'language');
			
		if ($project === null)
			$project = Environment::getCurrent()->project;
		else if (!($project instanceof Project))
			throw new ArgumentException('$project must be null or a '.
				'Premanager\Models\Project', 'project');
			
		if ($edition === null)
			$edition = Environment::getCurrent()->edition;
			
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
		
		$template = \str_replace('{language}', $language->name, $template);
		$template = \str_replace('{edition}', $editionString, $template);
		$template = \str_replace('{project}', $project->name, $template);
		$template = \str_replace('..', '.', $template);            
		$template = \str_replace('//', '/', $template);
		$template = \trim($template, "./").'/';
		return $template;
	}
	
	private function splitIntoParts() {
		// Check whether trailing slash after host misses
		if (\preg_match('!^[a-zA-Z0-9]+:/*[a-zA-Z0-9_.-]*$!', $this->_url))
			$this->_url .= '/';
		
		$expr = '![a-zA-Z0-9]*:/*(?P<host>[a-zA-Z0-9_.-]*)/(?P<path>.*)!';
		\preg_match($expr, $this->_url, $matches);
		$this->_host = $matches['host'];                                  
		$this->_path = $matches['path'];
	}
}

?>