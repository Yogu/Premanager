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
use Premanager\Models\StyleClass;
use Premanager\Models\Session;
use Premanager\Execution\Style;

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
	 * @var Premanager\Execution\Style
	 */
	private $_style;
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
	private $_sessionLoading;
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
	private $_styleLoading;
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
	 * while the actual value for $me or $session is loading. Use
	 * isSessionAvailable() to check whether this is the case.
	 * 
	 * @var Premanager\Models\User
	 */
	public $me = Module::PROPERTY_GET;

	/**
	 * The current session
	 *
	 * Ths property is read-only.
	 * 
	 * This property contains null if it is accessed while the actual value for
	 * $session or $me is loading. Use isSessionAvailable() to check whether this
	 * is the case.
	 * 
	 * @var Premanager\Models\Session
	 */
	public $session = Module::PROPERTY_GET;

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
	 * The current style
	 *
	 * Ths property is read-only.
	 * 
	 * This property contains Premanager\Models\Style::getDefault()->instance if
	 * it is accessed while the actual value for $style is loading. Use
	 * isStyleAvailable() to check whether this is the case.
	 * 
	 * @var Premanager\Execution\Style
	 */
	public $style = Module::PROPERTY_GET;

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
	 * @param Premanager\Execution\Style $style the current style
	 * @param int $edition enum Premanager\Execution\Edition
	 */
	public function create(User $me, Project $project, Language $language,
		Style $style, $edition) {
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
		$instance->_style = $style;
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
	 * while the actual value for $me or $session is loading. Use
	 * isSessionAvailable() to check whether this is the case.  
	 * 
	 * @return Premanager\Models\User
	 */
	public function getMe() {
		return $this->session ? $this->session->user : User::getGuest();
	}

	/**
	 * Gets the current session
	 * 
	 * This method returns null if it is called while the actual value for
	 * $session is loading. Use isSessionAvailable() to check whether this is the
	 * case.  
	 * 
	 * @return Premanager\Models\Session
	 */
	public function getSession() {
		if (!$this->_session && $this->_isReal) {
			if ($this->_sessionLoading)
				return null;
			else {
				$this->_sessionLoading = true;
				try {
					if ($key = Request::getCookie('session'))
						$this->_session = Session::getByKey($key);
				} catch (\Exception $e) {
					$this->_sessionLoading = false;
					throw $e;
				}
				$this->_sessionLoading = false;
			}
		}
		
		return $this->_session;
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
	 * Gets the current style
	 * 
	 * This method returns Premanager\Models\Style::getDefault()->instance if it
	 * is called while the actual value for $style is loading. Use
	 * isStyleAvailable() to check whether this is the case.
	 * 
	 * @return Premanager\Execution\Style
	 */
	public function getStyle() {
		if (!$this->_me && $this->_isReal) {
			if ($this->_styleLoading)
				return StyleClass::getDefault()->instance;
			else {
				$this->_styleLoading = true;
				try {
					// TODO: This value is only a placeholder; replace it by the real value
					$this->_style = StyleClass::getDefault()->instance;
				} catch (\Exception $e) {
					$this->_styleLoading = false;
					throw $e;
				}
				$this->_styleLoading = false;
			}
		}
		
		return $this->_style;
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
	 * Checks if the $session and $me properties contain the correct values  
	 *  
	 * @return bool true, if $session and $me are available
	 */
	public function isSessionAvailable() {
		return !$this->_sessionLoading;
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
	 * Checks if the $style property contains the correct value
	 * 
	 * @return bool true, if $language is available
	 */
	public function isStyleAvailable() {
		return !$this->_styleLoading;
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

	/*
	 * The following two methods have to be moved into the new login class as soon
	 * as it exists
	 */ 
	
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
}

Environment::__init();

?>