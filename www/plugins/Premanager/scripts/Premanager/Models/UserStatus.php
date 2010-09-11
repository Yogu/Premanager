<?php
namespace Premanager\Models;

/**
 * Specifies whether a user can log in or not
 */
class UserStatus {
	/**
	 * Specifies that a user can log in
	 */
	const DISABLED = 0x00;
	
	/**
	 * Specifies that a user can not log in
	 */
	const ENABLED = 0x01;
	
	/**
	 * Specifies that a user can not log in until its email is confirmed
	 */
	const WAIT_FOR_EMAIL = 0x02;
}

?>