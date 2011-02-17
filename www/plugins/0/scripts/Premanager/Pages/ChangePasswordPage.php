<?php
namespace Premanager\Pages;

use Premanager\Models\Session;
use Premanager\Execution\Environment;
use Premanager\URL;
use Premanager\IO\Mail;
use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Execution\TreeFormPageNode;
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
 * A page to change the own password
 */
class ChangePasswordPage extends TreeFormPageNode {
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
		$currentPassword = Request::getPOST('currentPassword');
		if (!$currentPassword)
			$errors[] = array('currentPassword', Translation::defaultGet('Premanager',
				'noCurrentPasswordInputtetError'));
		else if (!Environment::getCurrent()->getUser()->checkPassword(
			$currentPassword))
			$errors[] = array('currentPassword', Translation::defaultGet('Premanager',
				'wrongCurrentPasswordInputtetError'));
		
		$password = Request::getPOST('password');
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
		
		return array(
			'currentPassword' => $currentPassword,
			'password' => $password,
			'passwordConfirmation' => $passwordConfirmation);
	}
	
	/**
	 * Gets the values for a form without POST data or model
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		return array(
			'currentPassword' => '',
			'password' => '',
			'passwordConfirmation' => '');
	}
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected function getTemplate() {
		return new Template('Premanager', 'changePassword');
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
		Environment::getCurrent()->getUser()->setPassword($values['password']);
			
		return Page::createMessagePage($this,
			Translation::defaultGet('Premanager', 'passwordChangedMessage'));
	}
	
	/**
	 * Creates the form page based on the form's HTML
	 * 
	 * @param string $formHTML the form's HTML
	 * @return Premanager\Execution\Response the response object to send
	 */
	protected function getFormPage($formHTML) {
		if (!Environment::getCurrent()->getUser()->getID())
			return Page::createMessagePage($this,
				Translation::defaultGet('Premanager', 'guestChangesPasswordMessage'));
		
		$page = new Page($this);
		$page->createMainBlock($formHTML);
		return $page;
	}
}

?>
