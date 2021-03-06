<?php
namespace Premanager\Pages;

use Premanager\Models\StructureNodeType;

use Premanager\QueryList\SortRule;
use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Execution\Redirection;
use Premanager\Execution\ToolBarItem;
use Premanager\Models\StructureNode;
use Premanager\Premanager;
use Premanager\Execution\TreeListPageNode;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Translation;
use Premanager\Execution\Page;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\Execution\ListPageNode;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page that allows to edit an existing structureNode
 */
class EditStructureNodePage extends StructureNodeFormPage {
	/**
	 * @var Premanager\QueryList\QueryList
	 */
	private $_childList;
	private $_realURL;

	// ===========================================================================
	
	/**
	 * Creates a new EditStructureNodePage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\StructureNode $structureNode the structureNode to
	 *   edit
	 */
	public function __construct($parent, StructureNode $structureNode) {
		parent::__construct($parent, $structureNode, false);
	} 

	// ===========================================================================
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return '+edit';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'editNodeTitle'); 
	}
	
	/**
	 * Applies the values and gets the response
	 * 
	 * Is called when the form is submitted and validated. 
	 * 
	 * @param array $values the array of values
	 * @return Premanager\Execution\Response the response to send
	 */
	protected function applyValues(array $values) {
		if (!Rights::requireRight(Right::getByName('Premanager',
			'structureAdmin'), $this->getProject(), $errorResponse))
			return $errorResponse;
			
		$this->getStructureNode()->setValues($values['name'], $values['title']);
		return new Redirection($this->getParent()->getURL());
	}
	
	/**
	 * Gets the values for a form without POST data
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		return array(
			'name' => $this->getStructureNode()->getName(),
			'title' => $this->getStructureNode()->getTitle());
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof EditStructureNodePage &&
			$other->getStructureNode() == $this->getStructureNode(); 
	}	      
	
	/**
	 * Gets the internal URL to the page node that is edited by this page
	 * 
	 * @return string the real url
	 */
	public function getRealURL() {
		if (!$this->_realURL) {
			$node = $this->getStructureNode();
			while ($node && $node->getParent()) {
				$this->_realURL = $node->getName(). '/' . $this->_realURL;
				$node = $node->getParent();
			}
		}
		return $this->_realURL;
	}
	
	/**
	 * Gets the list of child structure nodes
	 * 
	 * @return Premanager\Pages\StructureNodePage
	 */
	private function getChildList(){
		if ($this->_childList == null) {
			$this->_childList = $this->getStructureNode()->getChildren();
			$this->_childList = $this->_childList->sort(array(
				new SortRule($this->_childList->exprMember('title'))));
		}
		return $this->_childList;
	}
	
	/**
	 * Creates the form page based on the form's HTML
	 * 
	 * @param string $formHTML the form's HTML
	 * @return Premanager\Execution\Response the response object to send
	 */
	protected function getFormPage($formHTML) {
		if (Request::getPOST('cancel'))
			return new Redirection($this->getParent()->getURL());
		else
			return parent::getFormPage($formHTML);
	}
}

?>
