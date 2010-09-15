<?php 
namespace Premanager\Execution;

use Premanager\URL;

use Premanager\Module;
use Premanager\Event;
use Premanager\IO\Config;
use Premanager\IO\Request;
use Premanager\Models\User;
use Premanager\Models\Project;
use Premanager\Models\Language;
use Premanager\Models\ThemeClass;
use Premanager\Models\Session;
use Premanager\Execution\Theme;

/**
 * A collection of environment properties
 */
class Environment extends Module {
	/**
	 * @var Premanager\Models\User
	 */
	private $_me;
	/**
	 * @var Premanager\Models\Project
	 */
	private $_project;
	/**
	 * @var Premanager\Models\Language
	 */
	private $_language;
	/**
	 * @var Premanager\Execution\Theme
	 */
	private $_theme;
	/**
	 * @var int
	 */
	private $_edition;
	/**
	 * @var Premanager\Models\Session
	 */
	private $_session;
	/**
	 * @var bool
	 */
	private $_isDefault;
	/**
	 * @var bool
	 */
	private $_isReal;
	/**
	 * @var Premanager\Execution\Options
	 */
	private $_options;
	/**
	 * @var Premanager\Execution\Translation
	 */
	private $_translation;
	/**
	 * @var string
	 */
	private $_urlPrefix;
	/**
	 * @var bool
	 */
	private $_meLoading;
	/**
	 * @var bool
	 */
	private $_projectLoading;
	/**
	 * @var bool
	 */
	private $_languageLoading;
	/**
	 * @var bool
	 */
	private $_themeLoading;
	/**
	 * @var bool
	 */
	private $_editionLoading;
	
	/**
	 * @var array
	 */
	private static $_stack = array();
	
	// ===========================================================================  
	
	/**
	 * Specifies that a user could not log in because the user is disabled
	 */
	const LOGIN_FAILED_REASON_STATUS = 0x00;
	
	/**
	 * Specifies that the user name is invalid
	 */
	const LOGIN_FAILED_REASON_USER = 0x01;
	
	/**
	 * Specifies that the password is wrong
	 */
	const LOGIN_FAILED_REASON_PASSWORD = 0x02;
	
	// =========================================================================== 

	/**
	 * The logged-in user
	 *
	 * Ths property is read-only.
	 * 
	 * This property contains Premanager\Models\User::getGuest() if it is accessed
	 * while the actual value for $me is loading. Use isMeAvailable() to check
	 * whether this is the case.
	 * 
	 * @var Premanager\Models\User
	 */
	public $me = Module::PROPERTY_GET;

	/**
	 * The current project
	 *
	 * Ths property is read-only.
	 * 
	 * This property contains Premanager\Models\Project::getOrganization() if it
	 * is accessed while the actual value for $project is loading. Use
	 * isProjectAvailable() to check whether this is the case.
	 * 
	 * @var Premanager\Models\Project
	 */
	public $project = Module::PROPERTY_GET;

	/**
	 * The current language
	 *
	 * Ths property is read-only.
	 * 
	 * This property contains Premanager\Models\Language::getDefault() if it is
	 * accessed while the actual value for $language is loading. Use
	 * isLanguageAvailable() to check whether this is the case.
	 * 
	 * @var Premanager\Models\Language
	 */
	public $language = Module::PROPERTY_GET;

	/**
	 * The current theme
	 *
	 * Ths property is read-only.
	 * 
	 * This property contains Premanager\Models\Theme::getDefault()->instance if
	 * it is accessed while the actual value for $theme is loading. Use
	 * isThemeAvailable() to check whether this is the case.
	 * 
	 * @var Premanager\Execution\Theme
	 */
	public $theme = Module::PROPERTY_GET;

	/**
	 * The current edition(enum Premanager\Execution\Edition)
	 *
	 * Ths property is read-only.
	 * 
	 * This property contains Edition::COMMON if it is accessed while the actual
	 * value for $edition is loading. Use isEditionAvailable() to check whether
	 * this is the case.
	 * 
	 * @var int
	 */
	public $edition = Module::PROPERTY_GET;
	
	/**
	 * The set of options
	 * 
	 * @var Premanager\Execution\Options
	 */
	public $options = Module::PROPERTY_GET;
	
	/**
	 * The set of translation strings
	 * 
	 * @var Premanager\Execution\Translation
	 */
	public $translation = Module::PROPERTY_GET;
	
	/**
	 * The prefix for relative urls
	 * 
	 * The url prefix contains protocol, host and may contain a path. Environment
	 * properties are inserted. Example: http://en.example.com/my-project.
	 * 
	 * @var string
	 */
	public $urlPrefix = Module::PROPERTY_GET_ACRONYM;
	
	// =========================================================================== 
	
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
	 *   int $reason a Premanager\Execution\Environment::LOGIN_FAILED_REASON_*
	 *     value
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
	
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Creates a new environment and initializes its properties
	 * 
	 * @param Premanager\Models\User $me the logged-in user, or
	 *   Premanager\Models\User::getGuest()
	 * @param Premanager\Models\Project $project the current project, or
	 *   Premanager\Models\Project::getOrganization()
	 * @param Premanager\Models\Language $language the current language
	 * @param Premanager\Execution\Theme $theme the current theme
	 * @param int $edition enum Premanager\Execution\Edition
	 */
	public function create(User $me, Project $project, Language $language,
		Theme $theme, $edition) {
		$instance = new self();
		switch ($edition) {
			case Edition::COMMON:
			case Editoin::MOBILE:
			case Edition::PRINTABLE:
				break;
			default:
				throw new InvalidEnumArgumentException('edition', $edition,
					'Premanager\Execution\Edition');
		}

		$instance->_me = $me;
		$instance->_project = $project;
		$instance->_language = $language;
		$instance->_theme = $theme;
		$instance->_edition = $edition;		
	}
	
	// ===========================================================================  
	
	/**
	 * The static constructor, don't call manually
	 */
	public static function __init() {
		// Insert a placeholder for the real environment which is replaced the first
		// time the real environment is needed by getCurrent()
		self::$_stack[] = null;
		
		self::$loginSuccessful = new Event(__CLASS__);
		self::$loginFailed = new Event(__CLASS__);
		self::$loggedOut = new Event(__CLASS__);
	}
	
	/**
	 * Stores the current environment and activates the specified
	 * @param Premanager\Execution\Environment $environment
	 * @see pop()
	 */
	public static function push(Environment $environment) {
		self::$_stack[] = $environment;
	}
	
	/**
	 * Removes the last pushed evironment off the stack and activates the 
	 * environment that has been pushed before, or, if there is nothing like this,
	 * restores the real environment
	 */
	public static function pop() {
		if (count($this->_stack[]) <= 1)
			throw new InvalidOperationException('There is nothing to pop');
		array_pop(self::$_stack);
	}
	
	/**
	 * Gets the current environment
	 * @return Premanager\Execution\Environment the current environment
	 */
	public static function getCurrent() {
		$item = self::$_stack[count(self::$_stack)-1];
		
		// this is the real environment and not yet set
		if (!$item) {
			$item = self::getReal();
			self::$_stack[0] = $item;
		}
		
		return $item;
	}
	
	// ===========================================================================

	/**
	 * Gets the logged-in user
	 * 
	 * This method returns Premanager\Models\User::getGuest() if it is called
	 * while the actual value for $me is loading. Use isMeAvailable() to check
	 * whether this is the case.  
	 * 
	 * @return Premanager\Models\User
	 */
	public function getMe() {
		if (!$this->_me && $this->_isReal) {
			if ($this->_meLoading)
				return User::getGuest();
			else {
				$this->_meLoading = true;
				try {
					$this->_me = self::getLoggedInUser();
				} catch (\Exception $e) {
					$this->_meLoading = false;
					throw $e;
				}
				$this->_meLoading = false;
			}
		}
		
		return $this->_me;
	}
	
	/**
	 * Gets the current project
	 * 
	 * This method returns Premanager\Models\Project::getOrganization() if it is
	 * called while the actual value for $project is currently is loading Use
	 * isProjectAvailable() to check whether this is the case.
	 * 
	 * @return Premanager\Models\Project
	 */
	public function getProject() {
		if (!$this->_project && $this->_isReal) {
			if ($this->_projectLoading)
				return Project::getOrganization();
			else {
				$this->_projectLoading = true;
				try {
					$this->_project = Request::getProject();
				} catch (\Exception $e) {
					$this->_projectLoading = false;
					throw $e;
				}
				$this->_projectLoading = false;
			}
		}
		
		return $this->_project;
	}
	
	/**
	 * Gets the current language
	 * 
	 * This method returns Premanager\Models\Language::getDefault() if it is
	 * called while the actual value for $language is loading. Use
	 * isLanguageAvailable() to check whether this is the case.
	 * 
	 * @return Premanager\Models\Language
	 */
	public function getLanguage() {
		if (!$this->_language && $this->_isReal) {
			if ($this->_languageLoading)
				return Language::getDefault();
			else {
				$this->_languageLoading = true;
				try {
					$this->_language = Request::getLanguage();
				} catch (\Exception $e) {
					$this->_languageLoading = false;
					throw $e;
				}
				$this->_languageLoading = false;
			}
		}
		
		return $this->_language;
	}
	
	/**
	 * Gets the current theme
	 * 
	 * This method returns Premanager\Models\Theme::getDefault()->instance if it
	 * is called while the actual value for $theme is loading. Use
	 * isThemeAvailable() to check whether this is the case.
	 * 
	 * @return Premanager\Execution\Theme
	 */
	public function getTheme() {
		if (!$this->_me && $this->_isReal) {
			if ($this->_themeLoading)
				return ThemeClass::getDefault()->instance;
			else {
				$this->_themeLoading = true;
				try {
					// TODO: This value is only a placeholder; replace it by the real value
					$this->_theme = ThemeClass::getDefault()->instance;
				} catch (\Exception $e) {
					$this->_themeLoading = false;
					throw $e;
				}
				$this->_themeLoading = false;
			}
		}
		
		return $this->_theme;
	}
	
	/**
	 * Gets the current edition (enum Premanager\Execution\Edition)
	 * 
	 * This method returns Premanager\Execution\edition::COMMON if it is called
	 * while the actual value for $edition is loading. Use isEditionAvailable() to
	 * check whether this is the case.
	 * 
	 * @return int
	 */
	public function getEdition() {
		if (!$this->_me && $this->_isReal) {
			if ($this->_editionLoading)
				return Edition::COMMON;
			else {
				$this->_editionLoading = true;
				try {
					$this->_edition = Request::getEdition();
				} catch (\Exception $e) {
					$this->_editionLoading = false;
					throw $e;
				}
				$this->_editionLoading = false;
			}
		}
		
		return $this->_edition;
	}
	
	/**
	 * Gets the set of options
	 * 
	 * @return Premanager\Execution\Options
	 */
	public function getOptions() {
		if (!$this->_options)
			$this->_options = new Options($this);
			
		return $this->_options;
	}
	
	/**
	 * Gets the set of translation strings
	 * 
	 * @return Premanager\Execution\Translation
	 */
	public function getTranslation() {
		if (!$this->_translation)
			$this->_translation = new Translation($this);
			
		return $this->_translation;
	}
	
	/**
	 * Gets the prefix for relative urls
	 * 
	 * The url prefix contains protocol, host and may contain a path. Environment
	 * properties are inserted. Example: http://en.example.com/my-project.
	 * 
	 * @return string
	 */
	public function getURLPrefix() {
		if ($this->_urlPrefix === null)
			$this->_urlPrefix =
				URL::fromTemplate($this->language, $this->edition, $this->project);
		return $this->_urlPrefix;
	}
	
	/**
	 * Checks if the $me property contains the correct value  
	 *  
	 * @return bool true, if $me is available
	 */
	public function isMeAvailable() {
		return !$this->_meLoading;
	}
	
	/**
	 * Checks if the $project property contains the correct value
	 *  
	 * @return bool true, if $project is available
	 */
	public function isProjectAvailable() {
		return !$this->_projectLoading;
	}
	
	/**
	 * Checks if the $language property contains the correct value
	 * 
	 * @return bool true, if $language is available
	 */
	public function isLanguageAvailable() {
		return !$this->_languageLoading;
	}
	
	/**
	 * Checks if the $theme property contains the correct value
	 * 
	 * @return bool true, if $language is available
	 */
	public function isThemeAvailable() {
		return !$this->_themeLoading;
	}
	
	/**
	 * Checks if the $edition property contains the correct value
	 * 
	 * @return bool true, if $edition is available
	 */
	public function isEditionAvailable() {
		return !$this->_editionLoading;
	}

	// ===========================================================================
	
	/**
	 * Gets the real environment
	 */
	private static function getReal() {
		$environment = new self();
		$environment->_isReal = true;
		return $environment;
	}

	/**
	 * Gets the logged-in user
	 * 
	 * This method checks for POST login / logout data and for cookies or returns
	 * a bot or the guest
	 * 
	 * @return Premanager\Models\User
	 */
	private static function getLoggedInUser() {
		/*
		//TODO: Move this into a Login Page Node
		// If user clicked login or logout button, do this
  	if (!Config::isLoginDisabled()) {
  		if (Request::getPOST('Premanager_Me_login')) {
	   		$this->login();
	  	} else if (Request::getPOST('Premanager_Me_logout'))
	   		$this->logout();
  	}
  	
  	// If we did not log in or out or the login has failed, this code is reached
  	*/

		$key = Request::getCookie('session');
		if ($key)
			$session = Session::getByKey($key);
		if ($session) {
			$session->hit();
			return $session->user;
		} else
			return User::getGuest();
	}    
	
	/*
	 * Tries to login using POST data
	 * 
	 * If login is successful, redirects and exits the script. If login fails,
	 * exits method commonly
	 */
	/*
	private static function login() {  
 		// If there is already a session started, remove this session later
		$oldCookie = DataBase::escape(Request::getCookie('session'));

		$userName = Request::gestPOST('Premanager_Me_user');
		$password = Request::gestPOST('Premanager_Me_password');
		$hidden = Request::gestPOST('Premanager_Me_hidden');
		$user = User::getByName($userName);
		if ($user) {
			if ($user->status == User::STATUS_ENABLED) {
				if ($user && $user->checkPassword($password, &$isSecondaryPassword)) {
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
					Output::setCookkie('session', $session->key);
					
					self::$loginSuccessful($this, array('user' => $user));
					
					// Redirect to drop POST data
					Output::redirect();
				} else {
					self::$loginFailed->call($this,
						array('reason' => self::LOGIN_FAILED_REASON_PASSWORD,
							'user' => $user));
				}
			} else {
				self::$loginFailed->call($this,
					array('reason' => self::LOGIN_FAILED_REASON_STATUS, 'user' => $user));
			}
		} else {
			self::$loginFailed->call($this,
				array('reason' => self::LOGIN_FAILED_REASON_USER, 'user' => null));
		}
	}
	*/

	/*
	 * Deletes the session specified by cookie and removes the cookie. Afterwards,
	 * redirects to the current page to drop POST data
	 */
	/*private static function logout() {
		$key = Request::getCookie('session');
		$session = Session::getByKey($key);
		if ($session)
			$session->delete();
		Output::deleteCookie('session');
		self::$loggedOut->call();
		Output::redirect();
	}*/

	/**
	 * Tries to log in using the sessions cookie
	 * 
	 * @return Premanager\Models\User the logged in user or null, if login failed
	 */
	private static function loginByCookie() {
		$key = Request::getCookie('session');
		$session = Session::getByKey($key);
		if ($session) {
			$session->hit();
			return $session->user;
		}
	}    
}

Environment::__init();

?>