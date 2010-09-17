<?php
namespace Premanager\Execution;

use Premanager\IO\Request;

use Premanager\IO\StatusCode;
use Premanager\Execution\Template;
use Premanager\NotImplementedException;
use Premanager\ArgumentException;
use Premanager\Models\StructureNode;
use Premanager\Models\StructureNodeType;
use Premanager\IO\Output;

class PageNotFoundNode extends PageNode {
	/**
	 * @var string
	 */
	private $_urlRest;
	
	/**
	 * Creates a page node that indicates that a page does not exist
	 * 
	 * @param Premanager\Models\PageNode|null $parent the parent node
	 * @param string $urlRest the part of the url that can not be found, starting
	 *   at the name of this node
	 */
	public function __construct($parent, $urlRest) {
		parent::__construct($parent);
		$this->_urlRest = $urlRest;
	}
	
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
		return null;
	}
	
	/**
	 * Gets the name that is used in urls. Note: you should not ulr-escape the
	 * result of this method because it contains escaped url data.
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_urlRest;
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'pageNotFound');
	}
	
	/**
	 * Creates a page object that covers the data of this page node
	 * 
	 * @return Premanager\Execution\Page the page or null, if this page node does
	 *   not result in a page. 
	 */
	public function getPage() {
		$template = new Template('Premanager', 'notFound');
		$template->set('urlRest', $this->_urlRest);
		$template->set('deepmostExistingNode', $this->parent);
		$template->set('refererExists', Request::getReferer() != '');
		$template->set('refererIsInternal', (bool) Request::isRefererInternal());
		
		$page = new Page($this);
		$page->createMainBlock($template->get());
		return $page;
	}

	/**
	 * Performs a call of this page
	 */
	public function execute() {
		Output::select($this->getPage(), StatusCode::NOT_FOUND);
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof PageNotFoundNode &&
			((!$other->_parent && !$this->_parent) ||
			($other->parent->equals($this->_parent) &&
			$other->_urlRest == $this->_urlRest));
	}	      
	
	/**
	 * Gets the url of this page relative to Environment::getCurrent()->urlPrefix
	 * 
	 * @return string
	 */
	public function getURL() {
		// If there are two parents, one of them is _not_ root
		if ($this->parent && $this->parent->parent) {
			if ($this->_urlRest)
				return $this->parent->url.'/'.$this->_urlRest;
			else
				return $this->parent->url;
		}
			
		// If there is exactly one parent, that is root
		else if ($this->parent)
			return $this->_urlRest;
			
		// Root node has an empty url	
		else
			return '';		
	}
}

?>
