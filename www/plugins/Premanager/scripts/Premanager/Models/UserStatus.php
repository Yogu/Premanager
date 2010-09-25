<?php
namespace Premanager\Models;

/**
 * Specifies whether a user can log in or not
 */
class UserStatus {
	/**
	 * Specifies that a user can log in
	 */
	const DISABLED = 0;
	
	/**
	 * Specifies that a user can not log in
	 */
	const ENABLED = 1;
	
	/**
	 * Specifies that a user can not log in until its email is confirmed
	 */
	const WAIT_FOR_EMAIL = 2;
}

?>
