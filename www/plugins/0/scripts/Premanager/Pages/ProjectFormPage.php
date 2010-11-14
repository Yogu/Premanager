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
		if (!$this->getModel() || !$this->getModel()->getID()) {
			if (!$name)
				$errors[] = array('name',
					Translation::defaultGet('Premanager', 'noProjectNameInputtedError'));
			else if (!Project::isValidName($name))
				$errors[] = array('name',
					Translation::defaultGet('Premanager', 'projectNameInvalidError'));
			else if (!Project::isNameAvailable($name, $this->getModel()))
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
	 * Loads the values from a model
	 * 
	 * @param mixed $model the model
	 * @return array the array of values
	 */
	protected function getValuesFromModel($model) {
		return array(
			'name' => $model->getName(),
			'title' => $model->getTitle(),
			'subTitle' => $model->getSubTitle(),
			'author' => $model->getAuthor(),
			'copyright' => $model->getCopyright(),
			'description' => $model->getDescription(),
			'keywords' => $model->getKeywords());
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
		// TODO: replace with tree url
		$projectsURL = $this->getModel() ? $this->getParent()->getParent() :
			$this->getParent();
		$projectsURL = $projectsURL->getURL();
		
		if ($project = $this->getModel()) {
			$project->setValues($values['name'], $values['title'],
				$values['subTitle'], $values['author'], $values['copyright'],
				$values['description'], $values['keywords']);
			return new Redirection($projectsURL . '/' .
				($project->getID() ? rawurlencode($project->getName()) : '-'));
		} else {
			$project = Project::createNew($values['name'], $values['title'],
				$values['subTitle'], $values['author'], $values['copyright'],
				$values['description'], $values['keywords']);
			return new Redirection($projectsURL.'/'.$project->getName());
		}
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
