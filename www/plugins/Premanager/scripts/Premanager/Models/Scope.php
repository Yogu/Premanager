<?php 
namespace Premanager\Models;

/**
 * Specifies whether a tree class or right is available for the organization,
 * for projects, or for both organization and projects
 */
class Scope {
	/**
	 * The tree class is only available for the organization
	 */
	const ORGANIZATION = 0;
	
	/**
	 * The tree class is only available for projects, not for the organization
	 */
	const PROJECTS = 1;
	
	/**
	 * The tree class is available for both organization and projects
	 */
	const BOTH = 2;
}

?>