<?php
namespace Premanager\Pages;

use Premanager\Models\StructureNode;
use Premanager\Types;
use Premanager\Debug\Debug;
use Premanager\Execution\FormPageNode;
use Premanager\Execution\Redirection;
use Premanager\Strings;
use Premanager\Models\Project;
use Premanager\Premanager;
use Premanager\Execution\TreeListPageNode;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Translation;
use Premanager\Execution\Page;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\Models\Group;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\Execution\ListPageNode;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page for editing a structure node or adding a new child node
 */
abstract class StructureNodeFormPage extends FormPageNode {
	/**
	 * @var Premanager\Models\StructureNode
	 */
	private $_structureNode;
	private $_add;

	// ===========================================================================
	
	/**
	 * Creates a new StructureNodePage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\StructureNode the edited / parent structure node
	 * @param bool $add true to add a new child to the specified structure node,
	 *   false to edit the specified structure node
	 */
	public function __construct($parent, StructureNode $structureNode, $add) {
		parent::__construct($parent);

		$this->_structureNode = $structureNode;
		$this->_add = $add;
	} 

	// ===========================================================================
	
	/**
	 * Gets the structure node that is represented by this page
	 * 
	 * @return Premanager\Models\StructureNode
	 */
	public function getStructureNode() {
		return $this->_structureNode;
	}
	
	/**
	 * Loads the values from POST parameters and validates them
	 * 
	 * The $errors parameter should be an array of following:
	 *   array(
	 *     fieldName - the name of the field that is invalid
	 *     errorMessage - a description of the error
	 *   )
	 * 
	 * @param array &$errors an array of errors as described above
	 * @return array the array of values
	 */
	protected function getValuesFromPOST(array &$errors) {
		$title = Strings::normalize(Request::getPOST('title'));
		if (!$title)
			$errors[] = array('title',
				Translation::defaultGet('Premanager', 'noNodeTitleInputtedError'));
				
		if ($this->_add || $this->_structureNode->getParent()) {
			$name = Strings::normalize(Request::getPOST('name'));
			if (!$name)
				$errors[] = array('name',
					Translation::defaultGet('Premanager', 'noNodeNameInputtedError'));
			else if (!StructureNode::isValidName($name))
				$errors[] = array('name',
					Translation::defaultGet('Premanager', 'invalidStructureNodeName'));
			else {
				if (!$this->_add &&
					!$this->_structureNode->getParent()->isNameAvailable($name,
						$this->_structureNode))
				{
					$errors[] = array('name',
						Translation::defaultGet('Premanager',
							'nodeNameAlreadyExistsError'));
				}
				if ($this->_add && !$this->_structureNode->isNameAvailable($name)) {
					$errors[] = array('name',
						Translation::defaultGet('Premanager',
							'nodeNameAlreadyExistsError'));
				}
			}
		}
		
		return array(
			'name' => $name,
			'title' => $title);
	}
	
	/**
	 * Gets the values for a form without POST data or model
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		return array(
			'name' => '',
			'title' => '');
	}
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected function getTemplate() {
		$template = new Template('Premanager', 'structureNodeForm');
		$template->set('structureNode', $this->_structureNode);
		$template->set('add', $this->_add);
		return $template;
	}
}

?>
