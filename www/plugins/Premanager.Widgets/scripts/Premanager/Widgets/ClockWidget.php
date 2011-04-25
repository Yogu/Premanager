<?php
namespace Premanager\Widgets;

use Premanager\DateTime;

use Premanager\Execution\Options;
use Premanager\Execution\StructurePageNode;
use Premanager\Pages\StructureNodePage;
use Premanager\Models\StructureNode;
use Premanager\Execution\Environment;
use Premanager\Modelling\SortDirection;
use Premanager\Modelling\SortRule;
use Premanager\Models\Project;
use Premanager\Execution\PageNode;
use Premanager\Execution\Template;
use Premanager\Pages\ViewonlinePage;
use Premanager\Execution\Translation;
use Premanager\Execution\PageBlock;

/**
 * Provides a widget that shows a list of all children of the current page node
 */
class ClockWidget extends Widget {
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
		$template = new Template('Premanager.Widgets', 'clockWidget');
		$template->set('time', DateTime::getNow());
		return $template->get();
	}
	
	private static function getContentForPageNode(PageNode $pageNode) {
		$count = Options::defaultGet('Premanager', 'page-tree.max-child-count');
		$children = $pageNode->getChildren($count);
		$template = new Template('Premanager.Widgets', 'subpagesWidget');
		$template->set('children', $children);
		$template->set('node', $pageNode);
		return $template->get();
	}
}

?>
