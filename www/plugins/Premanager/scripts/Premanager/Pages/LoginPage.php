<?php
namespace Premanager\Pages;

use Premanager\Models\Session;

use Premanager\Debug\Debug;

use Premanager\Event;

use Premanager\Models\UserStatus;

use Premanager\IO\DataBase\DataBase;
use Premanager\Execution\Environment;
use Premanager\Models\StructureNode;
use Premanager\Execution\ListPageNode;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Translation;
use Premanager\Execution\Page;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\Execution\TreePageNode;
use Premanager\Models\User;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page that shows a login form
 */
class LoginPage extends TreePageNode {
	/**
	 * The user has logged in successfully
	 * 
	 * Parameters:
	 *   Premanager\Models\User $user the user who logged in
	 *   
	 * @var Premanager\Event
	 */
	public static $loginSuccessful;
	
	/**
	 * The user tried to log in but the login failed
	 * 
	 * Parameters:
	 *   int $reason enum Premanager\Pages\LoginFailedReason
	 *   Premanager\Models\User $user the user who logged in
	 *   
	 * @var Premanager\Event
	 */
	public static $loginFailed;
	
	/**
	 * The user logged out
	 *   
	 * @var Premanager\Event
	 */
	public static $loggedOut;
	
	// =========================================================================== 
	
	public static function __init() {
		self::$loginSuccessful = new Event(__CLASS__);
		self::$loginFailed = new Event(__CLASS__);
		self::$loggedOut = new Event(__CLASS__);
	}
	
	// =========================================================================== 

	/**
	 * Performs a call of this page
	 */
	public function execute() {
		$template = new Template('Premanager', 'loginForm');
		//TODO: get the urls for password lost and register
		//$template->set('passwordLostURL', Environment::getCurrent()->project->)
		//$template->set('registerURL', Environment::getCurrent()->project->)
		$template->set('canRegister',
			Environment::getCurrent()->me->hasRight('Premanager', 'register') ||
			Environment::getCurrent()->me->hasRight('Premanager',
				'registerWithoutEmail'));
		$page = new Page($this);
			
		if (Request::getPOST('Premanager_LoginPage_login')) {
			switch (self::login(&$user)) {
				case LoginFailedReason::USER:
				case LoginFailedReason::PASSWORD:
					$template2 = new Template('Premanager', 'loginFailedMessage');
					//TODO: set the password lost url
					//$template->set('passwordLostURL', '???');
					$text = $template2->get();
					break;
				case LoginFailedReason::STATUS:
					switch ($user->status) {
						case UserStatus::DISABLED:
							$text = Translation::defaultGet('Premanager',
								'loginFailedAccountDisabledMessage');
							break;
						case UserStatus::WAIT_FOR_EMAIL:
							$text = Translation::defaultGet('Premanager',
								'loginFailedWaitForEmailMessage');
							break;
					}
					break;
			}
			
			$template->set('hidePasswordLostHint', true);
			$page->createMainBlock($text);
			$page->appendBlock(PageBlock::createSimple(Translation::defaultGet(
				'Premanager', 'loginFailedRetryLogin'), $template->get()));
		} else if (Request::getPOST('Premanager_LoginPage_logout')) {
			self::logout();
		} else {
			$page->createMainBlock($template->get());
		}
		Output::select($page);
	}
	
	// =========================================================================== 
	
	/**
	 * Tries to login using POST data
	 * 
	 * If login is successful, redirects and exits the script. If login fails,
	 * exits method commonly
	 * 
	 * @param Premanager\Models\User $user
	 */
	private static function login(&$user = null) {  
 		// If there is already a session started, remove this session later
		$oldCookie = DataBase::escape(Request::getCookie('session'));

		$userName = Request::getPOST('Premanager_LoginPage_user');
		$password = Request::getPOST('Premanager_LoginPage_password');
		$hidden = (bool) Request::getPOST('Premanager_LoginPage_hidden');
		$user = User::getByName($userName);
		if ($user) {
			if ($user->status == UserStatus::ENABLED) {
				if ($user->checkPassword($password, &$isSecondaryPassword)) {
					// If there is a session of this user, delete it		
					$session = Session::getByUser($user);
					if ($session)
						$session->delete();
					
					// Delete old session
					$session = Session::getByKey($key);
					if ($session)
						$session->delete();
						
					// Create new session
					$session = Session::createNew($user, $hidden, $isSecondaryPassword);
					
					// Set session cookie
					Output::setCookie('session', $session->key);
					
					self::$loginSuccessful->call($this, array('user' => $user));
					
					// Redirect to drop POST data
					Output::redirect();
				} else {
					self::$loginFailed->call($this,
						array('reason' => LoginFailedReason::PASSWORD, 'user' => $user));
					return LoginFailedReason::PASSWORD;
				}
			} else {
				self::$loginFailed->call($this,
					array('reason' => LoginFailedReason::STATUS, 'user' => $user));
				return LoginFailedReason::STATUS;
			}
		} else {
			self::$loginFailed->call($this,
				array('reason' => LoginFailedReason::USER, 'user' => null));
			return LoginFailedReason::USER;
		}
	}

	/**
	 * Deletes the session specified by cookie and removes the cookie. Afterwards,
	 * redirects to the current page to drop POST data
	 */
	private static function logout() {
		$key = Request::getCookie('session');
		$session = Session::getByKey($key);
		if ($session)
			$session->delete();
		Output::deleteCookie('session');
		self::$loggedOut->call();
		Output::redirect();
	}  
}

LoginPage::__init();

?>
