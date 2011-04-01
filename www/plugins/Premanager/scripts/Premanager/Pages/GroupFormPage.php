<?php
namespace Premanager\Pages;

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
 * A page for adding or editing a group
 */
abstract class GroupFormPage extends FormPageNode {
	/**
	 * @var Premanager\Models\Group
	 */
	private $_group;
	/**
	 * @var Premanager\Models\Project
	 */
	private $_project;

	// ===========================================================================
	
	/**
	 * Creates a new GroupFormPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\Group the edited group, if editing a group
	 */
	public function __construct($parent, $group, $project = null) {
		parent::__construct($parent);
		
		if ($group && $project)
			throw new ArgumentException('If a group is edited ($group specified), '.
				'$project must be null');
		if (!$group && !$project)
			throw new ArgumentException('If no group is edited ($group is null), '.
				'$project must be specified');

		$this->_group = $group;
		$this->_project = $group ? $group->getProject() : $project;
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
		if (!$name)
			$errors[] = array('name',
				Translation::defaultGet('Premanager', 'noGroupNameInputtedError'));
		else if (!Group::isValidName($name))
			$errors[] = array('name',
				Translation::defaultGet('Premanager', 'nameContainsSlashes'));
		else if (!Group::isNameAvailable($name, $this->_project, $this->_group))
			$errors[] = array('name',
				Translation::defaultGet('Premanager', 'groupNameAlreadyExistsError'));
		
		$title = Strings::normalize(Request::getPOST('title'));
		if (!$title)
			$errors[] = array('title',
				Translation::defaultGet('Premanager', 'noGroupTitleInputtedError'));
		
		$color = Strings::normalize(Request::getPOST('color'));
		if (!$color)
			$errors[] = array('color',
				Translation::defaultGet('Premanager', 'noGroupColorInputtedError'));
		else if (!Group::isValidColor($color))
			$errors[] = array('color',
				Translation::defaultGet('Premanager', 'invalidGroupColorInputtedError'));
			
		// priority is only valid to organization groups
		if (!$this->_project->getID()) {
			$priority = trim(Request::getPOST('priority'));
			if (!$priority)
				$priority = 0;
			else if (!Types::isInteger($priority))
				$errors[] = array('priority', Translation::defaultGet('Premanager',
					'invalidGroupPriorityInputtedError'));
		} else 
			$priority = '';
			
		$text = trim(Request::getPOST('text'));
		if (!$text)
			$errors[] = array('text', 
				Translation::defaultGet('Premanager', 'noGroupTextInputtedError'));

		$autoJoin = !!Request::getPOST('autoJoin');
		$loginConfirmationRequired =
			!!Request::getPOST('loginConfirmationRequired');
		
		return array(
			'name' => $name,
			'title' => $title,
			'color' => $color,
			'priority' => $priority,
			'text' => $text,
			'autoJoin' => $autoJoin,
			'loginConfirmationRequired' => $loginConfirmationRequired);
	}
	
	/**
	 * Gets the values for a form without POST data or model
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		return array(
			'name' => '',
			'title' => '',
			'color' => '000000',
			'priority' => '0',
			'text' => '',
			'autoJoin' => false,
			'loginConfirmationRequired' => false);
	}
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected function getTemplate() {
		return new Template('Premanager', 'groupForm');
	}
}

?>
