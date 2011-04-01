<?php
namespace Premanager\Widgets;

use Premanager\Execution\Options;
use Premanager\Execution\StructurePageNode;
use Premanager\Pages\StructureNodePage;
use Premanager\Models\StructureNode;
use Premanager\Execution\Environment;
use Premanager\QueryList\SortDirection;
use Premanager\QueryList\SortRule;
use Premanager\Models\Project;
use Premanager\Execution\PageNode;
use Premanager\Execution\Template;
use Premanager\Pages\ViewonlinePage;
use Premanager\Execution\Translation;
use Premanager\Execution\PageBlock;

/**
 * Provides a widget that shows a list of all children of the current page node
 */
class SubpagesWidget extends Widget {
	/**
	 * Gets the content of this widget in HTML
	 * 
	 * @return string the content in HTML
	 */
	public function getContent() {
		return
			self::getContentForPageNode(Environment::getCurrent()->getPageNode());
	}
	
	/**
	 * Gets a sample content in HTML
	 * 
	 * @return string the content in HMTL
	 */
	public static function getSampleContent() {
		return self::getContentForPageNode(new StructurePageNode());
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
