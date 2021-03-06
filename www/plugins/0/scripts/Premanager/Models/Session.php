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
	private $_hidden;
	private $_isFirstRequest;
	private $_project;
	private $_projectID;
	private $_confirmationExpirationTime;
	private $_isConfirmed;
	
	private static $_instances = array();
	private static $_count;
	private static $_descriptor;
	private static $_queryList;

	// ===========================================================================  
	
	protected function __construct() {
		parent::__construct();	
	}
	
	public static function __init() { 
		// Remove outdated sessions
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'Sessions')." ".
			"WHERE lastRequestTime < DATE_SUB(NOW(), INTERVAL ".
				Options::defaultGet('Premanager', 'session.length')." SECOND)");
	}
	
	private static function createFromID($id, $userID = null, $key = null, 
		$startTime = null, $lastRequestTime = null, $ip = null, $userAgent = null,
		$hidden = null, $isFirstRequest = null, $projectID = null,
		$isConfirmed = null) {
		
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
			if ($instance->_hidden === null)
				$instance->_hidden = $hidden;
			if ($instance->_isFirstRequest === null)
				$instance->_isFirstRequest = $isFirstRequest;
			if ($instance->_projectID === null)
				$instance->_projectID = $projectID;
			if ($instance->_isConfirmed === null)
				$instance->_isConfirmed = $isConfirmed;
				
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
		$instance->_hidden = $hidden;
		$instance->_isFirstRequest = $isFirstRequest;
		$instance->_projectID = $projectID;
		$instance->_isConfirmed = $isConfirmed;
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
		$id = (int)$id;
			
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
	 * @return Premanager\Models\Session
	 */
	public static function createNew(User $user, $hidden = false) {
		if (!$user)
			throw new ArgumentNullException('user');
		if ($user->getid() == 0)
			throw new ArgumentException('Cannot create a session for the guest',
				'user');
			
		$hidden = !!$hidden;
	
		$key = self::formKey($user);
		$ip = Request::getIP();
		$userAgent = Request::getUserAgent();
		$projectID = Environment::getCurrent()->getProject()->getID();
		$_hidden = $hidden ? '1' : '0';
		DataBase::query(
			"INSERT INTO ".DataBase::formTableName('Premanager', 'Sessions')." ".
			"(userID, startTime, lastRequestTime, `key`, ip, userAgent, ".
				"hidden, projectID, isFirstRequest) ".
			"VALUES ('".$user->getID()."', NOW(), NOW(), ".
				"'".DataBase::escape($key)."', '".DataBase::escape($ip)."', ".
				"'".DataBase::escape($userAgent)."', '$_hidden', '$projectID', '1')");
		$id = DataBase::getInsertID();
		
		$instance = self::createFromID($id, $user->getid(), $key, new DateTime(), 
			new DateTime(), $ip, $userAgent, $hidden, true,
			$projectID);
		$instance->_confirmationExpirationTime = null;
		$instance->_isConfirmed = false;

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
				'hidden' => array(DataType::BOOLEAN, 'getHidden', 'hidden'),
				'isFirstRequest' => array(DataType::BOOLEAN, 'getIsFirstRequest',
					'isFirstRequest'),
				'project' => array(Project::getDescriptor(), 'getProject', 'project'),
				'isConfirmed' => array(Project::getDescriptor(), 'isConfirmed',
					'!confirmationExpirationTime! > NOW()'),
				'confirmationExpirationTime' => array(Project::getDescriptor(),
					'getConfirmationExpirationTime', 'confirmationExpirationTime')),
				'Premanager', 'Sessions', array(__CLASS__, 'getByID'), false);
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
	 * Indicates whether user has re-entered their password to enable rights of
	 *   groups that require login confirmation
	 * 
	 * @return bool
	 */
	public function isConfirmed() {
		$this->checkDisposed();
			
		if ($this->_isConfirmed === null)
			$this->load();
		return $this->_isConfirmed;
	}  

	/**
	 * The date/time when this session is no longer confirmed
	 * 
	 * @return Premanager\DateTime or null, if this session is not confirmed
	 */
	public function getConfirmationExpirationTime() {
		$this->checkDisposed();
			
		if ($this->_confirmationExpirationTime === false)
			$this->load();
		return $this->_confirmationExpirationTime;
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
		
		$project = Environment::getCurrent()->getProject();
		
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Sessions')." ".
			"SET lastRequestTime = NOW(), ".
				"isFirstRequest = '0', ".
				"projectID = '".$project->getID()."' ".
			"WHERE id = '$this->_id'");
		
		$this->_lastRequestTime = new DateTime();
		$this->_isFirstRequest = false;
		$this->_project = $project;
		$this->_projectID = $project->getid();
	}
	
	public function confirm()  {
		$this->checkDisposed();
		
		$length = Options::defaultGet('Premanager', 'login-confirmation.length');
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Sessions')." ".
			"SET confirmationExpirationTime = ".
				"DATE_ADD(NOW(), INTERVAL $length SECOND) ".
			"WHERE id = $this->_id");
		$this->_confirmationExpirationTime =
			DateTime::getNow()->addSeconds($length);
		$this->_isConfirmed = true;
	}
	
	public function unconfirm()  {
		$this->checkDisposed();
		
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Sessions')." ".
			"SET confirmationExpirationTime = '0000-00-00 00:00:00' ".
			"WHERE id = $this->_id");
		$this->_confirmationExpirationTime = null;
		$this->_isConfirmed = false;
	}

	// ===========================================================================
	
	private function load() {
		$result = DataBase::query(
			"SELECT session.userID, session.key, session.startTime, ".
				"session.lastRequestTime, session.ip, session.userAgent, ".
				"session.hidden, session.isFirstRequest, session.projectID, ".
				"session.confirmationExpirationTime ".
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
		$this->_hidden = $result->get('hidden');
		$this->_isFirstRequest = $result->get('isFirstRequest');
		$this->_projectID = $result->get('projectID');
		if ($result->get('confirmationExpirationTime') != '0000-00-00 00:00:00') {
			$this->_confirmationExpirationTime = new DateTime(
				$result->get('confirmationExpirationTime'));
			$this->_isConfirmed =
				$this->_confirmationExpirationTime->compareTo(DateTime::getNow()) > 0;
		} else {
			$this->_confirmationExpirationTime = null;
			$this->_isConfirmed = false;
		} 
		
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