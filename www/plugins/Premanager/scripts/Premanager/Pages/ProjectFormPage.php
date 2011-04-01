<?php
namespace Premanager\Pages;

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
 * A page for adding or editing a project
 */
abstract class ProjectFormPage extends FormPageNode {
	private $_project;

	// ===========================================================================
	
	/**
	 * Creates a new EditProjectPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\Project the edited project, if editing a project
	 */
	public function __construct($parent, $project) {
		parent::__construct($parent);

		$this->_project = $project;
	} 

	// ===========================================================================
	
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
		$name = Strings::normalize(Request::getPOST('name'));
		// Editing or adding a project (not editing the organization)?
		if (!$this->_project || $this->_project->getID()) {
			if (!$name)
				$errors[] = array('name',
					Translation::defaultGet('Premanager', 'noProjectNameInputtedError'));
			else if (!Project::isValidName($name))
				$errors[] = array('name',
					Translation::defaultGet('Premanager', 'projectNameInvalidError'));
			else if (!Project::isNameAvailable($name, $this->_project))
				$errors[] = array('name', Translation::defaultGet('Premanager',
					'projectNameAlreadyExistsError'));
		}

		$title = Strings::normalize(Request::getPOST('title'));
		if (!$title)
			$errors[] = array('title',
				Translation::defaultGet('Premanager', 'noProjectTitleInputtedError'));
				
		$subTitle = Strings::normalize(Request::getPOST('subTitle'));
		
		$author = Strings::normalize(Request::getPOST('author'));
		if (!$author)
			$errors[] = array('author',
				Translation::defaultGet('Premanager', 'noProjectAuthorInputtedError'));
		
		$copyright = Strings::normalize(Request::getPOST('copyright'));
		if (!$copyright)
			$errors[] = array('copyright', Translation::defaultGet('Premanager',
				'noProjectCopyrightInputtedError'));  
		
		$description = Strings::normalize(Request::getPOST('description'));
		if (!$description)
			$errors[] = array('description', Translation::defaultGet('Premanager',
				'noProjectDescriptionInputtedError'));  
				
		$keywords = Strings::normalize(Request::getPOST('keywords'));
		
		return array(
			'name' => $name,
			'title' => $title,
			'subTitle' => $subTitle,
			'author' => $author,
			'copyright' => $copyright,
			'description' => $description,
			'keywords' => $keywords);
	}
	
	/**
	 * Gets the values for a form without POST data or model
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		return array();
	}
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected function getTemplate() {
		return new Template('Premanager', 'projectForm');
	}
}

?>
