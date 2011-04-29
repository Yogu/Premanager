<?php
namespace Premanager\Pages;

use Premanager\Types;
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
 * A page that allows to move a structure node into another node
 */
class MoveStructureNodePage extends PageNode {	
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
		if (!$structureNode->getParent())
			throw new ArgumentException('Root page node cannot be moved');
		
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
		return '+move';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'moveNode');
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		foreach (Request::getPOSTValues() as $key => $value) {
			if (substr($key, 0, 10) == 'move-into-') {
				$id = substr($key, 10);
				break;
			}
		}
		if (Types::isInteger($id) && $id > 0)
			$selectedNode = StructureNode::getByID($id);
		if ($selectedNode) {
			if ($selectedNode === $this->_structureNode->getParent())
				$error = Translation::defaultGet('Premanager',
					'moveTargetNotChangedError');
			else if ($selectedNode === $this->_structureNode ||
				$selectedNode->isChildOf($this->_structureNode))
				$error = Translation::defaultGet('Premanager',
					'moveTargetIsChildError');
			else if ($selectedNode->getTreeClass())
				$error = Translation::defaultGet('Premanager', 'moveIntoTreeNodeError');
			else if (!$selectedNode->areNamesAvailable($this->_structureNode))
				$error = Translation::defaultGet('Premanager',
					'nodeNameAlreadyExistsInTargetError');
			else {
				if (!Rights::requireRight(
					Right::getByName('Premanager', 'structureAdmin'), $this->getProject(),
					$errorResponse))
					return $errorResponse;
				
				$this->_structureNode->setParent($selectedNode);
				$url = $this->_structureNode->getURL();
				$url = PageNode::getTreeURL('Premanager', 'structure') . '/-' .
					($url ? '/' . $url : '');
				return new Redirection($url);
			}
		} else
			$selectedNode = $this->_structureNode->getParent(); 
		
		$page = new Page($this);
		
		$page->createMainBlock(
			'<p>'.Translation::defaultGet('Premanager', 'moveNodeMessage',
				array('title' => $this->_structureNode->getTitle())).'</p>'.
			($error ? '<ul class="input-errors"><li>'.$error.'</li></ul>' : ''));
		
		$template = new Template('Premanager', 'structureNodeMove');
		$template->set('node', $this);
		$template->set('structureNode', $this->_structureNode);
		$template->set('rootNode', $this->getProject()->getRootNode());
		$template->set('selectedNode', $selectedNode);
		$page->appendBlock(PageBlock::createSimple(
			Translation::defaultGet('Premanager', 'moveNodeTitle'),
			$template->get()));
		return $page;
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof MoveStructureNodePage &&
			$other->_structureNode === $this->_structureNode; 
	}	    
}

?>
