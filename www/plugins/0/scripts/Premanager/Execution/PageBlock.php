<?php
namespace Premanager\Execution;

use Premanager\Module;

class PageBlock extends Module {
	private $_html;
	
	/**
	 * The HTML representation of this block
	 * 
	 * @var string
	 */
	public $html = Module::PROPERTY_GET;
	
	/**
	 * Creates a simple block with a title and body and optional link
	 * 
	 * @param string $title the block's title (plain text)
	 * @param string $body the content (html)
	 * @param string $url an optional url if the block title should be a link
	 * @param string $linkTitle an optional text that is used for the "title"
	 *   attribute of the link (plain text)
	 * @param bool $isMainBlock if true, the block is defined as "main block".
	 *   Watch out to use it only one time per page; if there are multiple main
	 *   blocks, the html output will be invalid.
	 * @return Premanager\Execution\PageBlock
	 */
	public static function createSimple($title, $body, $url = '',
		$linkTitle = '', $isMainBlock = false) {
		$instance = new self();
		
		if ($url) {
			$head = '<a href="'.htmlspecialchars($url).'"';
			if ($linkTitle)
				$head .= ' title="'.htmlspecialchars($linkTitle).'"';
			$head .= htmlspecialchars($title).'</a>';
		} else
			$head = htmlspecialchars($title);

		$main = $isMainBlock ? ' id="main-block"' : '';
		
		$instance->_html = '<dl class="block"'.$main.'><dt>'.$head.'</dt>'.
			'<dd>'.$body.'</dd></dl>';
		return $instance;
	}
	
	/**
	 * Creates a table block
	 * 
	 * @param string $head the part to put into <thead></thead> (html)
	 * @param string $body the part to put into <tbody></tbody> (html)
	 * @return Premanager\Execution\PageBlock
	 */
	public static function createTable($head, $body) {
		$instance = new self();
		
		$instance->_html = '<div class="block"><table><thead>'.$head.'</thead>'.
			'<tbody>'.$body.'</tbody></table></div>';
		return $instance;
	}
	
	/**
	 * Gets the HTML representation of this block
	 * 
	 * @return string
	 */
	public function getHTML() {
		return $this->_html;
	}
}