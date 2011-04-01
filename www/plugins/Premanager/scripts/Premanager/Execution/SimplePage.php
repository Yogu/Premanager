<?php 
namespace Premanager\Execution;

use Premanager\Debug\Debug;
use Premanager\Models\StructureNode;
use Premanager\URL;
use Premanager\Models\Project;
use Premanager\Module;
use Premanager\IO\Config;

/**
 * Defines a page only with a html content, without headers and navigation
 */
class SimplePage extends Response {
	/**
	 * @var Premanager\Execution\PageNode
	 */
	private $_node;
	
	// ===========================================================================
	
	/**
	 * The html content
	 * 
	 * @var string
	 */
	public $content = array();
	
	/**
	 * The HTML status code for this page.
	 * 
	 * Default is 200 (OK). Other possible values are for example 404 (Not Found)
	 * or 403 (Forbidden)
	 * 
	 * @var int
	 */
	public $statusCode;
	
	// ===========================================================================
	
	/**
	 * Creates a new page
	 * 
	 * @param Premanager\Execution\PageNode $node the node that creates this page
	 *   (is used for navigation)
	 */
	public function __construct(PageNode $node) {
		parent::__construct();
		$this->_node = $node;
		$this->statusCode = 200;
	}
	
	// ===========================================================================
	
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
		$template = new Template('Premanager', 'simplePage');
		
		$template->set('node', $this->_node);
		$template->set('project', $this->_node->getProject());
		$template->set('projectNode', $projectNode);
		$template->set('isIndexPage', $this->_node instanceof StructurePageNode &&
			$this->_node->isProjectNode());
		$template->set('environment', Environment::getCurrent());
		$template->set('organization', Project::getOrganization());
		$template->set('canonicalURLPrefix', URL::fromTemplate(
			Environment::getCurrent()->getlanguage(), Edition::COMMON));
		$template->set('emptyURLPrefix', Config::getEmptyURLPrefix());
		$template->set('staticURLPrefix', Config::getStaticURLPrefix());
		$template->set('content', $this->content);
		
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
