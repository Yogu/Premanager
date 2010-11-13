<?php             
namespace Premanager\Models;

use Premanager\DateTime;

use Premanager\Execution\Environment;
use Premanager\IO\Request;
use Premanager\IO\Config;
use Premanager\Module;
use Premanager\Model;
use Premanager\ArgumentException;
use Premanager\ArgumentNullException;
use Premanager\InvalidOperationException;
use Premanager\Strings;
use Premanager\Types;
use Premanager\IO\CorrputDataException;
use Premanager\IO\DataBase\DataBase;
use Premanager\Execution\Options;
use Premanager\Debug\Debug;
use Premanager\Debug\AssertionFailedException;
use Premanager\QueryList\ModelDescriptor;
use Premanager\QueryList\QueryList;
use Premanager\QueryList\DataType;
              
/**
 * A session of a logged-in user
 */
final class Session extends Model {
	private $_id;
	private $_user;
	private $_userID;
	private $_key;
	private $_startTime;
	private $_lastRequestTime;
	private $_ip;
	private $_userAgent;
	private $_secondaryPasswordUsed;
	private $_hidden;
	private $_isFirstRequest;
	private $_project;
	private $_projectID;
	
	private static $_instances = array();
	private static $_count;
	private static $_descriptor;
	private static $_queryList;

	// ===========================================================================  

	/**
	 * The id of this session
	 *
	 * Ths property is read-only.
	 * 
	 * @var int
	 */
	public $id = Module::PROPERTY_GET;
   
	/**
	 * The user that has logged in with this session
	 *
	 * Ths property is read-only.
	 * 
	 * @var Premanager\Models\User
	 */
	public $user = Module::PROPERTY_GET;
	
	/**
	 * The key that identifies this session and that is used in the cookie
	 *
	 * Ths property is read-only.
	 * 
	 * @var string
	 */
	public $key = Module::PROPERTY_GET;    
	
	/**
	 * The date/time when this session was started
	 *
	 * Ths property is read-only.
	 * 
	 * @var Premanager\DateTime
	 */
	public $startTime = Module::PROPERTY_GET;      
	
	/**
	 * The date/time when the user of this session accessed the last time a page
	 *
	 * Ths property is read-only.
	 * 
	 * @var Premanager\DateTime
	 */
	public $lastRequestTime = Module::PROPERTY_GET;  
	
	/**
	 * The ip address the session has been started from
	 *
	 * Ths property is read-only.
	 * 
	 * @var string
	 */
	public $ip = Module::PROPERTY_GET; 
	
	/**
	 * The user agent of the browser with that the user has started the session
	 *
	 * Ths property is read-only.
	 * 
	 * @var string
	 */
	public $userAgent = Module::PROPERTY_GET;
	
	/**
	 * Specifies whether the user has logged in with a secondary password
	 *
	 * Ths property is read-only.
	 * 
	 * @var bool
	 */
	public $secondaryPasswordUsed = Module::PROPERTY_GET;
	
	/**
	 * Specifies whether ths session should be hidden from other visitors
	 *
	 * Ths property is read-only.
	 * 
	 * @var bool
	 */
	public $hidden = Module::PROPERTY_GET;
	
	/**
	 * Specifies whether this is the first request after the login request
	 * 
	 * After having logged in, the client is redirected to the same page (to drop
	 * POST data with the password). If you want to do something at the first
	 * request that produces a page (for example a login-successful hint), use
	 * this property.
	 *
	 * Ths property is read-only.
	 * 
	 * @var bool
	 */
	public $isFirstRequest = Module::PROPERTY_GET;
	
	/**
	 * The project this user is currently viewing
	 *
	 * Ths property is read-only.
	 * 
	 * @var Premanger\Objects\Project
	 */
	public $project = Module::PROPERTY_GET;

	// ===========================================================================  
	
	protected function __construct() {
		parent::__construct();	
	}
	
	public static function __init() { 
		// Remove outdated sessions
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'Sessions')." ".
			"WHERE lastRequestTime < DATE_SUB(NOW(), INTERVAL ".
				Options::defaultGet('Premanager', 'sessionLength')." SECOND)");
	}
	
	private static function createFromID($id, $userID = null, $key = null, 
		$startTime = null, $lastRequestTime = null, $ip = null, $userAgent = null,
		$secondaryPasswordUsed = null, $hidden = null, $isFirstRequest = null,
		$projectID = null) {
		
		if (array_key_exists($id, self::$_instances)) {
			$instance = self::$_instances[$id]; 
			if ($instance->_userID === null)
				$instance->_userID = $userID;
			if ($instance->_lastRequestTime === null)
				$instance->_lastRequestTime = $lastRequestTime;
			if ($instance->_startTime === null)
				$instance->_startTime = $startTime;
			if ($instance->_lastRequestTime === null)
				$instance->_lastRequestTime = $lastRequestTime;
			if ($instance->_ip === null)
				$instance->_ip = $ip;
			if ($instance->_userAgent === null)
				$instance->_userAgent = $userAgent;
			if ($instance->_secondaryPasswordUsed === null)
				$instance->_secondaryPasswordUsed = $secondaryPasswordUsed;
			if ($instance->_hidden === null)
				$instance->_hidden = $hidden;
			if ($instance->_isFirstRequest === null)
				$instance->_isFirstRequest = $isFirstRequest;
			if ($instance->_projectID === null)
				$instance->_projectID = $projectID;
				
			return $instance;
		}

		if (!Types::isInteger($id) || $id < 0)
			throw new ArgumentException('$id must be a nonnegative integer value',
				'id');
				
		$instance = new self();
		$instance->_id = $id;
		$instance->_userID = $userID;
		$instance->_key = $key;
		$instance->_startTime = $startTime;
		$instance->_lastRequestTime = $lastRequestTime;
		$instance->_ip = $ip;
		$instance->_userAgent = $userAgent;
		$instance->_secondaryPasswordUsed = $secondaryPasswordUsed;
		$instance->_hidden = $hidden;
		$instance->_isFirstRequest = $isFirstRequest;
		$instance->_projectID = $projectID;
		self::$_instances[$id] = $instance;
		return $instance;
	}

	// ===========================================================================
	
	/**
	 * Gets a session using its id
	 * 
	 * @param int $id the id of the session
	 * @return Premanager\Models\Session
	 */
	public static function getByID($id) {
		if (!Types::isInteger($id) || $id < 0)
			throw new ArgumentException(
				'$id must be a nonnegative integer value', 'id');
			
		if (\array_key_exists($id, self::$_instances)) {
			return self::$_instances[$id];
		} else {
			$instance = self::createFromID($id);
			// Check if id is correct
			if ($instance->load())
				return $instance;
			else
				return null;
		}
	}
	
	/**
	 * Gets a session using its user
	 * 
	 * @param Premanager\Models\User $user the user of the session
	 * @return Premanager\Models\Session
	 */
	public static function getByUser(User $user) {
		if (!$user)
			throw new ArgumentNullException('user');
		
		$result = DataBase::query(
			"SELECT session.id ".            
			"FROM ".DataBase::formTableName('Premanager', 'Sessions')." AS session ".
			"WHERE session.userID = '".$user->getID()."'");
		if ($result->next()) {
			return self::createFromID($result->get('id'));
		}
		return null;
	}
	
	/**
	 * Gets a session using its key
	 * 
	 * @param string $key key to search
	 * @return Premanager\Models\Session
	 */
	public static function getByKey($key) {
		$result = DataBase::query(
			"SELECT session.id ".            
			"FROM ".DataBase::formTableName('Premanager', 'Sessions')." AS session ".
			"WHERE session.key = '".DataBase::escape($key)."'");
		if ($result->next()) {
			return self::createFromID($result->get('id'));
		}
		return null;
	}
	
	/**
	 * Creates a new session and inserts it into data base
	 *
	 * @param Premanager\Models\User $user the user of this session
	 * @param bool $hidden true, if this session should be hidden from other
	 *   visitors             
	 * @param bool $secondaryPassowordUsed true, if a secondary password was used
	 *   for login
	 * @return Premanager\Models\Session
	 */
	public static function createNew(User $user, $hidden = false,
		$secondaryPassowordUsed = false) {
		if (!$user)
			throw new ArgumentNullException('user');
		if ($user->getid() == 0)
			throw new ArgumentException('Cannot create a session for the guest',
				'user');
			
		$hidden = !!$hidden;
		$secondaryPassowordUsed = !!$secondaryPassowordUsed;
	
		$key = self::formKey($user);
		$ip = Request::getIP();
		$userAgent = Request::getUserAgent();
		$projectID = Environment::getCurrent()->getproject()->id;
		$_secondaryPassowordUsed = $secondaryPassowordUsed ? '1' : '0';
		$_hidden = $hidden ? '1' : '0';
		DataBase::query(
			"INSERT INTO ".DataBase::formTableName('Premanager', 'Sessions')." ".
			"(userID, startTime, lastRequestTime, `key`, ip, userAgent, ".
				"secondaryPasswordUsed, hidden, projectID, isFirstRequest) ".
			"VALUES ('".$user->getID()."', NOW(), NOW(), ".
				"'".DataBase::escape($key)."', '".DataBase::escape($ip)."', ".
				"'".DataBase::escape($userAgent)."', '$_secondaryPassowordUsed', ".
				"'$_hidden', '$projectID', '1')");
		$id = DataBase::getInsertID();
		
		$instance = self::createFromID($id, $user->getid(), $key, new DateTime(), 
			new DateTime(), $ip, $userAgent, $secondaryPasswordUsed, $hidden, true,
			$projectID);

		if (self::$_count !== null)
			self::$_count++;	
		
		return $instance;
	}        
	    
	/**
	 * Gets the count of sessions
	 *
	 * @return int
	 */
	public static function getCount() {
		if (self::$_count === null) {
			$result = DataBase::query(
				"SELECT COUNT(session.sessionID) AS count ".
				"FROM ".DataBase::formTableName('Premanager', 'Sessions')." AS session");
			self::$_count = $result->get('count');
		}
		return self::$_count;
	}

	/**
	 * Gets a list of sessions
	 * 
	 * @return Premanager\QueryList\QueryList
	 */
	public static function getSessions() {
		if (!self::$_queryList)
			self::$_queryList = new QueryList(self::getDescriptor());
		return self::$_queryList;
	}          

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\QueryList\ModelDescriptor
	 */
	public static function getDescriptor() {
		if (self::$_descriptor === null) {
			self::$_descriptor = new ModelDescriptor(__CLASS__, array(
				'id' => array(DataType::NUMBER, 'getID', 'id'),
				'user' => array(User::getDescriptor(), 'getUser', 'user'),
				'key' => array(DataType::STRING, 'getKey', 'key'),
				'startTime' => array(DataType::DATE_TIME, 'getStartTime', 'startTime'),
				'lastRequestTime' => array(DataType::DATE_TIME, 'getLastRequestTime',
					'lastRequestTime'),
				'ip' => array(DataType::STRING, 'getip', 'ip'),
				'userAgent' => array(DataType::STRING, 'getUserAgent', 'userAgent'),
				'isSecondaryPasswordUsed' => array(DataType::BOOLEAN,
					'getIsSecondaryPasswordUsed', 'isSecondaryPasswordUsed'),
				'hidden' => array(DataType::BOOLEAN, 'getHidden', 'hidden'),
				'isFirstRequest' => array(DataType::BOOLEAN, 'getIsFirstRequest',
					'isFirstRequest'),
				'project' => array(Project::getDescriptor(), 'getProject', 'project')),
				'Premanager', 'Sessions', array(__CLASS__, 'getByID'));
		}
		return self::$_descriptor;
	}                                            

	// ===========================================================================
	
	/**
	 * Gets the id of this session
	 *
	 * @return int
	 */
	public function getID() {
		$this->checkDisposed();
		
		return $this->_id;
	}

	/**
	 * Gets the user that has logged in with this session
	 *
	 * @return Premanager\Models\User
	 */
	public function getUser() {
		$this->checkDisposed();
			
		if ($this->_user === null) {
			if ($this->_userID === null)
				$this->load();
			$this->_user = User::getByID($this->_userID);
		}
		return $this->_user;	
	}      

	/**
	 * Gets the key that identifies this session and is used in the cookie 
	 *
	 * @return string
	 */
	public function getKey() {
		$this->checkDisposed();
			
		if ($this->_key === null)
			$this->load();
		return $this->_key;	
	}        

	/**
	 * Gets the date/time when this session was started 
	 * 
	 * @return Premanager\DateTime
	 */
	public function getStartTime() {
		$this->checkDisposed();
			
		if ($this->_startTime === null)
			$this->load();
		return $this->_startTime;	
	}     

	/**
	 * Gets the date/time when the user has accessed a page the last time
	 * 
	 * @return Premanager\DateTime
	 */
	public function getLastRequestTime() {
		$this->checkDisposed();
			
		if ($this->_lastRequestTime === null)
			$this->load();
		return $this->_lastRequestTime;	
	}   

	/**
	 * Gets the ip address this session has been started from
	 * 
	 * @return int
	 */
	public function getIP() {
		$this->checkDisposed();
			
		if ($this->_ip === null)
			$this->load();
		return $this->_ip;	
	}  

	/**
	 * Gets the user agent of the browser this session has been started from
	 * 
	 * @return int
	 */
	public function getUserAgent() {
		$this->checkDisposed();
			
		if ($this->_userAgent === null)
			$this->load();
		return $this->_userAgent;
	}

	/**
	 * Indicates whether the user has logged in with a seconary password
	 * 
	 * @return bool
	 */
	public function getSecondaryPasswordUsed() {
		$this->checkDisposed();
			
		if ($this->_secondaryPasswordUsed === null)
			$this->load();
		return $this->_secondaryPasswordUsed;
	}

	/**
	 * Indicates whether this session should be hidden from other visitors
	 * 
	 * @return bool
	 */
	public function getHidden() {
		$this->checkDisposed();
			
		if ($this->_hidden === null)
			$this->load();
		return $this->_hidden;
	}

	/**
	 * Indicates whether this is the first request after the login request
	 * 
	 * @return bool
	 */
	public function getIsFirstRequest() {
		$this->checkDisposed();
			
		if ($this->_isFirstRequest === null)
			$this->load();
		return $this->_isFirstRequest;
	}

	/**
	 * Gets the last project this session accessed
	 * 
	 * @return Premanage\Objects\Project
	 */
	public function getProject() {
		$this->checkDisposed();
			
		if ($this->_project === null) {
			if ($this->_projectID === null)
				$this->load();
			$this->_project = Project::getById($this->_projectID);
		}
		return $this->_project;
	}     
	
	/**
	 * Deletes and disposes this session
	 */
	public function delete() {         
		$this->checkDisposed();
		
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'Sessions')." ".
			"WHERE id = '$this->_id'");

		if (self::$_count !== null)
			self::$_count--;
		unset(self::$_instances[$this->_id]);
			
		$this->dispose();
	}
	
	/**
	 * Has to be called at a request by this session
	 */
	public function hit() {
		$this->checkDisposed();
		
		$project = Environment::getCurrent()->getproject();
		
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Sessions')." ".
			"SET lastRequestTime = NOW(), ".
				"isFirstRequest = '0', ".
				"projectID = '".$project->getID()."'");	
		
		$this->_lastRequestTime = new DateTime();
		$this->_isFirstRequest = false;
		$this->_project = $project;
		$this->_projectID = $project->getid();
	}

	// ===========================================================================
	
	private function load() {
		$result = DataBase::query(
			"SELECT session.userID, session.key, session.startTime, ".
				"session.lastRequestTime, session.ip, session.userAgent, ".
				"session.secondaryPasswordUsed, session.hidden, ".
				"session.isFirstRequest, session.projectID ".
			"FROM ".DataBase::formTableName('Premanager', 'Sessions')." AS session ".
			"WHERE session.id = '$this->_id'");
		
		if (!$result->next())
			return false;
		
		$this->_userID = $result->get('userID');
		$this->_key = $result->get('key');
		$this->_startTime = new DateTime($result->get('startTime'));
		$this->_lastRequestTime = new DateTime($result->get('lastRequestTime'));
		$this->_ip = $result->get('ip');
		$this->_userAgent = $result->get('userAgent');
		$this->_secondaryPasswordUsed = $result->get('secondaryPasswordUsed');
		$this->_hidden = $result->get('hidden');
		$this->_isFirstRequest = $result->get('isFirstRequest');
		$this->_projectID = $result->get('projectID');
		
		return true;
	}

	// Returns the cookie value
	private static function formKey(User $user)  {
		if (!$user)
			throw new ArgumentNullException('user');
		if ($user->getid() == 0)
			throw new ArgumentException('$user is a guest', 'user'); 

		return hash('sha256',
			'ffa919513b2481678d3b3f54976d5f26f4785577a67fd1e5a2efa688c043c007'.
			Config::getSecurityCode().
			hash('sha256',
				'758e75425237cebe36a282b543a14567de6bb5ae80203c77d6d1bdaf19e675a7'.
				$user->getname().Config::getSecurityCode().$user->getid()).Request::getIP().
			time()); 
	}       
}

Session::__init();

?>