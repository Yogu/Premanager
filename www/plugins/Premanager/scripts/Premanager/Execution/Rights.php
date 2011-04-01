<?php
namespace Premanager\Execution;

use Premanager\Debug\Debug;
use Premanager\ArgumentException;
use Premanager\Models\Scope;
use Premanager\Models\Project;
use Premanager\Models\Right;

/**
 * Provides helper methods for checking for rights and confirming logins
 */
class Rights {
	public static function hasRight(Right $right, Project $project = null) {
		self::validateProject($right, $project);
		
		return Environment::getCurrent()->getUser()->hasRight($right, $project);
	}
	
	public static function hasRightInAnyProject(Right $right) {		
		return Environment::getCurrent()->getUser()->hasRight($right);
	}
	
	/**
	 * Checks whether the current user has the specified right and prepares an
	 * error response if needed
	 * 
	 * @param Premanager\Models\Right $right the right to check. Specify an array
	 *   of rights if only one of them is required
	 * @param Premanager\Models\Project $project the project
	 * @param Premanager\Execution\Response $errorResponse the response to output
	 *   if the right is not available. out-only.
	 * @param bool $active true, if the login should be confirmed if required or
	 *   false to return true even if the session is not confirmed
	 * @return bool true, if the user has the specified right
	 */
	public static function requireRight($right, Project $project = null,
		&$errorResponse, $active = true) {
		if (!is_array($right))
			$rights = array($right);
		else if ($right instanceof Right)
			$rights = $right;
		else
			throw new ArgumentException('$right must be either an instance of '.
				'Premanager\Models\Right or an array of such objects');
		foreach ($rights as &$right) {
			if (!($right instanceof Right))
				throw new ArgumentException(
					'An item of the $right array is no Premanager\Models\Right', 'right');
				self::validateProject($right, $project);
		}
		unset($right);
				
		if (!$active || Environment::getCurrent()->getSession() && 
			Environment::getCurrent()->getSession()->isConfirmed())
		{
			$ok = true;
			foreach ($rights as $right)	{
				$ok = Environment::getCurrent()->getUser()->hasRight($right, $project);
				if ($ok)
					break;
			}
		} else {
			foreach ($rights as $right)	{
				$ok = Environment::getCurrent()->getUser()->hasRight(
					$right, $project, false);
				if ($ok)
					break;
			}
			if (!$ok) {
				foreach ($rights as $right)	{
					$confirmationRequired = Environment::getCurrent()->getUser()->hasRight
						($right, $project, true);
					if ($confirmationRequired)
						break;
				}
			}
		}
		
		// Guests can't confirm their login
		if ($ok ||
			($confirmationRequired && !Environment::getCurrent()->getUser()->getID()))
			return true;
		else {
			$errorResponse = new Page(Environment::getCurrent()->getPageNode());
			
			if ($confirmationRequired) {
				$errorResponse->title = Translation::defaultGet('Premanager',
					'loginConfirmationPageTitle');
				$template = new Template('Premanager', 'loginConfirmationWrap');
				$errorResponse->createMainBlock($template->get());
			} else {
				$errorResponse->title = Translation::defaultGet('Premanager',
					'accessDenied');
				$errorResponse->createMainBlock(Translation::defaultGet('Premanager',
					'accessDeniedMessage'));
			}
		}
	}
	
	private static function validateProject(Right $right, &$project) {
		if ($right->getScope() == Scope::ORGANIZATION && $project &&
			$project->getID())
			throw new ArgumentException('For organization rights $project must be '.
				'either null or the organization project', 'project');
		if ($right->getScope() == Scope::PROJECTS && (!$project ||
			!$project->getID()))
			throw new ArgumentException('For project rights $project must be '.
				'a valid project and must not be the organization', 'project');
		
		if ($right->getScope() == Scope::ORGANIZATION)
			$project = Project::getOrganization();
	}
}

?>