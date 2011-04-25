<?php
namespace Premanager\Models;

use Premanager\Media\Image;
use Premanager\Modeling\QueryOperation;
use Premanager\Execution\Options;
use Premanager\Execution\Environment;
use Premanager\IO\Config;
use Premanager\IO\DataBase\DataBaseHelper;
use Premanager\NameConflictException;
use Premanager\Module;
use Premanager\Modeling\Model;
use Premanager\ArgumentNullException;
use Premanager\ArgumentOutOfRangeException;
use Premanager\InvalidEnumArgumentException;
use Premanager\DateTime;
use Premanager\TimeSpan;
use Premanager\Types;
use Premanager\Strings;
use Premanager\ArgumentException;
use Premanager\UnexpectedStateException;
use Premanager\InvalidOperationException;
use Premanager\Debug\Debug;
use Premanager\Debug\AssertionFailedException;
use Premanager\IO\File;
use Premanager\IO\FileNotFoundException;
use Premanager\IO\CorruptDataException;
use Premanager\IO\Request;
use Premanager\IO\DataBase\DataBase;
use Premanager\Modeling\QueryList;
use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\DataType;
use Premanager\Execution\Translation;

/**
 * A user
 */
final class User extends Model {
	private $_title;
	private $_color;
	private $_hasAvatar;
	private $_avatarType;
	private $_status;
	private $_hasPersonalSidebar;
	private $_email;
	private $_styleID;
	private $_style = false;
	private $_unconfirmedEmail;
	private $_unconfirmedEmailStartTime = false;
	private $_unconfirmedEmailKey;
	private $_registrationTime;
	private $_registrationIP;
	private $_lastLoginTime = false;
	private $_lastLoginIP;
	private $_lastVisibleLoginTime = false;
	private $_resetPasswordKey;
	private $_resetPasswordStartTime = false;
	private $_resetPasswordIP;
	private $_groups;
	private $_rights = array();

	/**
	 * @var Premanager\Models\UserModel
	 */
	private static $_descriptor;

	const EMAIL_PATTERN = '[a-z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]{0,2}[a-z0-9])?';

	// ===========================================================================

	public static function __init() {
		// Remove outdated sessions
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')." ".
			"SET resetPasswordKey = '', resetPasswordIP = '', ".
				"resetPasswordStartTime = '0000-00-00 00:00:00' ".
			"WHERE resetPasswordStartTime < DATE_SUB(NOW(), INTERVAL ".
				Options::defaultGet('Premanager', 'reset-password.expiration-time').
				" SECOND)");
	}

	/**
	 * Gets a boulde of information about this model
	 *
	 * @return Premanager\Models\UserModel
	 */
	public static function getDescriptor() {
		return UserModel::getInstance();
	}
	
	/**
	 * Gets a user class using its id
	 * 
	 * @param int $id
	 * @return Premanager\Models\User
	 */
	public static function getByID($id) {
		return self::getModelDescriptor()->getByID($id);
	}
                               
	/**
	 * Gets a user using its name
	 *
	 * Returns null if $name is not found
	 *
	 * @param string $name name of user
	 * @return Premanager\Models\User 
	 */
	public static function getByName($name) {
		return self::getModelDescriptor()->getByName($name);
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
		return self::getDescriptor()->createNew($name, $password, $email,
			$isEnabled);
	}

	/**
	 * Checks whether the name is a valid user name
	 *
	 * Note: this does NOT check whether the name is available
	 * (see isNameAvailable())
	 *
	 * @param string $name the name to check
	 * @return bool true, if the name is valid
	 */
	public static function isValidName($name) {
		return $name && strpos($name, '/') === false;
	}

	/**
	 * Checks whether the string is a valid email address
	 *
	 * @param string $email the email address to check
	 * @return bool true, if the email address is valid
	 */
	public static function isValidEmail($email) {
		return preg_match('/^'.self::EMAIL_PATTERN.'$/i', $email) == 1;
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
	public static function isNameAvailable($name, $ignoreThis = null) {
		return $this->getDescriptor()->isNameAvailable($name, $ignoreThis);
	}

	/**
	 * Checks whether the specified email is not already assigned to a user
	 *
	 * Note: this does NOT check whether the email is valid (see isValidEmail())
	 *
	 * @param string $email the email to check
	 * @param Premanager\Models\User|null $ignoreThis a user which may have
	 *   the name; it is excluded
	 * @return bool true, if this email is available
	 */
	public static function isEmailAvailable($email, User $ignoreThis = null) {
		$l = self::getUsers();
		$l = $l->filter($l->expr(QueryOperation::LOGICAL_OR,
			$l->exprEqual($l->exprMember('email'), $email),
			$l->exprEqual($l->exprMember('unconfirmedEmail'), $email)));
		if ($ignoreThis instanceof User)
			$l = $l->filter(
				$l->exprUnequal(
					$l->exprThis(),
					$ignoreThis));
		return $l->getCount() == 0;
	}

	/**
	 * Gets the guest user
	 *
	 * @return Premanager\Models\User
	 */
	public static function getGuest() {
		return self::getByID(0);
	}

	/**
	 * Gets the count of users
	 *
	 * @return int
	 */
	public static function getCount() {
		$result = DataBase::query(
			"SELECT COUNT(user.userID) AS count ".
			"FROM ".DataBase::formTableName('Premanager', 'Users')." AS user");
		return $result->get('count');
	}

	/**
	 * Gets a list of users
	 *
	 * @return Premanager\Modeling\QueryList
	 */
	public static function getUsers() {
		return self::getDescriptor()->getQueryList();
	}

	/**
	 * Updates $color and $title property for all users
	 */
	public static function clearAllCache() {
		$users = self::getUsers();
		foreach ($users as $user) {
			$user->clearCache();
		}
	}

	// ===========================================================================
	
	/**
	 * Gets the User model descriptor
	 * 
	 * @return Premanager\Models\UserModel the User model descriptor
	 */
	public function getModelDescriptor() {
		return self::getDescriptor();
	}

	/**
	 * Gets the user name
	 *
	 * @return string
	 */
	public function getName() {
		return parent::getName();
	}

	/**
	 * Gets the title of the topmost group
	 *
	 * @return string
	 */
	public function getTitle() {
		$this->checkDisposed();

		if ($this->_title === null)
			$this->load();
		return $this->_title;
	}

	/**
	 * Gets the color of the topmost group (hexadecimal RRGGBB format)
	 *
	 * @return string
	 */
	public function getColor() {
		$this->checkDisposed();

		if ($this->_color === null)
			$this->load();
		return $this->_color;
	}

	/**
	 * Gets true if this user has its own avatar
	 *
	 * @return bool
	 */
	public function hasAvatar() {
		$this->checkDisposed();

		if ($this->_hasAvatar === null)
			$this->load();
		return $this->_hasAvatar;
	}

	/**
	 * Gets the MIME type of the avatar
	 *
	 * @return string
	 */
	public function getAvatarType() {
		$this->checkDisposed();

		if ($this->_avatarType === null)
			$this->load();
		return $this->_avatarType;
	}

	/**
	 * Checks whether this user can be used for login
	 *
	 * @return bool true, if the user can be used for login
	 */
	public function isEnabled() {
		$this->checkDisposed();

		if ($this->_status === null)
			$this->load();
		return $this->_status == 'enabled';
	}

	/**
	 * Checks whether the user is automatically enabled when the email address
	 * is confirmed
	 *
	 * @return bool true, if the user is automatically enabled when the email
	 *   address is enabled
	 */
	public function getEnableOnEmailConfirmation() {
		$this->checkDisposed();

		if ($this->_status === null)
			$this->load();
		return $this->_status == 'waitForEmail';
	}

	/**
	 * Gets true, if this user has its own sidebar
	 *
	 * @return bool
	 */
	public function getHasPersonalSidebar() {
		$this->checkDisposed();

		if ($this->_hasPersonalSidebar === null)
			$this->load();
		return $this->_hasPersonalSidebar;
	}

	/**
	 * Gets the email address of this user (may be an empty string)
	 *
	 * @return string
	 */
	public function getEmail() {
		$this->checkDisposed();

		if ($this->_email === null)
			$this->load();
		return $this->_email;
	}

	/**
	 * Gets the style this user has chosen, if this user has explicitly chosen a
	 * style
	 *
	 * @return Premanager\Models\Style the style or null if no style is chosen
	 */
	public function getStyle() {
		$this->checkDisposed();

		if ($this->_style === false) {
			if ($this->_styleID === null)
				$this->load();
			$this->_style = $this->_styleID ? Style::getByID($this->_styleID) : null;
		}
		return $this->_style;
	}

	/**
	 * Gets an email address that is not confirmed yet
	 *
	 * @return string
	 */
	public function getUnconfirmedEmail() {
		$this->checkDisposed();

		if ($this->_unconfirmedEmail === null)
			$this->load();
		return $this->_unconfirmedEmail;
	}

	/**
	 * Gets the timestamp the unconfirmed email address has been set
	 *
	 * @return Premanager\DateTime
	 */
	public function getUnconfirmedEmailStartTime() {
		$this->checkDisposed();

		if ($this->_unconfirmedEmailStartTime === false)
			$this->load();
		return $this->_unconfirmedEmailStartTime;
	}

	/**
	 * Gets the date/time the unconfirmed email expires
	 *
	 * @return Premanager\DateTime
	 */
	public function getUnconfirmedEmailExpirationTime() {
		$this->checkDisposed();

		$time = $this->getUnconfirmedEmailStartTime();
		if ($time)
			return $time->add(TimeSpan::fromSeconds(Options::defaultGet('Premanager',
				'unconfirmed-email.expiration-time')));
		else
			return null;
	}

	/**
	 * Gets a key that is needed to confirm the unconfirmed email address
	 *
	 * @return string
	 */
	public function getUnconfirmedEmailKey() {
		$this->checkDisposed();

		if ($this->_unconfirmedEmailKey === null)
			$this->load();
		return $this->_unconfirmedEmailKey;
	}

	/**
	 * Gets timestamp this user has been created
	 *
	 * @return Premanager\DateTime
	 */
	public function getRegistrationTime() {
		$this->checkDisposed();

		if ($this->_registrationTime === null)
			$this->load();
		return $this->_registrationTime;
	}

	/**
	 * Gets the ip address from that this user has been created
	 *
	 * @return int
	 */
	public function getRegistrationIP() {
		$this->checkDisposed();
			throw new InvalidOperationException('User is deleted');

		if ($this->_registrationIP === null)
			$this->load();
		return $this->_registrationIP;
	}

	/**
	 * Gets date/time this user has logged in the last time
	 * (whatever hidden or visible)
	 *
	 * @return Premanager\DateTime the last login time or null
	 */
	public function getLastLoginTime() {
		$this->checkDisposed();

		if ($this->_lastLoginTime === false)
			$this->load();
		return $this->_lastLoginTime;
	}

	/**
	 * Gets the ip from that user has logged in the last time
	 * (whatever hidden or visible)
	 *
	 * @return string
	 */
	public function getLastLoginIP() {
		$this->checkDisposed();

		if ($this->_lastLoginIP === null)
			$this->load();
		return $this->_lastLoginIP;
	}

	/**
	 * Gets the date/time this user has logged in the last time without hiding
	 * itself
	 *
	 * @return Premanager\DateTime or null
	 */
	public function getLastVisibleLoginTime() {
		$this->checkDisposed();

		if ($this->_lastVisibleLoginTime === false)
			$this->load();
		return $this->_lastVisibleLoginTime;
	}

	/**
	 * Gets the key which can be used to change the password of this user without
	 * knowing the current password
	 *
	 * @return string the reset-password key, or an empty string if there is no
	 *   up-to-date key
	 */
	public function getResetPasswordKey() {
		$this->checkDisposed();

		if ($this->_resetPasswordKey === null)
			$this->load();
		return $this->_resetPasswordKey;
	}

	/**
	 * Gets the ip of the computer from which the password was reset
	 *
	 * @return string the ip or an empty string, if the password was not reset
	 */
	public function getResetPasswordIP() {
		$this->checkDisposed();

		if ($this->_resetPasswordIP === null)
			$this->load();
		return $this->_resetPasswordIP;
	}

	/**
	 * Gets the date/time when the password was reset
	 *
	 * @return Premanager\DateTime the date/time when the password was reset or
	 *   null if the password was not reset
	 */
	public function getResetPasswordStartTime() {
		$this->checkDisposed();

		if ($this->_resetPasswordStartTime === false)
			$this->load();
		return $this->_resetPasswordStartTime;
	}

	/**
	 * Gets the date/time when the password reset key will expire
	 *
	 * @return Premanager\DateTime the date/time when the password reset key will
	 *   expire, or null, if there is no password reset key
	 */
	public function getResetPasswordExpirationTime() {
		$this->checkDisposed();

		$time = $this->getResetPasswordStartTime();
		if ($time)
			return $time->add(TimeSpan::fromSeconds(Options::defaultGet('Premanager',
				'resetPasswordExpirationTime')));
		else
			return null;
	}

	/**
	 * Gets all rights this user has in the specified project
	 *
	 * @see hasRight()
	 *
	 * @param Premanager\Modles\Project $project the project or null to select
	 *   rights of all projects
	 * @param bool $loginConfirmed if false, only rights of groups that do not
	 *   require login confirmation are included. By default true.
	 * @return array an array of Premanager\Models\Right objects
	 */
	public function getRights($project, $loginConfirmed = true) {
		$this->checkDisposed();

		if ($project instanceof Project)
			$key = $project->getID();
		else
			$key = '_';
		if ($loginConfirmed)
			$key .=  '*';

		if ($this->_rights[$key] === null)
			$this->loadRights($project, $loginConfirmed);
		return $this->_rights[$key];
	}

	/**
	 * Checks if this user has the specified right
	 *
	 * @param Premanager\Models\Right $right the right to check
	 * @param Premanager\Models\Projecr $project the project in which the user
	 *   should have this right, or null to search in all projects
	 * @param bool $loginConfirmed if false, only rights of groups that do not
	 *   require login confirmation are included. By default true
	 * @return bool true, if this user has the specified right in the project
	 */
	public function hasRight(Right $right, $project = null,
		$loginConfirmed = true)
	{
		$this->checkDisposed();
		return array_search($right, $this->getRights($project, $loginConfirmed))
			!== false;
	}

	/**
	 * Gets the list of groups this user is a member of
	 *
	 * @return Premanager\Modeling\QueryList
	 */
	public function getGroups() {
		$this->checkDisposed();

		if ($this->_groups === null)
			$this->_groups = Group::getGroups()->joinFilter('Premanager', 'UserGroup',
				"item.id = [join].groupID AND [join].userID = $this->_id");
		return $this->_groups;
	}

	/**
	 * Checks whether this user is member of a specified group
	 *
	 * @param Premanager\Models\Group $group group to check
	 * @return bool
	 */
	public function isInGroup(Group $group) {
		$this->checkDisposed();

		$result = DataBase::query(
			"SELECT userGroup.groupID ".
			"FROM ".DataBase::formTableName('Premanager', 'UserGroup').
				" AS userGroup ".
			"WHERE userGroup.userID = $this->_id ".
				"AND userGroup.groupID = ".$group->getID());
		return $result->next();
	}

	/**
	 * Checks whether this user is member of a group of the specified project
	 *
	 * @param Premanager\Models\Project $project project to check
	 * @return bool
	 */
	public function isProjectMemberOf(Project $project) {
		$this->checkDisposed();

		$result = DataBase::query(
			"SELECT userGroup.groupID ".
			"FROM ".DataBase::formTableName('Premanager', 'UserGroup').
				" AS userGroup ".
			"INNER JOIN ".DataBase::formTableName('Premanager', 'Groups')." AS grp ".
				"ON userGroup.groupID = grp.id ".
			"WHERE userGroup.userID = $this->_id ".
				"AND grp.parentID = ".$project->getID());
		return $result->next();
	}

	/**
	 * Checks if the specifies password is correct for this user

	 * @param string $password the password to check
	 * @return bool true, if the password is correct
	 */
	public function checkPassword($password) {
		$this->checkDisposed();

		$isSecondaryPassword = false;

		// Password properties are not stored as class vars for security reasons
		$result = DataBase::query(
			"SELECT user.password ".
			"FROM ".DataBase::formTableName('Premanager', 'Users')." AS user ".
			"WHERE user.id = '$this->_id'");
		$dbPassword = $result->get('password');

		$encodedPassword = $this->encodePassword($password);
		return $dbPassword == $encodedPassword;
	}

	/**
	 * Changes the status
	 *
	 * Only if an user is enabled it can be used for login.
	 *
	 * @param bool true to enable the user, false to disable it
	 * @throws Premanager\InvalidOperationException this is the guest account
	 */
	public function setIsEnabled($value) {
		$this->checkDisposed();

		if (!$value && !$this->_id)
			throw new InvalidOperationException(
				'The guest account can not be disabled');

		if ($value)
			$status = "'enabled'";
		else
			$status = "IF(user.status = 'waitForEmail', 'waitForEmail', 'disabled')";

		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')." AS user ".
			"SET user.status = ".$status." ".
			"WHERE user.id = $this->_id");

		if ($value)
			$this->_status = 'enabled';
		else if (!$this->getEnableOnEmailConfirmation())
			$this->_status = 'disabled';
	}

	/**
	 * Changes the email address
	 *
	 * This value will be changed in data base and in this object.
	 *
	 * @param string $email the new email address. Must not be used yet.
	 */
	public function setEmail($email) {
		$this->checkDisposed();

		$email = trim($email);

		// Check if email address exists
		if ($email && !self::isEmailAvailable($email))
			throw new ArgumentException('There is already a user using this email '.
				'address');
			
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')." AS user ".
			"SET user.email = '".DataBase::escape($email)."' ".
			"WHERE user.id = $this->_id");

		$this->_email = $email;
	}

	/**
	 * Changes the style
	 *
	 * This value will be changed in data base and in this object.
	 *
	 * @param Premanager\Models\Style $style the style or null to reset to the
	 *   default style
	 * @throws Premanager\InvalidOperationException this user is the guest
	 */
	public function setStyle($style) {
		$this->checkDisposed();

		if (!$this->_id)
			throw InvalidOperationException('Cannot set style for guest');

		if ($style !== null && !($style instanceof Style))
			throw new ArgumentException(
				'$style must be either null or a Premanager\Models\Style', 'style');
			
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')." AS user ".
			"SET user.email = '".($style ? $style->getID() : 0)."' ".
			"WHERE user.id = $this->_id");

		$this->_style = $style;
		$this->_styleID = $style ? $style->getID() : 0;
	}

	/**
	 * Deletes and disposes this user
	 *
	 * @throws Premanager\InvalidOperationException this user is the guest account
	 */
	public function delete() {
		$this->checkDisposed();

		if (!$this->_id)
			throw new InvalidOperationException(
				'The guest account can not be deleted');

		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'UserGroup')." ".
			"WHERE userID = '$this->_id'");

		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'UserOptions')." ".
			"WHERE userID = '$this->_id'");

		parent::delete();
	}

	/**
	 * Inserts this user into a user group
	 *
	 * If this user is already in that group, nothing is done.
	 *
	 * @param Premanager\Models\Group $group
	 */
	public function joinGroup(Group $group) {
		$this->checkDisposed();

		if (!$group)
			throw new ArgumentNullException('group');

		// If user is already in that group, just add 0 to 0 (do "nothing")
		DataBase::query(
			"INSERT INTO ".DataBase::formTableName('Premanager', 'UserGroup')." ".
			"(userID, groupID, joinTime, joinIP) ".
			"VALUES ('$this->_id', '".$group->getID()."', NOW(), ".
				"'".Request::getIP()."') ".
			"ON DUPLICATE KEY UPDATE userID = userID");

		if (DataBase::getAffectedRowCount())
			$this->_groups = null;

		// If group is of organization and has a priority, title and color might
		// have changed
		if (!$group->getProject()->getID() && $group->getPriority())
			$this->clearCache();
	}

	/**
	 * Removes this user from a user group
	 *
	 * If this user is not a member of that group, nothing is done.
	 *
	 * @param Premanager\Models\Group $group
	 */
	public function leaveGroup(Group $group) {
		$this->checkDisposed();

		if (!$group)
			throw new ArgumentNullException('group');

		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'UserGroup')." ".
			"WHERE userID = '$this->_id' ".
				"AND groupID = '".$group->getID()."'");

		if (DataBase::getAffectedRowCount())
			$this->_groups = null;

		// If group is of organization and has a priority, title and color might
		// have changed
		if (!$group->getProject()->getID() && $group->getPriority())
			$this->clearCache();
	}

	/**
	 * Sets an unconfirmed email address
	 *
	 * A random key is generated that is needed to confirm this email address.
	 *
	 * @param string $email the email address. Must not be in use yet.
	 * @return string the random key needed to confirm the email address
	 */
	public function setUnconfirmedEmail($email) {
		$this->checkDisposed();

		$email = trim($email);
		if (!$email)
			throw new ArgumentException('$email is null or an empty string', 'email');
		if (!self::isEmailAvailable($email))
			throw new ArgumentException('There is already a user using this email '.
				'address');

		$key = self::generateKey();

		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')." ".
			"SET unconfirmedEmail = '".DataBase::escape($email)."', ".
				"unconfirmedEmailStartTime = NOW(), ".
				"unconfirmedEmailKey = '".DataBase::escape($key)."' ".
			"WHERE id = '$this->_id'");
		$this->_unconfirmedEmailKey = $key;
		$this->_unconfirmedEmail = $email;
		$this->_unconfirmedEmailStartTime = DateTime::getNow();

		return $key;
	}

	/**
	 * Removes the unconfirmed email address
	 *
	 * If there was no unconfirmed email address set, does nothing.
	 */
	public function removeUnconfirmedEmail($email) {
		$this->checkDisposed();

		$email = trim($email);
		if (!$email)
			throw new ArgumentException('$email is null or an empty string', 'email');

		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')." ".
			"SET unconfirmedEmail = '', ".
				"unconfirmedEmailStartTime = '0000-00-00 00:00:00', ".
				"unconfirmedEmailKey = '' ".
			"WHERE id = '$this->_id'");
		$this->_unconfirmedEmailKey = '';
		$this->_unconfirmedEmail = '';
		$this->_unconfirmedEmailStartTime = null;
	}

	/**
	 * Confirms the unconfirmed email address
	 *
	 * Moves the unconfirmed email to the email property and clears unconfirmed
	 * email properties.
	 *
	 * If status was 'waitForEmail', it changes to 'enabled'.
	 *
	 * If there was no unconfirmed email address set, clears email property and
	 * changes status as mentioned above
	 */
	public function confirmUnconfirmedEmail() {
		$this->checkDisposed();

		// Store unconfirmed email before it's lost
		$email = $this->getUnconfirmedEmail();

		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')." ".
			"SET email = unconfirmedEmail,
				unconfirmedEmail = '', ".
				"unconfirmedEmailStartTime = '0000-00-00 00:00:00', ".
				"unconfirmedEmailKey = '', ".
				"status = IF(status = 'waitForEmail', 'enabled', status) ".
			"WHERE id = '$this->_id'");
		$this->_email = $email;
		$this->_unconfirmedEmailKey = '';
		$this->_unconfirmedEmail = '';
		$this->_unconfirmedEmailStartTime = null;
		if ($this->getEnableOnEmailConfirmation())
			$this->_status = 'enabled';
	}


	/**
	 * Sets whether the user is automatically enabled when the email address
	 * is confirmed
	 *
	 * Only if an user is enabled it can be used for login.
	 *
	 * @param bool true to automatically enable the user when the email adress is
	 *   confirmed
	 */
	public function setEnableOnEmailConfirmation($value) {
		$this->checkDisposed();

		if ($this->_status === null)
			$this->load();

		if ($value && $this->_status != 'enabled')
			$status = 'waitForEmail';
		else if (!$value && $this->_status == 'waitForEmail')
			$status = 'disabled';

		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')." AS user ".
			"SET user.status = '$status' ".
			"WHERE user.id = $this->_id");

		$this->_status = $status;
	}

	/**
	 * Changes the password
	 *
	 * @param string $password
	 */
	public function setPassword($password) {
		$this->checkDisposed();

		$password = \trim($password);
		if (!$password)
			throw new ArgumentException('$password is null or an empty string',
				'password');

		$encodedPassword = $this->encodePassword($password);
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')." ".
			"SET password = '".DataBase::escape($encodedPassword)."' ".
			"WHERE id = '$this->_id'");
	}

	/**
	 * Generates a key for the reset-password function
	 *
	 * If there is already a key for this user, the method does nothing else than
	 * returining that key.
	 *
	 * @return string the random key needed to confirm the email address
	 */
	public function resetPassword() {
		$this->checkDisposed();

		if ($this->getResetPasswordKey())
			return $this->_resetPasswordKey;

		$key = self::generateKey();

		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')." ".
			"SET resetPasswordKey = '".DataBase::escape($key)."', ".
				"resetPasswordStartTime = NOW(), ".
				"resetPasswordIP = '".DataBase::escape(Request::getIP())."' ".
			"WHERE id = '$this->_id'");
		$this->_resetPasswordKey = $key;
		$this->_resetPasswordIP = Request::getIP();
		$this->_resetPasswordStartTime = new DateTime();

		return $key;
	}

	/**
	 * Deletes a reset password key generated by resetPassword()
	 */
	public function deleteResetPasswordKey() {
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')." ".
			"SET resetPasswordKey = '', ".
				"resetPasswordStartTime = '0000-00-00 00:00:00', ".
				"resetPasswordIP = '' ".
			"WHERE id = '$this->_id'");
		$this->_resetPasswordKey = '';
		$this->_resetPasswordIP = '';
		$this->_resetPasswordStartTime = null;
	}

	/**
	 * Gets the path to the avatar of this user
	 *
	 * @return string the file name or null, if this user does not have an avatar
	 */
	public function getAvatarFileName() {
		if ($this->hasAvatar())
			return Config::getStorePathOf('Premanager') . '/avatars/' . $this->_id;
	}

	/**
	 * Gets the path to the default avatar
	 *
	 * @return string the path to the default avatar
	 */
	public static function getDefaultAvatarFileName() {
		return Config::getPluginPathOf('Premanager').'/includes/defaultAvatar.png';
	}

	/**
	 * Gets the MIME type of the default avatar
	 *
	 * @return string the MIME type of the default avatar
	 */
	public static function getDefaultAvatarType() {
		return 'image/png';
	}

	/**
	 * Loads the avatar of this user and gets it as Image instance
	 *
	 * @return Premanager\Media\Image $image the avatar image or null if this user
	 *   does not have an avatar
	 * @throws Premanager\CorruptDataException avatar file is missing or could not
	 *   be loaded
	 */
	public function getAvatar() {
		$this->checkDisposed();

		if ($this->hasAvatar()) {
			$fileName = $this->getAvatarFileName();
			if (!File::exists($fileName))
				throw new CorruptDataException('Avatar file for the user '.$this->_id.
					' is missing');

			$image = Image::fromFile($fileName);
			if (!$image)
				throw new CorruptDataException('Avatar file for the user '.$this->_id.
					'could not be loaded');
			return $image;
		} else
			return null;
	}

	/**
	 * Sets an image as avatar for this user.
	 *
	 * If it is too large, it is scaled down.
	 *
	 * If this user has already an avatar, the old avatar is deleted.
	 *
	 * @param Premanager\Media\Image $image the new avatar image
	 */
	public function setAvatar(Image $image) {
		$this->checkDisposed();

		$image->resizeProportionally(
			Options::defaultGet('Premanager', 'avatar.max-width'),
			Options::defaultGet('Premanager', 'avatar.max-height'));

		$image->saveToFile(
			Config::getStorePathOf('Premanager') . '/avatars/' . $this->_id);

		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')."  ".
			"SET hasAvatar = '1', ".
				"avatarMIME = '".DataBase::escape($image->getType())."' ".
			"WHERE id = '$this->_id'");

		$this->_hasAvatar = true;
		$this->_avatarType = $image->getType();
	}

	/**
	 * Deletes the avatar for this user.
	 *
	 * If this user does not have an avatar, nothing is done.
	 */
	public function deleteAvatar() {
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')."  ".
			"SET hasAvatar = '0' ".
			"WHERE id = '$this->_id'");


		$fileName = Config::getStorePathOf('Premanager').'/avatars/'.$this->_id;
		if (File::exists($fileName))
			File::delete($fileName);

		$this->_hasAvatar = false;
		$this->_avatarType = '';
	}

	/**
	 * Updates $color and $title property
	 */
	public function clearCache() {
		$result = DataBase::query(
			"SELECT grp.id, grp.color, translation.title ".
			"FROM ".DataBase::formTableName('Premanager', 'Groups')." AS grp ".
			"INNER JOIN ".DataBase::formTableName('Premanager', 'UserGroup').
				" AS userGroup ".
				"ON userGroup.groupID = grp.id ",
			/* translating */
			"WHERE userGroup.userID = '$this->_id' ".
				"AND grp.parentID = 0 ". // only chose groups of organization
				"AND grp.priority > 0 ".
			"ORDER BY grp.priority DESC ".
			"LIMIT 0,1");
		if ($result->next()) {
			$groupID = $result->get('id');
			$this->_color = $result->get('color');
			$this->_title = $result->get('title');
		} else {
			$this->_color = '000000';
			$this->_title =
				Translation::defaultGet('Premanager', 'defaultUserTitle');
		}

		// Update color
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')." ".
			"SET color = '$this->_color' ".
			"WHERE id = '$this->_id'");

		// Delete current translations
		DataBase::query(
			"DELETE FROM ".DataBase::formTableName('Premanager', 'UsersTranslation')." ".
			"WHERE id = '$this->_id'");

		// Update all languages
		foreach (Language::getLanguages() as $language) {
			// Get title
			Environment::push(Environment::create(
				Environment::getCurrent()->getUser(),
				Environment::getCurrent()->getSession(),
				Environment::getCurrent()->getPageNode(),
				Environment::getCurrent()->getProject(),
				$language,
				Environment::getCurrent()->getStyle(),
				Environment::getCurrent()->getEdition()));
			try {
				if ($groupID) {
					$result = DataBase::query(
						"SELECT translation.title ".
						"FROM ".DataBase::formTableName('Premanager', 'Groups')." AS grp ",
						/* translating */
						"WHERE grp.id = '$groupID'");
					$title = $result->get('title');
				} else
					$title = Translation::defaultGet('Premanager', 'defaultUserTitle');
				Environment::pop();
			} catch (\Exception $e) {
				Environment::pop();
				throw $e;
			}

			// Update title
			DataBase::query(
				"INSERT INTO ".
					DataBase::formTableName('Premanager', 'UsersTranslation')." ".
				"(id, languageID, title) ".
				"VALUES ('$this->_id', '".$language->getID()."', ".
					"'".DataBase::escape($title)."')");
		}
	}

	/**
	 * Sets the $lastLoginTime and, if $hidden is false, the $lastVisibleLoginTime
	 * properties to the current date/time
	 *
	 * Has to be called when a user logs in
	 *
	 * @param bool $hidden true, if the login should not be displayed in public
	 */
	public function updateLoginTime($hidden = false) {
		DataBase::query(
			"UPDATE ".DataBase::formTableName('Premanager', 'Users')." ".
			"SET lastLoginTime = NOW(), ".
				"lastLoginIP = '".Request::getIP()."' ".
				(!$hidden ? ", lastVisibleLoginTime = NOW()" : '').
			"WHERE id = '$this->_id'");

		$this->_lastLoginTime = DateTime::getNow();
		$this->_lastLoginIP = Request::getIP();
		if (!$hidden)
			$this->_lastVisibleLoginTime = DateTime::getNow();
	}

	// moved to bottom because Eclipse seems to have trouble with lambda.
	/**
	 * Changes the user name or set a translation for "guest", if this guest
	 *
	 * This value will be changed in data base and in this object.
	 *
	 * @param string $name new user name
	 * @throws Premanage\NameConflictOperation the name is already assigned to a
	 *   user
	 */
	public function setName($name) {
		$this->checkDisposed();

		$name = trim($name);

		if (!$name)
			throw new ArgumentException(
				'$name is an empty string or contains only whitespaces', 'name');
		if (!self::isValidName($name))
			throw new ArgumentException('$name is not a valid user name');
		if (!self::isNameAvailable($name, $this))
			throw new NameConflictException('This name is already assigned to a user',
				$name);

		// Update guest's name
	  if (!$this->_id) {
			$result = DataBase::query(
				"SELECT string.id, translation.languageID ".
				"FROM ".DataBase::formTableName('Premanager', 'Strings')." AS string ".
				"LEFT JOIN ".DataBase::formTableName('Premanager', 'StringsTranslation').
					" AS translation ".
					"ON string.id = translation.id ".
					"AND translation.languageID = '".
						Environment::getCurrent()->$language->getid()."' ".
				"WHERE string.pluginID = '0' ".
					"AND string.name = 'guest'");
			if (Types::isInteger($result->get('languageID'))) {
				DataBase::query(
					"UPDATE ".
						DataBase::formTableName('Premanager', 'StringsTranslation'). " ".
					"SET value = '".DataBase::escape($_name)."' ".
					"WHERE id = '".$result->get('id')."' ".
						"AND languageID = '".Environment::getCurrent()->$language->getid()."'");
			} else {
				DataBase::query(
					"INSERT INTO ".
						DataBase::formTableName('Premanager', 'StringsTranslation'). " ".
					"(id, languageID, value) ".
					"VALUES ('".$result->get('id')."', ".
						"'".Environment::getCurrent()->$language->getid()."', ".
						"'".DataBase::escape($this->_name)."')");
			}
		} else
			$data = array('name' => $this->_name);

		$this->update($data, array(), $name);
	}
	
	/**
	 * Gets all names used by this model and their languages
	 * 
	 * @return array array($name =>
	 *   array('languageID' => $languageID, 'nameGroup' => $nameGroup))
	 */
	protected function getNamesInUse() {
		$this->checkDisposed();
		
		if (!$this->_id) {
			$result = DataBase::query(
				"SELECT translation.value, translation.languageID ".
				"FROM ".DataBase::formTableName('Premanager', 'Strings')." AS string ".
				"INNER JOIN ".
					DataBase::formTableName('Premanager', 'StringsTranslation').
					" AS translation ".
					"ON string.id = translation.id ".
				"WHERE string.pluginID = '0' ".
					"AND string.name = 'guest'");
			$names = array();
			while ($result->next()) {
				$names[$result->get('name')] = array(
					'languageID' => $result->get('languageID'),
					'nameGroup' => '');
			}
			return $names;
		} else
			return parent::getNamesInUse();
	}

	// ===========================================================================

	/**
	 * Encodes a plain password
	 *
	 * @param string $password the plain password
	 * @return string the ecnoded password
	 */
	private static function encodePassword($password) {
		return hash('sha256',
			Config::getSecurityCode().
			'89c54dcc6124c96115f3c0733ff8072299e7305f7a36902ea5f5cfee0f7939a6'.
			hash('sha256',
				'9e4d3431361375e515abd82e261ceb04b14cf517426a8ce74ffbdde9239da356'.
				Config::getSecurityCode().$password));
	}
	/**
	 * Fills the fields from data base
	 * 
	 * @param array $fields an array($name => $sql) where $sql is a SQL statement
	 *   to store under the alias $name
	 * @return array ($name => $value) the values for the fields - or false if the
	 *   model does not exist in data base
	 */
	public function load(array $fields = array()) {
		$fields = array(
			'color',
			'translation.title',
			'hasAvatar',
			'avatarMIME',
			'status',
			'styleID',
			'email',
			'unconfirmedEmail',
			'registrationTime',
			'registrationIP',
			'lastLoginTime',
			'lastLoginIP',
			'lastVisibleLoginTime',
			'resetPasswordKey',
			'resetPasswordIP',
			'resetPasswordStartTime'
		);
		
		if ($values = parent::load($fields)) {
			$this->_color = $values['color'];
			$this->_title = $values['title'];
			$this->_hasAvatar = !!$values['hasAvatar'];
			$this->_avatarType = $values['avatarMIME'];
			$this->_status = $values['status'];
			$this->_hasPersonalSidebar = $values['hasPersonalSidebar'];
			$this->_email = $values['email'];
			$this->_styleID = $values['styleID'];
			$this->_unconfirmedEmail = $values['unconfirmedEmail'];
			$this->_unconfirmedEmailStartTime = $this->_unconfirmedEmail ?
				new DateTime($values['unconfirmedEmailStartTime']) : null;
			$this->_unconfirmedEmailKey = $values['unconfirmedEmailKey'];
			$this->_registrationTime =
				new DateTime($values['registrationTime']);
			$this->_registrationIP = $values['registrationIP'];
			$this->_lastLoginTime =
				$values['lastLoginTime'] != '0000-00-00 00:00:00' ?
				new DateTime($values['lastLoginTime']) : null;
			$this->_lastLoginIP = $values['lastLoginIP'];
			$this->_lastVisibleLoginTime =
				$values['lastVisibleLoginTime'] != '0000-00-00 00:00:00' ?
				new DateTime($values['lastVisibleLoginTime']) : null;
			$this->_resetPasswordKey = $values['resetPasswordKey'];
			if ($this->_resetPasswordKey) {
				$this->_resetPasswordIP = $values['resetPasswordIP'];
				$this->_resetPasswordStartTime =
					new DateTime($values['resetPasswordStartTime']);
			} else {
				$this->_resetPasswordStartTime = null;
				$this->_resetPasswordIP = '';
			}
		}
		
		return $values;
	}

	/**
	 * Loads the rights of this user into $_rights field
	 */
	private function loadRights($project, $loginConfirmed) {
		if ($project instanceof Project)
			$key = $project->getID();
		else
			$key = '_';
		if ($loginConfirmed)
			$key .=  '*';
		$this->_rights[$key] = array();

		if ($project)
			$where =
				"WHERE (grp.parentID = 0 OR grp.parentID = ".$project->getID().")";
		if (!$loginConfirmed) {
			if ($where)
				$where .= " AND ";
			else
				$where = "WHERE ";
			$where .= "NOT grp.loginConfirmationRequired ";
		}

		$result = DataBase::query(
			"SELECT rght.id ".
			"FROM ".DataBase::formTableName('Premanager', 'Groups').
				" AS grp ".
			"INNER JOIN ".DataBase::formTableName('Premanager', 'UserGroup').
				" AS userGroup ".
				"ON grp.id = userGroup.groupID ".
				"AND userGroup.userID = '$this->_id' ".
			"INNER JOIN ".DataBase::formTableName('Premanager', 'GroupRight').
				" AS groupRight ".
				"ON groupRight.groupID = grp.id ".
			"INNER JOIN ".DataBase::formTableName('Premanager', 'Rights').
				" AS rght ".
				"ON groupRight.rightID = rght.id ".
			$where.
			"GROUP BY rght.id ".
			"ORDER BY rght.pluginID ASC, ".
				"rght.name ASC");
		while ($result->next())
			$this->_rights[$key][] = Right::getByID($result->get('id'));
	}

	/**
	 * Joins groups with "auto join" flag
	 */
	private function joinAutoJoinGroups() {
		$result = DataBase::query(
			"SELECT grp.id ".
			"FROM ".DataBase::formTableName('Premanager', 'Groups')." AS grp ".
			"WHERE grp.autoJoin");
		while ($result->next()) {
			$this->joinGroup(Group::getByID($result->get('id')));
		}
	}

	// Returns the cookie value
	private function generateKey()  {
		return substr(hash('sha256',
			'4310fbf72c046c412e46a028eff9c4043788bc50928c04d4bbcfbccac4132498'.
			Config::getSecurityCode().
			hash('sha256',
				'1b323eb338f3c051e24651c24f6ab9121f779ccb27b0e9179f7b80a3e4c858e6'.
				$this->getName().Config::getSecurityCode().$this->getID()).
				Request::getIP().time()), 0, 16);
	}
}

User::__init();

?>
