<?php 
namespace Premanager\IO;

use Premanager\Models\Project;
use Premanager\Execution\StructurePageNode;
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

class URLInfo {
	private $_url;
	private $_relativeURL;
	private $_pageNode;
	private $_project;
	private $_language;
	private $_edition;
	private $_isAnalyzed;
	private $_isAnalyzing;
	private $_isValidRequest;
	
	public function __construct($url) {
		$this->_url = $url;
	}
	
	/**
	 * Gets the complete url
	 * 
	 * @return string the complete url
	 */
	public function getURL() {
		return $this->_url;
	}
	
	/**
	 * Gets url relative to Environment::getCurrent()->getURLPrefix()
	 * 
	 * @return string the relative url
	 */
	public function getRelativeURL() {
		return $this->_relativeURL;
	}
	
	/**
	 * Gets the requested page node
	 * 
	 * @return Premanager\Execution\PageNode
	 */
	public function getPageNode() {
		if ($this->_pageNode === null) {
			$this->analyzeURL();
		}
		return $this->_pageNode;
	}
	
	/**
	 * Gets the project that owns the requested page node
	 * 
	 * @return Premanager\Models\Project
	 */
	public function getProject() {
		if ($this->_project === null) {
			$this->analyzeURL();
		}
		return $this->_project;
	}
	
	/**
	 * Gets the requested language
	 * 
	 * @return Premanager\Models\Language
	 */
	public function getLanguage() {
		if ($this->_language === null) {
			$this->analyzeURL();
		}
		return $this->_language;
	}
	
	/**
	 * Gets the requested edition
	 * 
	 * @return int (enum Premanager\Execution\Edition)
	 */
	public function getEdition() {
		if ($this->_edition === null) {
			$this->analyzeURL();
		}
		return $this->_edition;
	}
	
	/**
	 * Checks whether the request is currently analyzed which means that the
	 * methods getPageNode(), getProject(), getLanguage() and getEdition() do not
	 * work properly
	 */
	public function isAnalyzing() {
		return $this->_isAnalyzing;
	}
	
	/**
	 * Tries to find out which ressource the visitor wants to access
	 */
	private function analyzeURL() {
		if (!$this->_isAnalyzed) {
			// Don't call this method twice!
			$this->_isAnalyzed = true;
			$this->_isAnalyzing = true;
			
			try {
				try {
					$trunkURL = new URL(Config::getEmptyURLPrefix());
				} catch (FormatException $e) {
					throw new CorruptDataException('The url prefix is not a valid url: '.
						Config::getEmptyURLPrefix());
				}
				
				// The request url might not be a valid url...
				try {
					$requestURL = new URL($this->_url);
				} catch (FormatException $e) {
					$this->_pageNode = new PageNotFoundNode(new StructurePageNode(), '');
					$this->_relativeRequestURL = '';
					$this->_isAnalyzing = false;
					$this->_project = Project::getOrganization();
					$this->_language = Language::getDefault();
					$this->_edition = Edition::COMMON;
					return; 
				}
				
				// We can't use the URL class here because the template contains
				// characters ({ and }) that are now allowed in a url
				preg_match('/[^:]+\:\/\/(?P<host>[^\/]+)(?<path>.*)/i',
					Config::getURLTemplate(), $matches);
				$templateHost = $matches['host'];
				$templatePath = $matches['path'];
					
				// Goes through all elements and checks if they match to any available
				// template.
				// Returns the index of the element after the last used one
				$walker = function($elements, $templates, $trunkElements,
					$breakOnFailure, $state)
				{
					if (count($trunkElements)) {
						if (count($elements) > count($trunkElements))
							array_splice($elements, -count($trunkElements));
						if (count($templates) > count($trunkElements))
							array_splice($templates, -count($trunkElements));
					}
					
					for ($i = 0; $i < count($elements); $i++) {
						$element = $elements[$i];
						foreach ($templates as $templateKey => $template) {
							// Check if the lement matches the template. Test the strongest
							// defined template at first.
							$ok = false;
							switch ($template) {
								case '{edition}':
									switch ($element) {
										case 'mobile':
											$state->edition = Edition::MOBILE;
											$ok = true;
											break;
										case 'print':
											$state->edition = Edition::PRINTABLE;
											$ok = true;
											break;
									}
									break;
									
								case '{language}':
									$lang = Language::getByName($element);
									if ($lang) {
										$state->language = $lang;
										$ok = true;
									}
									break;
							}
							
							// If the element matches the template, 
							if ($ok) {
								unset($templates[$templateKey]);
								// unset does not reorder the indices, so use array_splice
								array_splice($elements, 0, 1);
								$i--;
								break;
							}
						}
						
						if ($breakOnFailure && !$ok) {
							array_splice($elements, 0, $i);
							return $elements;
						}
					}
					return $elements;
				};
				
				// requestURL - emptyURLPrefix = significant data
				// urlTemplate - emptyURLPrefix = template for the data
				
				$state = (object)
					(array('edition' => Edition::COMMON, 'language' => null));
				
				// Domain part
				$trunkElements = self::explodeRemoveEmpty($trunkURL->getHost(), '.');
				$elements = self::explodeRemoveEmpty($requestURL->getHost(), '.');
				$templates = self::explodeRemoveEmpty($templateHost, '.');
				call_user_func($walker, $elements, $templates, $trunkElements, false,
					$state);
				
				// Path part
				$trunkElements = self::explodeRemoveEmpty($trunkURL->getPath(), '/');
				$elements = self::explodeRemoveEmpty($requestURL->getPath(), '/');
				$templates = self::explodeRemoveEmpty($templatePath, '/');
				$elements = call_user_func($walker, $elements, $templates,
					$trunkElements, true, $state);
	
				if (!$state->language) {
					foreach (self::parseHTTPLanguageHeader() as $code) {
						if ($lang = Language::getByName($code)) {
							$state->language = $lang;
							break;
						}
					}
					
					if (!$state->language)
						$state->language = Language::getDefault();
				}
					
				$this->_language = $state->language;
				$this->_edition = $state->edition;
				
				$this->_relativeRequestURL = implode('/', $elements);
				
				if (!$node = PageNode::fromPath($elements, $impact, $isBackend)) {
					if ($isBackend) {
						$rest = $this->_relativeRequestURL;
						if ($impact)
							$rest = Strings::substring($rest,
								Strings::length($impact->getURL()) - ($rest[0] == '!' ? 2 : 0));
						$node = new BackendPageNotFoundNode($impact, $rest);
					} else {
						$node = new PageNotFoundNode($impact,
							Strings::substring($this->_relativeRequestURL,
								Strings::length($impact->getURL())));
					}
				}
				$this->_pageNode = $node;
				$this->_project = $node->getProject();
			} catch (\Exception $e) {
				$this->_isAnalyzing = false;
				throw $e;
			}
			$this->_isAnalyzing = false;
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
	
	private static function explodeRemoveEmpty($str, $delimiter) {
		$array = array();
		foreach (explode($delimiter, $str) as $item)
			if ($item) $array[] = $item;
		return $array;
	}
}

?>
