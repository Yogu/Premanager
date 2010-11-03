<?php
namespace Premanager\Execution;

use Premanager\Models\Project;

use Premanager\Model;
use Premanager\QueryList\QueryList;
use Premanager\DateTime;
use Premanager\Debug\Debug;
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
	 * The url of this page relative to Environment::getCurrent()->geturlPrefix()
	 * 
	 * @var string
	 */
	public $url = Module::PROPERTY_GET_ACRONYM;
	
	/**
	 * The url of this page relative to Environment::getCurrent()->geturlPrefix(),
	 * including a query part if exists
	 * 
	 * @var string
	 */
	public $fullURL = Module::PROPERTY_GET;
	
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
		$this->_project = $parent ? $parent->_project : null;
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
	public function getChildByName($name) {
		
	}
	
	/**
	 * Gets an array of all child page nodes
	 * 
	 * @param int $count the number of items the array should contain at most or
	 *   -1 if all available items should be contained
	 * @param Premanager\Execution\PageNode $referenceNode the page node that
	 *   should be always in the array
	 * @return array an array of the child Premanager\Execution\PageNode's
	 */
	public function getChildren($count = -1, PageNode $referenceNode = null) {
		return array();
	}
	
	/**
	 * Gets an array of the models to be included for the result of getChildren()
	 * 
	 * @param int $count the count parameter of getChildren
	 * @param Premanager\QueryList\QueryList $list the list that contains the
	 *   models of the child page nodes 
	 * @param Premanager\Model $model the model of this page node
	 * @return array an array of models to be included
	 */
	protected function getChildrenHelper(QueryList $list, $model, $count) {
		if ($model !== null && !($model instanceof Model))
			throw new ArgumentException('$model must either be null or a '.
				'Premanager\Model.', 'model');
		
		// If the reference node is specified and used (not all items should be
		// contained in the array), find the structure node of the reference node in
		// the list and use this index as center
		$startIndex = 0;
		if ($model && $count >= 0) {
			$index = $list->indexOf($model);
			if ($index >= 0)
				$startIndex = max($index - floor(($count-1) / 2), 0); 		
		}
		
		// If not all items should be contained, select the speicified range
		if ($count >= 0)
			return $list->getRange($startIndex, $count, true);
		else
			return $list->getAll();
	}
	
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
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public abstract function getResponse();
	
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
	 * Gets the url of this page relative to Environment::getCurrent()->geturlPrefix()
	 * 
	 * @return string
	 */
	public function getURL() {
		// If there are two parents, one of them is _not_ root
		if ($this->getParent() && $this->getParent()->getParent()) {
			if ($name = $this->getName())
				return $this->getParent()->url.'/'.rawurlencode($name);
			else
				return $this->getParent()->url;
		}
			
		// If there is exactly one parent, that is root
		else if ($this->getParent())
			return rawurlencode($this->getName());
			
		// Root node has an empty url	
		else
			return '';		
	}
	
	/**
	 * Gets the url of this page relative to Environment::getCurrent()->geturlPrefix(),
	 * including a query part if exists
	 * 
	 * @return string
	 */
	public function getFullURL() {
		$url = $this->getURL();
		if ($query = $this->getQueryString())
			$url .= '?'.$query;
		return $url;
	}
	
	/**
	 * Gets an array of names and values of the query ('page' => 7 for '?page=7')
	 * 
	 * @return array
	 */
	public function getURLQuery() {
		return array();
	}
	
	/**
	 * Gets the query part of the url using the getURLQuery function (without the
	 * question mark)
	 * 
	 * @return string the query string
	 */
	public function getQueryString() {
		$query = $this->getURLQuery();
		$queryString = '';
		foreach ($query as $name => $value) {
			if ($queryString)
				$queryString .= '&';
			$queryString .= rawurlencode($name);
			if ($value)
				$queryString .= '='.rawurlencode($value);
		}
		return $queryString;
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
			$name = trim(rawurldecode($name));
			if (!$name)
				continue;
			
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
