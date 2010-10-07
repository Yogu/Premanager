<?php
namespace Premanager\Execution;

use Premanager\IO\Config;
use Premanager\Module;

/**
 * Stores information about a stylesheet
 */
class StylesheetInfo extends Module {
	private $_fileName;
	private $_url;
	private $_media;
	
	/**
	 * The absolute path and file name
	 * 
	 * This property is read-only.
	 * 
	 * @var string
	 */
	public $fileName = Module::PROPERTY_GET;
	
	/**
	 * The absolute url
	 * 
	 * This property is read-only.
	 * 
	 * @var string
	 */
	public $url = Module::PROPERTY_GET_ACRONYM;
	
	/**
	 * The media type this style sheet is created for
	 * 
	 * This property is read-only.
	 * 
	 * @var string
	 */
	public $media = Module::PROPERTY_GET;
	
	/**
	 * Creates a new StylesheetInfo and sets its properties
	 * 
	 * @param string $fileName absolute path and file name of the style sheet
	 * @param string $url absolute url to the style sheet
	 * @param string $media the media type this style sheet is created for 
	 *   (default is 'all')
	 */
	public function __construct($fileName, $url, $media = 'all') {
		parent::__construct();
		
		$this->_fileName = $fileName;
		$this->_url = $url;
		$this->_media = $media ? $media : 'all';
	}
	
	/**
	 * Creates a new StylesheetInfo and sets its properties assuming the style
	 * sheet is stored in the static directory
	 * 
	 * @param string $pluginName the plugin that contains the style sheet
	 * @param string $relativeURL the url relative to the plugin's static folder
	 * @param string $media the media type this style sheet is created for
	 *   (default is 'all')
	 */
	public static function simpleCreate($pluginName, $relativeURL, $media = 'all')
	{
		return new self(
			Config::getStaticPath() . '/' . $pluginName . '/' . $relativeURL,
			Config::getStaticURLPrefix() . $pluginName . '/' . $relativeURL,
			$media);		
	}
	
	/**
	 * Gets the absolute path and file name
	 * 
	 * @return string
	 */
	public function getFileName() {
		return $this->_fileName;
	}
	
	/**
	 * Gets the absolute url
	 * 
	 * @return string
	 */
	public function getURL() {
		return $this->_url;
	}
	
	/**
	 * Gets the media type this style sheet is created for
	 * 
	 * @return string
	 */
	public function getMedia() {
		return $this->_media;
	}
}

?>
