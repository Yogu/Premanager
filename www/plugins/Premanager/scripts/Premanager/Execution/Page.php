<?php 
namespace Premanager\Execution;

/**
 * Defines a common page that can be outputted
 */
use Premanager\Module;

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
			$this->_node->getTitle(), $body, false, false, true)));
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
		throw new NotImplementedException();
	}
}

?>