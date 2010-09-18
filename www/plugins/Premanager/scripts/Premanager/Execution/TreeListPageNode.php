<?php
namespace Premanager\Execution;

use Premanager\Debug\Debug;

use Premanager\ArgumentException;
use Premanager\Module;

/**
 * A PageNode that is embedded in a StructurePageNode
 * 
 * Tree nodes do not have to extend this class but they should implement some
 * methods with a redirection to the owner ndoe
 */
abstract class TreeListPageNode extends ListPageNode {
	/**
	 * Creates a new TreePageNode
	 * 
	 * @param Premanager\Execution\StructurePageNode $owner the page node that
	 *   embends this
	 */
	public function __construct(StructurePageNode $owner) {
		parent::__construct($owner);
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		// The name is defined by the owner node
		return $this->parent->name;
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		// The title is defined by the owner node
		return $this->parent->title;
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $this->parent->equals($other);
	}
	
	/**
	 * Gets the url of this page relative to Environment::getCurrent()->urlPrefix
	 * 
	 * @return string
	 */
	public function getURL() {
		return $this->parent->url;	
	}
}

?>
