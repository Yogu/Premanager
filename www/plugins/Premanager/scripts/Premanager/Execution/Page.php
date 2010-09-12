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
	 * Gets the value of the main block
	 * 
	 * @return string
	 */
	public function getMainBlock() {
		return $this->_mainBlock;
	}
	
	/**
	 * Sets a new value for the main block
	 * 
	 * @param string $value
	 */
	public function setMainBlock($content) {
		$this->_mainBlock = (string) $value;
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