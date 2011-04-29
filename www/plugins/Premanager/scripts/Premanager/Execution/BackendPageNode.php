<?php
namespace Premanager\Execution;

use Premanager\IO\Request;
use Premanager\IO\StatusCode;
use Premanager\Execution\Template;
use Premanager\NotImplementedException;
use Premanager\ArgumentException;
use Premanager\Models\StructureNode;
use Premanager\IO\Output;
use Premanager\Module;

/**
 * Defines a basic backend page node
 */
abstract class BackendPageNode extends PageNode {
	/**
	 * @var string
	 */
	private $_name;
	
	/**
	 * Creates a BackendPageNode
	 * 
	 * @param Premanager\Models\PageNode|null $parent the parent node
	 * @param string $name the name of this page node
	 */
	public function __construct($parent, $name) {
		parent::__construct($parent);
		
		if (!$name)
			throw new ArgumentException('$name must be a string and not empty',
				'name');
		
		$this->_name = $name;
	}
	
	/**
	 * Gets an array of names and values of the query ('page' => 7 for '?page=7')
	 * 
	 * @return array
	 */
	public function getURLQuery() {
		return $_GET;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'backendPageTitle');
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$response = new XMLResponse();
		$w = $response->writer;
		$w->startElement('error');
		$w->startElement('message');
		$w->text('This page cannot be accessed directly');
		$w->endElement();
		$w->endElement();
		return $response;
	}
}

?>
