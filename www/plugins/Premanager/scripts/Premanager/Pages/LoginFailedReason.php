<?php
namespace Premanager\Pages;

/**
 * Defines the reason why the login has failed
 */
class LoginFailedReason {
	/**
	 * Specifies that a user could not log in because the user is disabled
	 * @var int
	 */
	const STATUS = 0;
	
	/**
	 * Specifies that the user name is invalid
	 * @var int
	 */
	const USER = 1;
	
	/**
	 * Specifies that the password is wrong
	 * @var int
	 */
	const PASSWORD = 2; 
}
