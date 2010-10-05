<?php
namespace Premanager\Pages;

/**
 * Defines the reason why the login has failed
 */
class LoginFailedReason {
	/**
	 * Specifies that the login was successful
	 */
	const SUCCESSFUL = 0;
	
	/**
	 * Specifies that a user could not log in because the user is disabled
	 * @var int
	 */
	const STATUS = 1;
	
	/**
	 * Specifies that the user name is invalid
	 * @var int
	 */
	const USER = 2;
	
	/**
	 * Specifies that the password is wrong
	 * @var int
	 */
	const PASSWORD = 3; 
}
