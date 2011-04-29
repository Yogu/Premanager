<?php
namespace Premanager\Models;

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

class UserModel extends ModelDescriptor {
	private static $_instance;
	
	// ===========================================================================
	
	/**
	 * Loads the members calling addProperty()
	 */
	protected function loadMembers() {
		parent::loadMembers();
	
		$this->addProperty('title', DataType::STRING, 'getTitle', '*title');
		$this->addProperty('color', DataType::STRING, 'getColor', 'color');
		$this->addProperty('hasAvatar', DataType::BOOLEAN, 'hasAvatar', 'hasAvatar');
		$this->addProperty('avatarType', DataType::STRING, 'getAvatarType', 'avatarMIME');
		$this->addProperty('enabled', DataType::BOOLEAN, 'isEnabled',
			'!status! = "enabled"');
		$this->addProperty('hasPersonalSidebar', DataType::BOOLEAN,
			'getHasPersonalSidebar', 'hasPersonalSidebar');
		$this->addProperty('email', DataType::STRING, 'getEmail', 'email');
		$this->addProperty('style', Style::getDescriptor(), 'getStyle', 'styleID');
		$this->addProperty('unconfirmedEmail', DataType::STRING,
			'getUnconfirmedEmail', 'unconfirmedEmail');
		$this->addProperty('unconfirmedEmailStartTime', DataType::STRING,
			'getUnconfirmedEmailStartTime', 'unconfirmedEmailStartTime');
		$this->addProperty('unconfirmedEmailKey', DataType::STRING,
			'getUnconfirmedEmailKey', 'unconfirmedEmailKey');
		$this->addProperty('enableOnEmailConfirmation', DataType::BOOLEAN,
			'getEnableOnEmailConfirmation', '!status! = "waitForEmail"');
		$this->addProperty('registrationTime', DataType::DATE_TIME,
			'getRegistrationTime', 'registrationTime');
		$this->addProperty('registrationIP', DataType::STRING, 'getRegistrationIP',
			'registrationIP');
		$this->addProperty('lastLoginTime', DataType::DATE_TIME, 'getLastLoginTime',
			'lastLoginTime');
		$this->addProperty('lastLoginIP', DataType::STRING, 'getLastLoginIP',
			'lastLoginIP');
		$this->addProperty('lastVisibleLoginTime', DataType::DATE_TIME,
			'getLastVisibleLoginTime', 'lastVisibleLoginTime');
		$this->addProperty('lastLoginTime', DataType::DATE_TIME, 'getLastLoginTime',
			'lastLoginTime');
		$this->addProperty('lastVisibleLoginTime', DataType::DATE_TIME,
			'getLastVisibleLoginTime', 'lastVisibleLoginTime');
		$this->addProperty('resetPasswordKey', DataType::STRING,
			'getResetPasswordKey', 'resetPasswordKey');
		$this->addProperty('resetPasswordIP', DataType::STRING,
			'getResetPasswordIP', 'resetPasswordIP');
		$this->addProperty('resetPasswordStartTime', DataType::DATE_TIME,
			'getResetPasswordStartTime', 'resetPasswordStartTime');
		$this->addProperty('resetPasswordExpirationTime', DataType::DATE_TIME,
			'getResetPasswordExpirationTime');
	}
	
	// ===========================================================================
	
	/**
	 * Gets the single instance of Premanager\Models\UserModel
	 * 
	 * @return Premanager\Models\UserModel the single instance of this class
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
		return 'Premanager\Models\User';
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
		return 'Users';
	}
	
	/**
	 * Gets flags set for this model descriptor 
	 * 
	 * @return int (enum set Premanager\Modeling\ModelFlags)
	 */
	public function getFlags() {
		return ModelFlags::HAS_TRANSLATION | ModelFlags::UNTRANSLATED_NAME;
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
	 * Creates a new user and inserts it into data base
	 *
	 * @param string $name user name
	 * @param string $password unencoded password
	 * @param string $email the user's email address. Must not be used yet.
	 * @param bool $isEnabled true, if this account can be used to login
	 * @return Premanager\Models\User the new user
	 * @throws Premanage\NameConflictOperation the name is already assigned to a
	 *   user
	 */
	public static function createNew($name, $password, $email = '',
		$isEnabled = true)
		{
			$name = Strings::normalize($name);
		$password = trim($password);
		$email = trim($email);
		$status = $isEnabled ? 'enabled' : 'disabled';
		
		if (!$name)
			throw new ArgumentException('$name is an empty string or contains only '.
				'whitespaces', 'name');
		if (!self::isValidName($name))
			throw new ArgumentException('$name is not a valid user name');
		if (!self::isNameAvailable($name, $this))
			throw new NameConflictException('This name is already assigned to a user',
				$name);
		if (!$password)
			throw new ArgumentException('$password is an empty string or contains '.
				'only whitespaces', 'password');
		if ($email && !self::isEmailAvailable($email))
			throw new ArgumentException('There is already a user using this email '.
				'address');
			
		$user = $this->createNewBase(
			array(
				'registrationTime!' => "NOW()",
				'registrationIP' => Request::getIP(),
				'password' => self::encodePassword($password),
				'email' => $email,
				'status' => $status),
			array(
				'title' => ''),
			$name
		);
		
		$user->joinAutoJoinGroups();
		
		// Set color and title fields
		$user->clearCache();
		
		return $user;
	}    
                               
	/**
	 * Gets a user using its name
	 *
	 * Returns null if $name is not found
	 *
	 * @param string $name name of user
	 * @return Premanager\Models\User  
	 */
	public function getByName($name) {
		return $this->getByNameBase($name);
	}

	/**
	 * Checks if a name is not already assigned to a user
	 * 
	 * Note: this does NOT check whether the name is valid (see isValidName())
	 *
	 * @param $name name to check 
	 * @param Premanager\Models\User|null $ignoreThis a user which may have
	 *   the name; it is excluded
	 * @return bool true, if $name is available
	 */
	public function isNameAvailable($name, User $ignoreThis = null)
	{
		$model = $this->getByNameBase($name, '', $inUse);
		return !$model || !$inUse || $model === $ignoreThis;
	}
}

