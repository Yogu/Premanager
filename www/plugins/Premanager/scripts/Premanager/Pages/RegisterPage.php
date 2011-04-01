<?php
namespace Premanager\Pages;

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
 * A page for registering a new user
 */
class RegisterPage extends TreeFormPageNode {
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
		else if (!User::isNameAvailable($name))
			$errors[] = array('name',
				Translation::defaultGet('Premanager', 'userNameAlreadyExistsError'));
	
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
				
		$email = Strings::normalize(Request::getPOST('email'));
		$emailConfirmation =
			Strings::normalize(Request::getPOST('emailConfirmation'));
		if ($email) {
			if (!User::isValidEmail($email))
				$errors[] = array('email',
					Translation::defaultGet('Premanager', 'invalidEmailAddressError'));
			else if (!User::isEmailAvailable($email))
				$errors[] = array('email', Translation::defaultGet('Premanager',
					'emailAddressAlreadyInUseError'));
			else if (!$emailConfirmation)
				$errors[] = array('emailConfirmation', Translation::defaultGet(
					'Premanager', 'noEmailConfirmationInputtedError'));
			else if ($emailConfirmation != $email)
				$errors[] = array('emailConfirmation', Translation::defaultGet(
					'Premanager', 'emailConfirmationInvalidError'));
		} else if (!self::isEmailOptional())
			$errors[] = array('email', Translation::defaultGet('Premanager',
				'noRegistrationEmailInputtedError'));
		
		return array(
			'name' => $name,
			'email' => $email,
			'emailConfirmation' => $emailConfirmation,
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
		$template = new Template('Premanager', 'registerForm');
		$template->set('emailOptional', self::isEmailOptional());
		return $template;
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
		if (!Rights::requireRight(array(
			Right::getByName('Premanager', 'register'),
			Right::getByName('Premanager', 'registerWithoutEmail')), null,
			$errorResponse))
			return $errorResponse;
		
		$user = User::createNew($values['name'], $values['password'], '',
			self::isEmailOptional());
			if (!self::isEmailOptional())
				$user->setEnableOnEmailConfirmation(true);
		
		$email = $values['email'];
		if ($email)
			$key = $user->setUnconfirmedEmail($email);
			
		$params = array(
				'userName' => $user->getName(),
				'organizationTitle' => Project::getOrganization()->getTitle(),
				'linkURL' => URL::fromTemplate().$this->getURL().'?key='.$key);
			
		if (!self::isEmailOptional()) {
			$mail = new Mail();
			$mail->title = Translation::defaultGet('Premanager',
				'userAccountActivationEmailTitle', array(
					'organizationTitle' => Project::getOrganization()->getTitle()));
			$mail->plainContent = Translation::defaultGet('Premanager',
				'userAccountActivationEmailPlainMessage', $params);
			$mail->createMainBlock('<p>'.Translation::defaultGet('Premanager',
				'userAccountActivationEmailMessage', $params).'</p>');
			if ($mail->send($email, $user->getName())) {
				return Page::createMessagePage($this, Translation::defaultGet(
					'Premanager', 'userAccountActivationEmailSentMessage'));
			} else {    
				//TODO Premanager::logToDB('warning', 'Failed to send user account '.
				//	'activation email to '.$email);
				
				return Page::createMessagePage($this, Translation::defaultGet(
					'Premanager', 'userAccountActivationEmailFailedErrorMessage'));	
			}
		} else if ($email) {
			$mail = new Mail();
			$mail->title = Translation::defaultGet('Premanager',
				'userEmailConfirmationOnAccountCreationEmailTitle', array(
					'organizationTitle' => Project::getOrganization()->getTitle()));
			$mail->plainContent = Translation::defaultGet('Premanager',
				'userEmailConfirmationOnAccountCreationEmailPlainMessage', $params);
			$mail->createMainBlock('<p>'.Translation::defaultGet('Premanager',
				'userEmailConfirmationOnAccountCreationEmailMessage', $params).'</p>');
			if ($mail->send($email, $user->getName())) {
				$message = 'userAccountWithEmailCreatedMessage';
			} else {  
				//TODO
				// Premanager::logToDB('warning', 'Failed to send email confirmation '.
				//	'email to '.$email);     
					
				$message = 'registerEmailConfirmationEmailFailedMessage';
			}
		} else {
			$message = 'userAccountCreatedMessage';
		}
		
		$template = new Template('Premanager', 'loginForm');
		$template->set('canRegister', false);
		$page = new Page($this);
		$page->createMainBlock(
			'<p>'.Translation::defaultGet('Premanager', $message, $params).'</p>');
		$page->appendBlock(PageBlock::createSimple(
			Translation::defaultGet('Premanager', 'loginTitle'),
			$template->get()));
		return $page;
	}
	
	/**
	 * Creates the form page based on the form's HTML
	 * 
	 * @param string $formHTML the form's HTML
	 * @return Premanager\Execution\Response the response object to send
	 */
	protected function getFormPage($formHTML) {
		if ($key = Request::getGET('key')) {
			$l = User::getUsers();
			$l = $l->filter($l->exprEqual($l->exprMember('unconfirmedEmailKey'),
				$key));
			if ($l->getCount() == 1) {
				$user = $l->get(0);
				$enabled = $user->isEnabled();
				$user->confirmUnconfirmedEmail();
				if (!$enabled && $user->isEnabled()) {
					$page = new Page($this);
					$page->createMainBlock('<p>'.Translation::defaultGet('Premanager',
						'emailAddressConfirmedWithWaitForEmailMessage').'</p>');
					$template = new Template('Premanager', 'loginForm');
					$page->appendBlock(PageBlock::createSimple(
						Translation::defaultGet('Premanager', 'loginTitle'),
						$template->get()));
					return $page;
				} else
					return Page::createMessagePage($this, Translation::defaultGet(
						'Premanager', 'emailAddressConfirmedMessage'));
			} else
				return Page::createMessagePage($this, Translation::defaultGet(
					'Premanager', 'confirmEmailInvalidKeySpecifiedError'));
		} else {
			if (!Rights::requireRight(array(
				Right::getByName('Premanager', 'register'),
				Right::getByName('Premanager', 'registerWithoutEmail')), null,
				$errorResponse, false))
				return $errorResponse;
				
			$page = new Page($this);
			$page->createMainBlock($formHTML);
			return $page;
		}
	}
	
	/**
	 * Gets an array of names and values of the query ('page' => 7 for '?page=7')
	 * 
	 * @return array
	 */
	public function getURLQuery() {
		$query = array();
		if (Request::getGET('key')) {
			$query['key'] = Request::getGET('key');
		}
		return $query;
	}
	
	/**
	 * Checks whether the user has the right to register without email address
	 * 
	 * @return bool true, if the email address is optional
	 */
	private static function isEmailOptional() {
		return
			Rights::hasRight(Right::getByName('Premanager', 'registerWithoutEmail'));		
	}
}

?>
