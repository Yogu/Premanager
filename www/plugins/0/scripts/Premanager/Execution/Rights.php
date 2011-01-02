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
	
	public static function requireRight($right, Project $project = null,
		&$errorResponse) {
		if (!is_array($right))
			$rights = array($right);
		else
			$rights = $right;
		foreach ($rights as &$right)
			self::validateProject($right, $project);
		unset($right);
				
		if (Environment::getCurrent()->getSession() && 
			Environment::getCurrent()->getSession()->isConfirmed())
		{
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
		
		if ($ok)
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