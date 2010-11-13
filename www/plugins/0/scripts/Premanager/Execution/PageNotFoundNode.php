<?php
namespace Premanager\Execution;

use Premanager\Debug\Debug;

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
		$this->_urlRest = ltrim($urlRest, '/');
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
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$template = new Template('Premanager', 'notFound');
		$template->set('urlRest', $this->_urlRest);
		$template->set('deepmostExistingNode', $this->getparent());
		$template->set('refererExists', Request::getReferer() != '');
		$template->set('refererIsInternal', (bool) Request::isRefererInternal());
		
		$page = new Page($this);
		$page->createMainBlock($template->get());
		$page->statusCode = 404;
		return $page;
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof PageNotFoundNode &&
			((!$other->getparent() && !$this->getparent()) ||
			($other->getparent()->equals($this->getparent()) &&
			$other->_urlRest == $this->_urlRest));
	}	      
	
	/**
	 * Gets the url of this page relative to Environment::getCurrent()->geturlPrefix()
	 * 
	 * @return string
	 */
	public function getURL() {
		// If there are two parents, one of them is _not_ root
		if ($this->getParent() && $this->getParent()->parent) {
			if ($this->_urlRest)
				return $this->getParent()->url.'/'.$this->_urlRest;
			else
				return $this->getParent()->url;
		}
			
		// If there is exactly one parent, that is root
		else if ($this->getParent())
			return $this->_urlRest;
			
		// Root node has an empty url	
		else
			return '';		
	}
	
	/**
	 * Gets an array of names and values of the query ('page' => 7 for '?page=7')
	 * 
	 * @return array
	 */
	public function getURLQuery() {
		return $_GET;
	}
}

?>
