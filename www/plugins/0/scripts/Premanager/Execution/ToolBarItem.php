<?php
namespace Premanager\Execution;

use Premanager\IO\Config;
use Premanager\Module;

/**
 * Defines an item of the page tool bar
 */
class ToolBarItem extends Module {
	private $_url;
	private $_title;
	private $_description;
	private $_iconURL;
	private $_enabled;
	private $_html;
	
	// ===========================================================================
	
	/**
	 * Creates a tool bar item
	 * 
	 * @param string $url the url the tool bar item links to
	 * @param string $title the text shown in the tool bar item
	 * @param string $description an optional description string used for the
	 *   title attribute
	 * @param string $iconURL an optional url to a graphic file shown as icon,
	 *   relative to Config::getStaticURLPrefix()
	 * @param bool $enabled specifies whether the tool bar item is enabled
	 */
	public function __construct($url, $title, $description = '', $iconURL = '',
		$enabled = true) {
		$this->_url = $url;
		$this->_description = $description;
		$this->_iconURL = $iconURL;
		$this->_enabled = !!$enabled;
		
		$this->_html = '<li>';
		if ($disabled) {
			$tag = 'span';
			$this->_html .= '<span class="disabled"';
		} else {
			$tag = 'a';
			$this->_html .= '<a href="./'.htmlspecialchars($url).'"';
		}
		if ($description)
			$this->_html .=  ' title="'.htmlspecialchars($description).'"';
		if ($iconURL)
			$this->_html .= ' style="background-image: url('.
				htmlspecialchars(Config::getStaticURLPrefix().$iconURL).');"';
		$this->_html .= '>'.htmlspecialchars($title).'</'.$tag.'></li>';
	}
	
	// ===========================================================================
	
	/**
	 * Gets the html code for this tool bar item
	 * 
	 * @return string the html code
	 */
	public function getHTML() {
		return $this->_html;
	}
}