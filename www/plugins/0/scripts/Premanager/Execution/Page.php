<?php 
namespace Premanager\Execution;

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
	 * An array of rows of cols of blocks (Premanager\Execution\PageBlock)
	 * 
	 * A block is accessed in this pattern: $blocks[$row][$col][$index].
	 * 
	 * @var array
	 */
	public $blocks;
	
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
	 * The node that has created this page
	 * 
	 * This property is read-only.
	 * 
	 * @var Premanager\Execution\PageNode
	 */
	public $node = Module::PROPERTY_GET;
	
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
	 * Adds the block in a new col in a new row which is placed at the end
	 * 
	 * @param Premanager\Execution\PageBlock $block the block to append
	 */
	public function appendBlock(PageBlock $block) {
		$this->blocks[] = array(array($block));
	}
	
	/**
	 * Adds the block in a new col in a new row which is placed at the beginning
	 * 
	 * @param Premanager\Execution\PageBlock $block the block to insert
	 */
	public function insertBlock(PageBlock $block) {
		array_splice(&$this->blocks, 0, 0, array(array(array($block))));
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
		
		// Create the html navigation tree
		$node = $this->_node;
		$prev = null;
		$navigationTree = array();
		while ($node) {
			//TODO: replace constant count (5) by option value
			$children = $node->getChildren(5, $prev);
			for ($i = 0; $i < count($children); $i++) {
				if ($prev && $children[$i]->equals($prev))
					$children[$i] = $navigationTree;
				else
					$children[$i] = array($children[$i]);
			}
			$navigationTree = array($node, $children);
			$prev = $node; 
			$node = $node->getparent();
		}
		
		$template->set('node', $this->_node);
		$template->set('title', $this->title);
		$template->set('project', $this->_node->getproject());
		$template->set('projectNode', $projectNode);
		$template->set('isIndexPage', $this->_node instanceof StructurePageNode &&
			$this->_node->isProjectNode());
		$template->set('hierarchy', $hierarchy);
		$template->set('navigationTree', $navigationTree);
		$template->set('blocks', $this->blocks);
		$template->set('environment', Environment::getCurrent());
		$template->set('organization', Project::getOrganization());
		$template->set('canonicalURLPrefix',
			URL::fromTemplate(Environment::getCurrent()->getlanguage(), Edition::COMMON));
		$template->set('staticURLPrefix', Config::getStaticURLPrefix());
		if (Config::isDebugMode())
			$template->set('log', Debug::getLog());
			
		$template->set('sidebar', '<section class="block"><header><h1>Search</h1></header><div><p>The search field.</p></div></section>');
		
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

?>
