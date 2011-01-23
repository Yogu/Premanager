<?php 
namespace Premanager\Execution;

use Premanager\Models\Style;
use Premanager\ArgumentException;
use Premanager\Debug\Debug;
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

/**
 * A collection of environment properties
 */
class Environment extends Module {
	/**
	 * @var Premanager\Models\User
	 */
	private $_user;
	/**
	 * @var Premanager\Models\Project
	 */
	private $_project;
	/**
	 * @var Premanager\Execution\PageNode
	 */
	private $_pageNode;
	/**
	 * @var Premanager\Models\Language
	 */
	private $_language;
	/**
	 * @var Premanager\Models\Style
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
	private $_styleLoading;
	
	/**
	 * @var array
	 */
	private static $_stack = array();
	
	// =========================================================================== 
	
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Creates a new environment and initializes its properties
	 * 
	 * @param Premanager\Models\User $user the logged-in user, or
	 *   Premanager\Models\User::getGuest()
	 * @param Premanager\Models\session $session the session object, if the user
	 *   is logged in
	 * @param Premanager\Models\Project $pageNode the requested node
	 * @param Premanager\Models\Project $project the current project, or
	 *   Premanager\Models\Project::getOrganization() - MAY differ from the
	 *   project of $pageNode
	 * @param Premanager\Models\Language $language the current language
	 * @param Premanager\Models\Style $style the current style
	 * @param int $edition enum Premanager\Execution\Edition
	 */
	public static function create(User $user, $session,
		PageNode $pageNode, Project $project, Language $language, Style $style,
		$edition)
	{
		$instance = new self();
		switch ($edition) {
			case Edition::COMMON:
			case Edition::MOBILE:
			case Edition::PRINTABLE:
				break;
			default:
				throw new InvalidEnumArgumentException('edition', $edition,
					'Premanager\Execution\Edition');
		}
		
		if ($session !== null && !($session instanceof Session))
			throw new ArgumentException('$session must be either null or a '.
				'Premanager\Models\Session object', 'session');

		$instance->_user = $user;
		$instance->_session = $session;
		$instance->_pageNode = $pageNode;
		$instance->_project = $project;
		$instance->_language = $language;
		$instance->_style = $style;
		$instance->_edition = $edition;
		return $instance;
	}
	
	// ===========================================================================  
	
	/**
	 * The static constructor, don't call manually
	 */
	public static function __init() {
		// Insert a placeholder for the real environment which is replaced the first
		// time the real environment is needed by getCurrent()
		self::$_stack[] = null;
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
		if (count(self::$_stack) <= 1)
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
	 * while the actual value for $user or $session is loading. Use
	 * isSessionAvailable() to check whether this is the case.  
	 * 
	 * @return Premanager\Models\User
	 */
	public function getUser() {
		return
			$this->getSession() ? $this->getSession()->getUser() : User::getGuest();
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
	 * Gets the requested page node
	 * 
	 * This method returns StructurePageNode::getRootNode() if it is
	 * called while the actual value for $pageNode is currently is loading. Use
	 * isPageNodeAvailable() to check whether this is the case.
	 * 
	 * @return Premanager\Execution\PageNode
	 */
	public function getPageNode() {
		if (!$this->_pageNode && $this->_isReal) {
			if (Request::getPageNode() === null)
				return StructurePageNode::getRootNode();
			else
				$this->_pageNode = Request::getPageNode();
		}
		
		return $this->_pageNode;
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
			if (Request::getProject() === null)
				return Project::getOrganization();
			else
				$this->_project = Request::getProject();
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
			if (Request::getLanguage() === null)
				return Language::getDefault();
			else
				$this->_language = Request::getLanguage();
		}
		
		return $this->_language;
	}
	
	/**
	 * Gets the current style
	 * 
	 * This method returns the default stlye if it is called while the actual
	 * value for $style is loading. Use isStyleAvailable() to check whether this
	 * is the case.
	 * 
	 * @return Premanager\Models\Style
	 */
	public function getStyle() {
		if (!$this->_style && $this->_isReal) {
			if ($this->_styleLoading)
				return Style::getDefault();
			else {
				$this->_styleLoading = true;
				try {
					// TODO: This value is only a placeholder; replace it by the real value
					$this->_style = Style::getDefault();
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
		if ($this->_edition === null && $this->_isReal) {
			if (Request::getEdition() === null)
				return Edition::COMMON;
			else
				$this->_edition = Request::getEdition();
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
				URL::fromTemplate($this->getLanguage(), $this->getEdition(),
					$this->getProject());
		return $this->_urlPrefix;
	}
	
	/**
	 * Has to be called after logout
	 * 
	 * @return string
	 */
	public function notfifyLoggedOut() {
		$this->_session = null;
		$this->_sessionLoading = false;
	}
	
	/**
	 * Checks if the $session and $user properties contain the correct values  
	 *  
	 * @return bool true, if $session and $user are available
	 */
	public function isSessionAvailable() {
		return !$this->_sessionLoading;
	}
	
	/**
	 * Checks if the $pageNode property contains the correct value
	 *  
	 * @return bool true, if $pageNode is available
	 */
	public function isPageNodeAvailable() {
		return !Request::isAnalyzing();
	}
	
	/**
	 * Checks if the $project property contains the correct value
	 *  
	 * @return bool true, if $project is available
	 */
	public function isProjectAvailable() {
		return !Request::isAnalyzing();
	}
	
	/**
	 * Checks if the $language property contains the correct value
	 * 
	 * @return bool true, if $language is available
	 */
	public function isLanguageAvailable() {
		return !Request::isAnalyzing();
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
		return !Request::isAnalyzing();
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
}

Environment::__init();

?>
