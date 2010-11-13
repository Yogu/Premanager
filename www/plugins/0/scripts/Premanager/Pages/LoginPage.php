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
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		// helpers
		$canRegister = 
			Environment::getCurrent()->getuser()->hasRight('Premanager', 'register') ||
			Environment::getCurrent()->getuser()->hasRight('Premanager', 
				'registerWithoutEmail');
		$referer = Request::isRefererInternal() ? Request::getReferer() : '';
		
		$template = new Template('Premanager', 'loginForm');
		$template->set('canRegister', $canRegister);
		$template->set('referer', $referer);
		$page = new Page($this);
		$page->title = Translation::defaultGet('Premanager', 'theLogin');;
			
		if (Request::getPOST('login')) {
			switch (self::login(&$user)) {
				case LoginFailedReason::USER:
				case LoginFailedReason::PASSWORD:
					// Show the reason message and a login form
					$template2 = new Template('Premanager', 'loginFailedMessage');
					$page->title =
						Translation::defaultGet('Premanager', 'loginFailedTitle');
					//TODO: set the password lost url
					//$template->set('passwordLostURL', '???');
					$text = $template2->get();
					break;
				case LoginFailedReason::STATUS:
					// Show the reason message and a login form
					switch ($user->getstatus()) {
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
					
				case LoginFailedReason::SUCCESSFUL:
					// Show a link to the referer
					$template = new Template('Premanager', 'loginSuccessful');
					$template->set('referer', Request::getPOST('referer'));
					$template->set('environment', Environment::getCurrent());
					$page->createMainBlock($template->get());
					return $page;
			}
			
			$template->set('hidePasswordLostHint', true);
			$page->createMainBlock($text);
			$page->appendBlock(PageBlock::createSimple(Translation::defaultGet(
				'Premanager', 'loginFailedRetryLogin'), $template->get()));
		} else if (Request::getPOST('logout')) {
			// Logout and show a link to the referer
			self::logout();
			$template = new Template('Premanager', 'logoutSuccessful');
			$template->set('referer', Request::getPOST('referer'));
			$page->title = Translation::defaultGet('Premanager', 'theLogout');
			$page->createMainBlock($template->get());
		} else  if (Environment::getCurrent()->getSession()) {
			// Show a logout button
			$template = new Template('Premanager', 'logout');
			$template->set('environment', Environment::getCurrent());
			$template->set('referer', $referer);
			$page->title = Translation::defaultGet('Premanager', 'theLogout');
			$page->createMainBlock($template->get());
		} else {
			// Show the login form
			$page->createMainBlock($template->get());
		}
		return $page;
	}
	
	// =========================================================================== 
	
	/**
	 * Tries to login using POST data
	 * 
	 * @param Premanager\Models\User $user
	 * @return int (enum Premanager\Pages\LoginFailedReason)
	 */
	private static function login(&$user = null) {  
 		// If there is already a session started, remove this session later
		$oldSession =
			Session::getByKey(DataBase::escape(Request::getCookie('session')));

		$userName = Request::getPOST('user');
		$password = Request::getPOST('password');
		$hidden = (bool) Request::getPOST('hidden');
		$user = User::getByName($userName);
		if ($user) {
			if ($user->getstatus() == UserStatus::ENABLED) {
				if ($user->checkPassword($password, &$isSecondaryPassword)) {
					// If there is a session of this user, delete it		
					$session = Session::getByUser($user);
					if ($session)
						$session->delete();
					
					// Delete old session
					if ($oldSession)
						$oldSession->delete();
						
					// Create new session
					$session = Session::createNew($user, $hidden, $isSecondaryPassword);
					
					$user->updateLoginTime($hidden);
					
					// Set session cookie
					Output::setCookie('session', $session->getkey());
					
					self::$loginSuccessful->call($this, array('user' => $user));
					
					return LoginFailedReason::SUCCESSFUL;
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
	 * Deletes the session specified by cookie and removes the cookie
	 */
	private static function logout() {
		$key = Request::getCookie('session');
		$session = Session::getByKey($key);
		if ($session)
			$session->delete();
		Output::deleteCookie('session');
		self::$loggedOut->call();
	}  
}

LoginPage::__init();

?>
