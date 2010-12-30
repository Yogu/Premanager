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
use Premanager\Models\User;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\Execution\ListPageNode;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page for adding or editing a user
 */
abstract class UserFormPage extends FormPageNode {
	/**
	 * @var Premanager\Models\User
	 */
	private $_user;

	// ===========================================================================
	
	/**
	 * Creates a new UserFormPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\User the edited user, if editing a user
	 */
	public function __construct($parent, $user) {
		parent::__construct($parent);

		$this->_user = $user;
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
				Translation::defaultGet('Premanager', 'noUserNameInputtedError'));
		else if (!User::isValidName($name))
			$errors[] = array('name',
				Translation::defaultGet('Premanager', 'nameContainsSlashes'));
		else if (!User::isNameAvailable($name, $this->_user))
			$errors[] = array('name',
				Translation::defaultGet('Premanager', 'userNameAlreadyExistsError'));
	
		$password = Request::getPOST('password');
		if ($password || !$this->_user) {
			$passwordConfirmation = Request::getPOST('passwordConfirmation');
			if (!$password)
				$errors[] = array('password',
					Translation::defaultGet('Premanager', 'noPasswordInputtedError'));
			else {
				if (!$passwordConfirmation)
					$errors[] = array('passwordConfirmation',
						Translation::defaultGet('Premanager',
						'noPasswordConfirmationInputtedError'));
				if ($password && $passwordConfirmation &&
					$password != $passwordConfirmation)
					$errors[] = array('passwordConfirmation',
						Translation::defaultGet('Premanager',
						'passwordConfirmationInvalidError'));
			}
		}
				
		$email = Strings::normalize(Request::getPOST('email'));
		$emailConfirmation =
			Strings::normalize(Request::getPOST('emailConfirmation'));
		if ($email) {
			if (!User::isValidEmail($email))
				$errors[] = array('email',
					Translation::defaultGet('Premanager', 'invalidEmailAddressError'));
			else if (!$emailConfirmation)
				$errors[] = array('emailConfirmation', Translation::defaultGet(
					'Premanager', 'noEmailConfirmationInputtedError'));
			else if ($emailConfirmation != $email)
				$errors[] = array('emailConfirmation', Translation::defaultGet(
					'Premanager', 'emailConfirmationInvalidError'));
		}
		
		if (!$this->_user || $this->_user->getID())
			$isEnabled = !!Request::getPOST('isEnabled');
		else
			$isEnabled = true;
		
		return array(
			'name' => $name,
			'email' => $email,
			'emailConfirmation' => $emailConfirmation,
			'password' => $password,
			'passwordConfirmation' => $passwordConfirmation,
			'isEnabled' => $isEnabled);
	}
	
	/**
	 * Gets the values for a form without POST data or model
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		return array(
			'name' => '',
			'email' => '',
			'isEnabled' => true);
	}
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected function getTemplate() {
		return new Template('Premanager', 'userForm');
	}
}

?>
