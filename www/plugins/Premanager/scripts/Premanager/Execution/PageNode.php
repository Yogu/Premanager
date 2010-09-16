<?php
namespace Premanager\Execution;

use Premanager\ArgumentException;
use Premanager\Module;

abstract class PageNode extends Module {
	/**
	 * @var Premanager\Execution\PageNode
	 */
	private $_parent;
	/**
	 * @var Premanager\Models\Project
	 */
	protected $_project;
	
	/**
	 * The parent node
	 * 
	 * @var Premanager\Execution\PageNode
	 */
	public $parent = Module::PROPERTY_GET;
	
	/**
	 * The project that owns this node
	 * 
	 * @var Premanager\Models\Project
	 */
	public $project = Module::PROPERTY_GET;
	
	/**
	 * The name that is used in urls
	 * 
	 * @var string
	 */
	public $name = Module::PROPERTY_GET;
	
	/**
	 * The displayed title that is used when the titles of the parent nodes are
	 * also displayed
	 * 
	 * @var string
	 */
	public $title = Module::PROPERTY_GET;
	
	/**
	 * The title that is used in a context where the titles of the parent nodes
	 * are not displayed
	 * 
	 * @var string
	 */
	public $standAloneTitle = Module::PROPERTY_GET;
	
	/**
	 * The url of this page relative to Environment::getCurrent()->urlPrefix
	 * 
	 * @var string
	 */
	public $url = Module::PROPERTY_GET_ACRONYM;
	
	/**
	 * Creates a new page node
	 * 
	 * @param Premanager\Execution\ParentNode|null $parent
	 */
	public function __construct($parent) {
		parent::__construct();
		
		if ($parent != null && !($parent instanceof PageNode))
			throw new ArgumentException('$parent must either be null or a '.
				'Premanager\Execution\PageNode.', 'parent');
		
		$this->_parent = $parent;
		$this->_project = $parent->_project;
	}
	
	/**
	 * Gets the parent node
	 * 
	 * @var Premanager\Execution\PageNode
	 */
	public function getParent() {
		return $this->_parent;
	}
	
	/**
	 * Gets the project that owns this node
	 * 
	 * @var Premanager\Models\Project
	 */
	public function getProject() {
		return $this->_project;
	}
	
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public abstract function getChildByName($name);
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public abstract function getName();
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public abstract function getTitle();
	
	/**
	 * Gets the title that is used in a context where the titles of the parent
	 * nodes are not displayed
	 * 
	 * @return string
	 */
	public function getStandAloneTitle() {
		return $this->getTitle();
	}
	
	/**
	 * Creates a page object that covers the data of this page node
	 * 
	 * @return Premanager\Execution\Page the page or null, if this page node does
	 *   not result in a page. 
	 */
	public function getPage() {
		return null;
	}

	/**
	 * Performs a call of this page
	 */
	public abstract function execute();
	
	/**
	 * Checks if this object represents the same page as $other (the
	 * implementation of Premanager\Execution\PageNode only checks if the object
	 * references are equal)
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $this == $other;
	}	     
	
	/**
	 * Gets the url of this page relative to Environment::getCurrent()->urlPrefix
	 * 
	 * @return string
	 */
	public function getURL() {
		// If there are two parents, one of them is _not_ root
		if ($this->parent && $this->parent->parent) {
			$name = $this->name;;
			if ($name)
				return $this->parent->url.'/'.rawurlencode($this->name);
			else
				return $this->parent->url;
		}
			
		// If there is exactly one parent, that is root
		else if ($this->parent)
			return rawurlencode($this->name);
			
		// Root node has an empty url	
		else
			return '';		
	}
	
	/**
	 * Finds a page node specified by its url relative the current project's root
	 * node's url
	 * 
	 * @param string|array $url the relative url or an array of path elements
	 * @param Premanager\Execution\PageNode $impact if the page node is not found,
	 *   contains the deepmost node found
	 * @return Premanager\Execution\PageNode the found page node or null
	 */
	public static function fromPath($url, &$impact = null) {
		if (is_array($url))
			$path = $url;
		else
			$path = explode('!/!', rtrim(trim($url), '/'));

		// Get the root node for the organization project
		$node = new StructurePageNode();
		              
		// Go through the path and find matching nodes 
		foreach ($path as $name) {
			// A name can contain special chars like ? or : and they are url-encoded.
			$name = rawurldecode($name);
			
			$child = $node->getChildByName($name);
			if ($child)
				$node = $child;
			else {
				$impact = $node;
				return null;
			}
		}
		return $node;
	}
}

?>
