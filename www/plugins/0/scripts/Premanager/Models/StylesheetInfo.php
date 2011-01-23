<?php
namespace Premanager\Models;

use Premanager\Pages\AddGroupHomePage;

use Premanager\IO\Config;
use Premanager\Module;

/**
 * Stores information about a stylesheet. This is not a model, but a helper
 * class of Premanager\Models\Style class.
 */
class StylesheetInfo extends Module {
	private $_fileName;
	private $_url;
	private $_media;
	private $_type;
	
	// ===========================================================================
	
	/**
	 * Creates a new StylesheetInfo and sets its properties
	 * 
	 * @param string $fileName absolute path and file name of the style sheet
	 * @param string $url absolute url to the style sheet
	 * @param string $media the media type this style sheet is created for 
	 *   (default is 'all')
	 * @param string $type the MIME type (default is 'text/css')
	 * @param string 
	 */
	public function __construct($fileName, $url, $media = 'all',
		$type = 'text/css')
	{
		parent::__construct();
		
		$this->_fileName = $fileName;
		$this->_url = $url;
		$this->_media = $media ? $media : 'all';
		$this->_type = $type;
	}
	
	// ===========================================================================
	
	/**
	 * Creates a new StylesheetInfo and sets its properties assuming the style
	 * sheet is stored in the static directory
	 * 
	 * @param string $pluginName the plugin that contains the style sheet
	 * @param string $relativeURL the url relative to the plugin's static folder
	 * @param string $media the media type this style sheet is created for
	 *   (default is 'all')
	 * @param string $type the MIME type (default is 'text/css')
	 */
	public static function simpleCreate($pluginName, $relativeURL,
		$media = 'all', $type = 'text/css')
	{
		return new self(
			Config::getStaticPath() . '/' . $pluginName . '/' . $relativeURL,
			Config::getStaticURLPrefix() . $pluginName . '/' . $relativeURL,
			$media, $type);
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
	
	/**
	 * Gets the MIME type of the stylesheet file
	 * 
	 * @return string
	 */
	public function getType() {
		return $this->_type;
	}
}

?>
