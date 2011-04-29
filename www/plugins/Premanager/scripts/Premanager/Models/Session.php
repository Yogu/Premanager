<?php             
namespace Premanager\Models;

use Premanager\DateTime;

use Premanager\Execution\Environment;
use Premanager\IO\Request;
use Premanager\IO\Config;
use Premanager\Module;
use Premanager\Modeling\Model;
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
use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\QueryList;
use Premanager\Modeling\DataType;
              
/**
 * A session of a logged-in user
 */
final class Session extends Model {
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
	
	private static $_descriptor;

	// ===========================================================================
	
	public static function __init() {
		// Remove outdated sessions
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'Sessions')." ".
			"WHERE lastRequestTime < DATE_SUB(NOW(), INTERVAL ".
			Options::defaultGet('Premanager', 'session.length')." SECOND)");
	}	

	// ===========================================================================

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Models\SessionModel
	 */
	public static function getDescriptor() {
		return SessionModel::getInstance();
	}
	
	/**
	 * Gets a plugin using its id
	 * 
	 * @param int $id
	 * @return Premanager\Models\Session
	 */
	public static function getByID($id) {
		return self::getDescriptor()->getByID($id);
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
			return self::getByID($result->get('id'));
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
			return self::getByID($result->get('id'));
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
		return self::getDescriptor()->createNew($user, $hidden);
	}
	    
	/**
	 * Gets the count of sessions
	 *
	 * @return int
	 */
	public static function getCount() {
		$result = DataBase::query(
			"SELECT COUNT(session.sessionID) AS count ".
			"FROM ".DataBase::formTableName('Premanager', 'Sessions')." AS session");
		return $result->get('count');
	}

	/**
	 * Gets a list of sessions
	 * 
	 * @return Premanager\Modeling\QueryList
	 */
	public static function getSessions() {
		return self::getDescriptor()->getQueryList();
	}                                         

	// ===========================================================================

	/**
	 * Gets a boulde of information about the Session model
	 *
	 * @return Premanager\Models\SessionModel
	 */
	public function getModelDescriptor() {
		return SessionModel::getInstance();
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
	public function isFirstRequest() {
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
		parent::delete();
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

	/**
	 * Fills the fields from data base
	 * 
	 * @param array $fields an array($name => $sql) where $sql is a SQL statement
	 *   to store under the alias $name
	 * @return array ($name => $value) the values for the fields - or false if the
	 *   model does not exist in data base
	 */
	public function load(array $fields = array()) {
		$fields[] = 'userID';
		$fields[] = 'key';
		$fields[] = 'startTime';
		$fields[] = 'lastRequestTime';
		$fields[] = 'ip';
		$fields[] = 'userAgent';
		$fields[] = 'hidden';
		$fields[] = 'isFirstRequest';
		$fields[] = 'projectID';
		$fields[] = 'confirmationExpirationTime';
		
		if ($values = parent::load($fields)) {
			$this->_userID = $values['userID'];
			$this->_key = $values['key'];
			$this->_startTime = new DateTime($values['startTime']);
			$this->_lastRequestTime = new DateTime($values['lastRequestTime']);
			$this->_ip = $values['ip'];
			$this->_userAgent = $values['userAgent'];
			$this->_hidden = $values['hidden'];
			$this->_isFirstRequest = $values['isFirstRequest'];
			$this->_projectID = $values['projectID'];
			if ($values['confirmationExpirationTime'] != '0000-00-00 00:00:00') {
				$this->_confirmationExpirationTime = new DateTime(
					$values['confirmationExpirationTime']);
				$this->_isConfirmed =
					$this->_confirmationExpirationTime->compareTo(DateTime::getNow()) > 0;
			} else {
				$this->_confirmationExpirationTime = null;
				$this->_isConfirmed = false;
			} 
		}
		
		return $values;
	}
}

Session::__init();

?>