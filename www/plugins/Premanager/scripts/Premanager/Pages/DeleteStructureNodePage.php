<?php
namespace Premanager\Pages;

use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Execution\Redirection;
use Premanager\Execution\ToolBarItem;
use Premanager\Models\Project;
use Premanager\Premanager;
use Premanager\Execution\TreeListPageNode;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Translation;
use Premanager\Execution\Page;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\Models\StructureNode;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\Execution\ListPageNode;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page that asks whether to delete a structure node
 */
class DeleteStructureNodePage extends PageNode {	
	private $_structureNode;

	// ===========================================================================
	
	/**
	 * Creates a new DeleteStructureNodePage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\StructureNode $structureNode the structure node to
	 *   delete
	 */
	public function __construct($parent, StructureNode $structureNode) {
		parent::__construct($parent);

		$this->_structureNode = $structureNode;
	} 

	// ===========================================================================
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return '+delete';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'deleteNode');
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		if (Request::getPOST('confirm')) {	
			if (!Rights::requireRight(
				Right::getByName('Premanager', 'structureAdmin'), $this->getProject(),
				$errorResponse))
				return $errorResponse;
				
			$this->_structureNode->delete();
			return new Redirection($this->getParent()->getParent()->getURL());
		} else if (Request::getPOST('cancel')) {
			return new Redirection($this->getParent()->getURL());
		} else {
			$page = new Page($this);
			$template = new Template('Premanager', 'confirmation');
			$template->set('message', Translation::defaultGet('Premanager',
				'deleteNodeMessage', array('title' => $this->_structureNode->getTitle(),
				'url' => $this->getParent()->getURL())));
			$page->createMainBlock($template->get());
			return $page;
		}
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof DeleteStructureNodePage &&
			$other->_structureNode === $this->_structureNode; 
	}	    
}

?>
