<?php
namespace Premanager\Pages;

use Premanager\Models\StructureNodeType;

use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Debug\Debug;
use Premanager\Models\Project;
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
 * A page that allows to add a new structure node
 */
class AddStructureNodePage extends StructureNodeFormPage {
	/**
	 * Creates a new EditProjectPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\StructureNode $parentStructureNode the parent
	 *   structure node
	 */
	public function __construct($parent, StructureNode $parentStructureNode) {
		parent::__construct($parent, $parentStructureNode, true); // true: add
	} 

	// ===========================================================================
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return '+add';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'addNodeTitle');
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
		if (!Rights::requireRight(Right::getByName('Premanager', 'structureAdmin'),
			$this->getProject(), $errorResponse))
			return $errorResponse;
		
		$structureNode = $this->getStructureNode()->createChild($values['name'],
			$values['title'], StructureNodeType::SIMPLE);
		return new Redirection(
			$this->getParent()->getURL() . '/' . $structureNode->getName());
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof AddStructureNodePage;
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
