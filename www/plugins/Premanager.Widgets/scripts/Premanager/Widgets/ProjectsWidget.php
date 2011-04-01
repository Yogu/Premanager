<?php
namespace Premanager\Widgets;

use Premanager\QueryList\SortDirection;
use Premanager\QueryList\SortRule;
use Premanager\Models\Project;
use Premanager\Execution\PageNode;
use Premanager\Execution\Template;
use Premanager\Pages\ViewonlinePage;
use Premanager\Execution\Translation;
use Premanager\Execution\PageBlock;

/**
 * Provides a widget that shows a list of projects
 */
class ProjectsWidget extends Widget {
	/**
	 * Gets the content of this widget in HTML
	 * 
	 * @return string the content in HTML
	 */
	public function getContent() {
		return self::getSampleContent();
	}
	
	/**
	 * Gets a sample content in HTML
	 * 
	 * @return string the content in HMTL
	 */
	public static function getSampleContent() {
		$list = Project::getProjects();
		$list = $list->sort(array(
			new SortRule($list->exprEqual($list->exprMember('id'), 0),
				SortDirection::DESCENDING),
			new SortRule($list->exprMember('title'))));
					
		$template = new Template('Premanager.Widgets', 'projectsWidget');
		$template->set('projects', $list);
		return $template->get();
	}
}

?>
