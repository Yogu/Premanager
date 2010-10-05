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
class Page extends Module {
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
	 * The node that has created this page
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
	}
	
	/**
	 * Adds a new block in a new col in a new row. The block's title will be the
	 * title of the assigned node, the block's body will be $content and it will
	 * be a main block. 
	 * 
	 * @param string $body the content for the block's body
	 */
	public function createMainBlock($body) {
		$this->blocks[] = array(array(PageBlock::createSimple(
			$this->_node->standAloneTitle, $body, false, false, true)));
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
		array_splice(&$this->blocks[], 0, 0, array(array(array($block))));
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
	public function getHTML() {
		$template = new Template('Premanager', 'page');
		
		// Get list of node, parent of node, parent of parent of node ...
		$hierarchy = array();
		for ($node = $this->_node; $node != null; $node = $node->parent) {
			$hierarchy[] = $node;
		}
		
		// Create the html navigation tree
		$node = new $this->_node;
		$prev = null;
		$navigationTree = array();
		while ($node) {
			//TODO: replace constant count (5) by option value
			$children = $node->getChildren(5, $prev);
			for ($i = 0; $i < count($children); $i++) {
				if ($children[$i]->equals($node))
					$children[$i] = array($children[$i], $navigationTree);
				else
					$children[$i] = array($children[$i]);
			}
			$navigationTree = $children;
			$prev = $node; 
			$node = $node->parent;
		}
		$navigationTree = array($this->_node, $navigationTree);
		
		$template->set('node', $this->_node);
		$template->set('project', $this->_node->project);
		$template->set('projectNode', $projectNode);
		$template->set('isIndexPage', $this->_node instanceof StructurePageNode &&
			$this->_node->isProjectNode());
		$template->set('hierarchy', $hierarchy);
		$template->set('navigationTree', $navigationTree);
		$template->set('blocks', $this->blocks);
		$template->set('environment', Environment::getCurrent());
		$template->set('organization', Project::getOrganization());
		$template->set('canonicalURLPrefix',
			URL::fromTemplate(Environment::getCurrent()->language, Edition::COMMON));
		$template->set('staticURLPrefix', Config::getStaticURLPrefix());
		if (Config::isDebugMode())
			$template->set('log', Debug::getLog());
		
		return $template->get();
	}
}

?>