<?php
namespace Premanager\Execution;

use Premanager\Debug\Debug;
use Premanager\Models\Project;
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
 * A page that contains a form
 */
abstract class FormPageNode extends PageNode {
	private $_values = array();
	private $_errors = array();

	// ===========================================================================
	
	/**
	 * Creates a new FormPageNode
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 */
	public function __construct($parent) {
		parent::__construct($parent);
	} 

	// ===========================================================================
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		if (Request::getPOST('submit')) {
			$errors = array();
			$this->_values = $this->getValuesFromPOST($errors);
			if (count($errors)) {
				foreach ($errors as $error) {
					if (!is_array($this->_errors[$error[0]]))
						$this->_errors[$error[0]] = array();
					$this->_errors[$error[0]][] = $error[1];
				}
			} else {
				return $this->applyValues($this->_values);
			}
		} else {
			$this->_values = $this->getDefaultValues();
		}
		
		$template = $this->getTemplate();
		$template->set('node', $this);
		$template->set('values', $this->_values);
		$template->set('errors', $this->_errors);
		
		return $this->getFormPage($template->get());
	}

	// ===========================================================================
	
	/**
	 * Creates the form page based on the form's HTML
	 * 
	 * @param string $formHTML the form's HTML
	 * @return Premanager\Execution\Response the response object to send
	 */
	protected function getFormPage($formHTML) {
		$page = new Page($this);
		$page->createMainBlock($formHTML);
		return $page;
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
	protected abstract function getValuesFromPOST(array &$errors);
	
	/**
	 * Gets the values for a form without POST data or model
	 * 
	 * @return array the array of values
	 */
	protected abstract function getDefaultValues();
	
	/**
	 * Applies the values and gets the response
	 * 
	 * Is called when the form is submitted and validated. 
	 * 
	 * @param array $values the array of values
	 * @return Premanager\Execution\Response the response to send
	 */
	protected abstract function applyValues(array $values);
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected abstract function getTemplate();
}

?>
