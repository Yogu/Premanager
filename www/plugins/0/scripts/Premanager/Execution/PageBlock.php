<?php
namespace Premanager\Execution;

use Premanager\Module;

class PageBlock extends Module {
	private $_html;
	
	// ===========================================================================
	
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
	 * @param bool $isArticle true, if the block should generate a <article> tag
	 *   to indicate that the contents can stand alone (for blog articles,
	 *   calendar events, comments etc.)
	 * @return Premanager\Execution\PageBlock
	 */
	public static function createSimple($title, $body, $url = '',
		$linkTitle = '', $isMainBlock = false, $isArticle = false) {
		$instance = new self();
		
		if ($url) {
			$head = '<a href="'.htmlspecialchars($url).'"';
			if ($linkTitle)
				$head .= ' title="'.htmlspecialchars($linkTitle).'"';
			$head .= htmlspecialchars($title).'</a>';
		} else
			$head = htmlspecialchars($title);

		$main = $isMainBlock ? ' id="main-block"' : '';
		
		$tagName = $isArticle ? 'article' : 'section';
		
		$instance->_html = '<'.$tagName.' class="block"'.$main.'><header><h1>'.
			$head.'</h1></header><div>'.$body.'</div></'.$tagName.'>';
		return $instance;
	}
	
	// ===========================================================================
	
	/**
	 * Creates a table block
	 * 
	 * @param string $head the part to put into <thead></thead> (html)
	 * @param string $body the part to put into <tbody></tbody> (html)
	 * @param string $frame a HTML string to put around the <table> tag. &table;
	 *   is replaced by the whole <table> tag (optional).
	 * @return Premanager\Execution\PageBlock
	 */
	public static function createTable($head, $body, $frame = '')
	{
		$instance = new self();
		
		$table = '<table><thead>'.$head.'</thead><tbody>'.$body.'</tbody></table>';
		if ($frame)
			$html = str_replace('&table;', $table, $frame);
		else
			$html = $table; 
		
		$instance->_html = '<section class="block table-wrap">'.$html.'</section>';
		
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