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
 * A page that sends an email with a key to change the password if it is lost
 */
class PasswordLostPage extends TreeFormPageNode {
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
		if ($user = self::getUserFromKey()) {
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
				'password' => $password,
				'passwordConfirmation' => $passwordConfirmation);
		} else {
			$email = Strings::normalize(Request::getPOST('email'));
			if (!$email)
				$errors[] = array('email',
					Translation::defaultGet('Premanager',
						'passwordLostNoEmailInputtedError'));
			else {
				$l = User::getUsers();
				$l = $l->filter($l->exprEqual($l->exprMember('email'), $email));
				if (!$l->getCount())
					$errors[] = array('email', Translation::defaultGet('Premanager',
						'passwordLostEmailNotFoundError'));
				else {
					$user = $l->get(0);
					
					if (!$user->isEnabled())
						$errors[] = array('email', Translation::defaultGet('Premanager',
							'passwordLostUserDisabledError'));
				}
			}
			
			return array(
				'email' => $email,
				'user' => $user);
		}
	}
	
	/**
	 * Gets the values for a form without POST data or model
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		return array(
			'email' => '',
			user => null,
			'password' => '',
			'passwordConfirmation' => '');
	}
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected function getTemplate() {
		if ($user = self::getUserFromKey()) {
			$template = new Template('Premanager', 'passwordLostSecond');
			$template->set('user', $user);
		} else
			$template = new Template('Premanager', 'passwordLostFirst');
		
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
		if ($user = self::getUserFromKey()) {
			$user->setPassword($values['password']);
			
	 		// If there is already a session started, remove it
			if ($session = Session::getByKey(Request::getCookie('session')))
				$session->delete();
			
			// If there is a session of this user, delete it
			if (($session = Session::getByUser($user)) && !$session->isDisposed())
				$session->delete();
				
			// Create new session
			$session = Session::createNew($user, false);
			$user->updateLoginTime($hidden);
			
			// Set session cookie
			Output::setCookie('session', $session->getKey());
			
			return Page::createMessagePage($this,
				Translation::defaultGet('Premanager', 'passwordLostThirdMessage'));
		} else {
			$user = $values['user'];
			
			$key = $user->resetPassword();
				
			$params = array(
					'userName' => $user->getName(),
					'linkURL' => URL::fromTemplate().$this->getURL().'?key='.$key);
				
			$mail = new Mail();
			$mail->title =
				Translation::defaultGet('Premanager', 'passwordLostEmailTitle');
			$mail->plainContent = Translation::defaultGet('Premanager',
				'passwordLostEmailPlainMessage', $params);
			$mail->createMainBlock('<p>'.Translation::defaultGet('Premanager',
				'passwordLostEmailMessage', $params).'</p>');
			if ($mail->send($email, $user->getName())) {
				return Page::createMessagePage($this, Translation::defaultGet(
					'Premanager', 'passwordLostSucceededMessage'));
			} else {    
				//TODO Premanager::logToDB('warning', 'Failed to send user account '.
				//	'activation email to '.$email);
				
				return Page::createMessagePage($this, Translation::defaultGet(
					'Premanager', 'passwordLostEmailFailedErrorMessage'));	
			}
		}
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
			if (!$user) {
				if ($user === false)
					$message = 'passwordLostUserDisabledError';
				else
					$message = 'passwordLostInvalidKeyError';
				return Page::createMessagePage($this, Translation::defaultGet(
					'Premanager', $message));
			}
		}
		
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
	 * @return Premanager\Models\User the user, or null on invalid key or false on
	 *  disabled user account
	 */
	private static function getUserFromKey() {
		static $user;
		
		if ($user === null) {
			$user = false;
			if ($key = Request::getGET('key')) {
				$l = User::getUsers();
				$l = $l->filter($l->exprEqual($l->exprMember('resetPasswordKey'),
					$key));
				if ($l->getCount() == 1) {
					$user = $l->get(0);
					if (!$user->isEnabled())
						$user = 0;
				}
			}
		}
		
		if ($user === false)
			return null;
		else if ($user === 0)
			return false;
		else
			return $user;
	}
}

?>
