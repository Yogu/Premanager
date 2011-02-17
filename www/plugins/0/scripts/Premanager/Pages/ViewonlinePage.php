<?php
namespace Premanager\Pages;

use Premanager\Execution\TreePageNode;
use Premanager\Models\Project;
use Premanager\Execution\Options;
use Premanager\DateTime;
use Premanager\Models\Session;
use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Execution\ToolBarItem;
use Premanager\Debug\Debug;
use Premanager\QueryList\SortDirection;
use Premanager\QueryList\QueryOperation;
use Premanager\QueryList\SortRule;
use Premanager\Execution\TreeListPageNode;
use Premanager\Models\StructureNode;
use Premanager\Execution\ListPageNode;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Translation;
use Premanager\Execution\Page;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\Models\User;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page that shows a list of all users
 */
class ViewonlinePage extends TreePageNode {	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$list = self::getList();
		
		$page = new Page($this);
		$page->title = Translation::defaultGet('Premanager', 'viewonline');
		$minutes = floor(Options::defaultGet('Premanager',
			'viewonline.max-session-age') / 60);
		$page->createMainBlock(Translation::defaultGet('Premanager',
			count($list) ? 'viewonlineMessage' : 'viewonlineEmpty',
			array('timeSpan' => $minutes)));
		
		if (count($list)) {
			$template = new Template('Premanager', 'viewonlineHead');
			$head = $template->get();
			
			$template = new Template('Premanager', 'viewonlineBody');
			$template->set('organization', Project::getOrganization());
			$template->set('sessions', $list);
			$template->set('node', $this);
			$body = $template->get();
			
			$page->appendBlock(PageBlock::createTable($head, $body));
		}
		
		return $page;
	} 

	/**
	 * Gets the list of viewonline sessions sorted by last request time
	 * 
	 * @return Premanager\QueryList\QueryList the list of sessions
	 */
	private static function getList() {
		static $cache;
		if (!$cache) {
			$cache = Session::getSessions();
			$cache = $cache->filter(
				$cache->expr(QueryOperation::GREATER,
					$cache->exprMember('lastRequestTime'),
					DateTime::getNow()->addSeconds(
						-Options::defaultGet('Premanager', 'viewonline.max-session-age'))));
			$cache = $cache->sort(array(
				new SortRule($cache->exprMember('lastRequestTime'))));
		}
		return $cache;
	}
}

?>
