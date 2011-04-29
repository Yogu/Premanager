<?php
namespace Premanager\Models;

use Premanager\IO\Config;
use Premanager\IO\Request;
use Premanager\Execution\Environment;
use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\DataType;
use Premanager\IO\DataBase\QueryBuilder;
use Premanager\NotSupportedException;
use Premanager\IO\DataBase\DataBase;
use Premanager\Pages\AddGroupHomePage;
use Premanager\Models\User;
use Premanager\Modeling\ModelFlags;
use Premanager\InvalidOperationException;
use Premanager\ArgumentException;
use Premanager\Module;

/**
 * Provides a model descriptor for session models
 */
class SessionModel extends ModelDescriptor {
	private static $_instance;
	
	// ===========================================================================
	
	/**
	 * Loads the members calling addProperty()
	 */
	protected function loadMembers() {
		parent::loadMembers();
		
		$this->addProperty('user', User::getDescriptor(), 'getUser', 'user');
		$this->addProperty('key', DataType::STRING, 'getKey', 'key');
		$this->addProperty('startTime', DataType::DATE_TIME, 'getStartTime',
			'startTime');
		$this->addProperty('lastRequestTime', DataType::DATE_TIME,
			'getLastRequestTime', 'lastRequestTime');
		$this->addProperty('ip', DataType::STRING, 'getip', 'ip');
		$this->addProperty('userAgent', DataType::STRING, 'getUserAgent',
			'userAgent');
		$this->addProperty('hidden', DataType::BOOLEAN, 'getHidden', 'hidden');
		$this->addProperty('isFirstRequest', DataType::BOOLEAN, 'getIsFirstRequest',
			'isFirstRequest');
		$this->addProperty('project', Project::getDescriptor(), 'getProject',
			'project');
		$this->addProperty('isConfirmed', Project::getDescriptor(), 'isConfirmed',
			'!confirmationExpirationTime! > NOW()');
		$this->addProperty('confirmationExpirationTime', Project::getDescriptor(),
			'getConfirmationExpirationTime', 'confirmationExpirationTime');
	}
	
	// ===========================================================================
	
	/**
	 * Gets the single instance of Premanager\Models\SessionModel
	 * 
	 * @return Premanager\Models\SessionModel the single instance of this class
	 */
	public static function getInstance() {
		if (self::$_instance === null)
			self::$_instance = new self();
		return self::$_instance;
	}
	
	/**
	 * Gets the name of the class this descriptor describes
	 * 
	 * @return string
	 */
	public function getClassName() {
		return 'Premanager\Models\Session';
	}
	
	/**
	 * Gets the name of the plugin containing the models
	 * 
	 * @return string
	 */
	public function getPluginName() {
		return 'Premanager';
	}
	
	/**
	 * Gets the name of the model's table
	 * 
	 * @return string
	 */
	public function getTable() {
		return 'Sessions';
	}
	
	/**
	 * Gets flags set for this model descriptor 
	 * 
	 * @return int (enum set Premanager\Modeling\ModelFlags)
	 */
	public function getFlags() {
		return ModelFlags::NO_NAME;
	}
	
	/**
	 * Gets an SQL expression that determines the name group for an item (alias
	 * for item table is 'item')
	 * 
	 * @return string an SQL expression
	 */
	public function getNameGroupSQL() {
		return '';
	}

	/**
	 * Creates a new session and inserts it into data base
	 *
	 * @param Premanager\Models\User $user the user of this session
	 * @param bool $hidden true, if this session should be hidden from other
	 *   visitors
	 * @return Premanager\Models\Session
	 */
	public function createNew(User $user, $hidden = false) {
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
		
		return $this->createNewBase(
			array(
				'userID' => $user->getID(),
				'startTime!' => 'NOW()',
				'lastRequestTime!' => 'NOW()',
				'key' => $key,
				'ip' => $ip,
				'userAgent' => $userAgent,
				'hidden' => $hidden,
				'projectID' => $projectID,
				'isFirstRequest' => true
			),
			array()
		);
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

