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
 * A page to change the email address of own account
 */
class ChangeEmailPage extends TreeFormPageNode {
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
		$user = Environment::getCurrent()->getUser();
		
		$email = Strings::normalize(Request::getPOST('email'));
		$emailConfirmation =
			Strings::normalize(Request::getPOST('emailConfirmation'));
		if ($email) {
			if (!User::isValidEmail($email))
				$errors[] = array('email',
					Translation::defaultGet('Premanager', 'invalidEmailAddressError'));
			else if (!User::isEmailAvailable($email, $user))
				$errors[] = array('email', Translation::defaultGet('Premanager',
					'emailAddressAlreadyInUseError'));
			else if (!$emailConfirmation)
				$errors[] = array('emailConfirmation', Translation::defaultGet(
					'Premanager', 'noEmailConfirmationInputtedError'));
			else if ($emailConfirmation != $email)
				$errors[] = array('emailConfirmation', Translation::defaultGet(
					'Premanager', 'emailConfirmationInvalidError'));
		} else if (!self::canRemoveEmail())
			$errors[] = array('email', Translation::defaultGet('Premanager',
				'cantRemoveEmailError'));
		
		return array(
			'email' => $email,
			'emailConfirmation' => $emailConfirmation);
	}
	
	/**
	 * Gets the values for a form without POST data or model
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		$user = Environment::getCurrent()->getUser();
		
		return array(
			'email' => $user->getEmail(),
			'emailConfirmation' => $user->getEmail());
	}
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected function getTemplate() {
		return new Template('Premanager', 'changeEmail');
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
		$user = Environment::getCurrent()->getUser();
		
		if ($values['email'] == $user->getEmail()) {
			return Page::createMessagePage($this, Translation::defaultGet
				('Premanager', 'emailNotChangedMessage'));
		} else {
			if ($values['email'] == $user->getUnconfirmedEmail())
				return Page::createMessagePage($this, Translation::defaultGet
					('Premanager', 'changedEmailToUnconfirmedMessage', array
					('expirationTime' => $user->getUnconfirmedEmailExpirationTime())));
			else {
				$email = $values['email'];
				$key = $user->setUnconfirmedEmail($email);
			
				$params = array(
						'userName' => $user->getName(),
						'linkURL' => URL::fromTemplate().$this->getURL().'?key='.$key);
					
				$mail = new Mail();
				$mail->title = Translation::defaultGet
					('Premanager', 'userEmailConfirmationEmailTitle');
				$mail->plainContent = Translation::defaultGet('Premanager',
					'userEmailConfirmationEmailPlainMessage', $params);
				$mail->createMainBlock('<p>'.Translation::defaultGet('Premanager',
					'userEmailConfirmationEmailMessage', $params).'</p>');
				if ($mail->send($email, $user->getName())) {
					return Page::createMessagePage($this, Translation::defaultGet(
						'Premanager', 'unconfirmedEmailChangedMessage'));
				} else {    
					//TODO Premanager::logToDB('warning', 'Failed to send user account '.
					//	'activation email to '.$email);
					
					return Page::createMessagePage($this, Translation::defaultGet(
						'Premanager', 'passwordLostEmailFailedErrorMessage'));	
				}
			}
		}
			
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
		if ($key = Request::getGET('key')) {
			$user = self::getUserFromKey();
			if ($user) {
				$enabled = $user->isEnabled();
				$user->confirmUnconfirmedEmail();
				if (!$enabled && $user->isEnabled()) {
					$template = new Template('Premanager', 'loginForm');
					$template->set('canRegister', false);
					$page = new Page($node);
					$page->createMainBlock('<p>'.Translation::defaultGet('Premanager',
						'emailConfirmedAccountActivatedMessage').'</p>');
					$page->appendBlock(PageBlock::createSimple(
						Translation::defaultGet('Premanager', 'loginTitle'),
						$template->get()));
					return $page;
				} else
					return Page::createMessagePage($this, Translation::defaultGet(
						'Premanager', 'emailConfirmedMessage',
						array('email' => $user->getEmail())));
			} else
				return Page::createMessagePage($this, Translation::defaultGet(
					'Premanager', 'confirmEmailInvalidKeySpecifiedError'));
		}
		
		if (!Environment::getCurrent()->getUser()->getID())
			return Page::createMessagePage($this,
				Translation::defaultGet('Premanager', 'guestChangesEmailMessage'));
		
		$page = new Page($this);
		$page->createMainBlock($formHTML);
		return $page;
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
	 * Gets the user assigned to the key given by GET data
	 * 
	 * @return Premanager\Models\User the user, or null on invalid key
	 */
	private static function getUserFromKey() {
		static $user;
		
		if ($user === null) {
			$user = false;
			if ($key = Request::getGET('key')) {
				$l = User::getUsers();
				$l = $l->filter($l->exprEqual($l->exprMember('unconfirmedEmailKey'),
					$key));
				if ($l->getCount() == 1)
					$user = $l->get(0);
			}
		}
		
		if ($user === false)
			return null;
		else
			return $user;
	}
	
	/**
	 * Checks whether the user has the right to remove the email
	 * 
	 * @return bool true, if the email address is optional
	 */
	private static function canRemoveEmail() {
		return
			Rights::hasRight(Right::getByName('Premanager', 'registerWithoutEmail'));
	}
}

?>
