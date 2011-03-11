<?php 
namespace Premanager\Execution;

use Premanager\Event;

use Premanager\Premanager;
use Premanager\Debug\Debug;
use Premanager\Models\StructureNode;
use Premanager\URL;
use Premanager\Models\Project;
use Premanager\Module;
use Premanager\IO\Config;

/**
 * Defines a common page that can be outputted
 */
class Page extends Response {
	/**
	 * @var Premanager\Execution\PageNode
	 */
	private $_node;
	/**
	 * @var array
	 */
	private $_stylesheets = array();
	
	// ===========================================================================
	
	/**
	 * An array of rows of cols of blocks (Premanager\Execution\PageBlock)
	 * 
	 * A block is accessed in this pattern: $blocks[$row][$col][$index].
	 * 
	 * @var array
	 */
	public $blocks = array();
	
	/**
	 * The HTML status code for this page.
	 * 
	 * Default is 200 (OK). Other possible values are for example 404 (Not Found)
	 * or 403 (Forbidden)
	 * 
	 * @var int
	 */
	public $statusCode;
	
	/**
	 * The page title (may differ from the page node title)
	 * 
	 * This title is displayed in the browser's title bar and in the heading of
	 * the main block, if exists.
	 * 
	 * Has to be set before calling createMainBlock().
	 * 
	 * The default value is the page node title.
	 * 
	 * @var string
	 */
	public $title;
	
	/**
	 * An array of tool bar items (Premanager\Execution\ToolBarItem)
	 * 
	 * @var array
	 */
	public $toolbar = array();
	
	// ===========================================================================
	
	/**
	 * Is called directly before the template is rendered
	 * 
	 * Parameters:
	 * - template: the Premanager\Execution\Template object
	 * 
	 * @var Premanager\Event
	 */
	public static $templatePreparedEvent;
	
	public static function __init() {
		self::$templatePreparedEvent = new Event(__CLASS__);
	}
	
	// ===========================================================================
	
	/**
	 * Creates a new page
	 * 
	 * @param Premanager\Execution\PageNode $node the node that creates this page
	 *   (is used for navigation)
	 */
	public function __construct(PageNode $node) {
		parent::__construct();
		$this->_node = $node;
		$this->title = $node->getTitle();
		$this->statusCode = 200;
	}
	
	/**
	 * Creates a page that simply contains the $message, embedded in <p></p> tags
	 * 
	 * @param Premanager\Execution\PageNode $node the node that creates this page
	 *   (is used for navigation)
	 * @param string $message the message to output
	 */
	public static function createMessagePage(PageNode $node, $message) {
		$template = new Template('Premanager', 'message');
		$template->set('message', $message);
		$page = new Page($node);
		$page->createMainBlock($template->get());
		return $page;
	}
	
	// ===========================================================================
	
	/**
	 * Adds a new block in a new col in a new row. The value of the $title
	 * property is used for the block's title, the block's body will be $content
	 * and it will be a main block.
	 * 
	 * $title has to be set before calling this method
	 * 
	 * @param string $body the content for the block's body
	 */
	public function createMainBlock($body) {
		$this->blocks[] = array(array(PageBlock::createSimple(
			$this->title, $body, false, false, true)));
	}
	
	/**
	 * Adds the block in the first col of the first row and places it at the end
	 * 
	 * @param Premanager\Execution\PageBlock $block the block to append
	 */
	public function appendBlock(PageBlock $block) {
		if (!count($this->blocks))
			$this->blocks[] = array(array());
		if (!count($this->blocks[0]))
			$this->blocks[0][] = array();
		$this->blocks[0][0][] = $block;
	}
	
	/**
	 * Adds the block in the first col of the first row and places it at the
	 * beginning
	 * 
	 * @param Premanager\Execution\PageBlock $block the block to insert
	 */
	public function insertBlock(PageBlock $block) {
		if (!count($this->blocks))
			$this->blocks[] = array(array());
		if (!count($this->blocks[0]))
			$this->blocks[0][] = array();
		$arr &= $this->blocks[0][0];
		array_splice($arr, 0, 0, array($block));
	}
	
	/**
	 * Adds a stylesheet reference to the page
	 * 
	 * @param string $url the url to the stylesheet
	 */
	public function addStylesheet($url) {
		$this->_stylesheets[] = $url;
	}
	
	/**
	 * Gets the node that has created this page
	 * 
	 * @return Premanager\Execution\PageNode
	 */
	public function getNode() {
		return $this->_node;
	}
	
	/**
	 * Gets the HTML representation of this page
	 * 
	 * @return string
	 */
	public function getContent() {
		$template = new Template('Premanager', 'page');
		
		// Get list of node, parent of node, parent of parent of node ...
		$hierarchy = array();
		for ($node = $this->_node; $node != null; $node = $node->getparent()) {
			$hierarchy[] = $node;
		}
		
		$navigationTree = PageNode::getNavigationTreeSource($this->_node);
		
		$template->set('node', $this->_node);
		$template->set('isList', $this->_node instanceof ListPageNode);
		$template->set('title', $this->title);
		$template->set('project', $this->_node->getProject());
		$template->set('projectNode', $projectNode);
		$template->set('isIndexPage', $this->_node instanceof StructurePageNode &&
			$this->_node->isProjectNode());
		$template->set('hierarchy', $hierarchy);
		$template->set('navigationTree', $navigationTree);
		$template->set('blocks', $this->blocks);
		$template->set('toolbar', $this->toolbar);
		$template->set('stylesheets', $this->_stylesheets);
		$template->set('environment', Environment::getCurrent());
		$template->set('organization', Project::getOrganization());
		$template->set('canonicalURLPrefix', URL::fromTemplate(
			Environment::getCurrent()->getlanguage(), Edition::COMMON));
		$template->set('emptyURLPrefix', Config::getEmptyURLPrefix());
		$template->set('staticURLPrefix', Config::getStaticURLPrefix());
		if (Config::isDebugMode())
			$template->set('log', Debug::getLog());
		$template->set('version', Premanager::getVersionInfo());
		
		self::$templatePreparedEvent->call($this, array('template' => $template));
		
		return $template->get();
	}
	
	/**
	 * Gets the MIME type of this response
	 * 
	 * @return string
	 */
	public function getContentType() {
		return 'text/html';
	}
	
	/**
	 * Gets the HTML status code to be sent (e.g. 200 for OK)
	 * 
	 * @return int
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}
}

Page::__init();

?>
