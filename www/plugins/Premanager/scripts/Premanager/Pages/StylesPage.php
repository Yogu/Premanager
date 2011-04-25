<?php
namespace Premanager\Pages;

use Premanager\Execution\Environment;
use Premanager\Types;
use Premanager\Models\Style;
use Premanager\Execution\TreePageNode;
use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Execution\ToolBarItem;
use Premanager\Models\Project;
use Premanager\Debug\Debug;
use Premanager\Modeling\SortDirection;
use Premanager\Modeling\QueryOperation;
use Premanager\Modeling\SortRule;
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
 * A page that shows a list of all available styles and allows to enable and
 * disable them and to select a default style
 */
class StylesPage extends TreePageNode {
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		if (!Rights::requireRight(Right::getByName('Premanager', 'manageStyles'),
			null, $errorResponse, false))
			return $errorResponse;
		
		$list = self::getList();
		$notGuest = !!Environment::getCurrent()->getUser()->getID();
			
		if (Request::getPOST('submit')) {
			if (!Rights::requireRight(Right::getByName('Premanager', 'manageStyles'),
				null, $errorResponse))
				return $errorResponse;
			
			$isEnabled = Request::getPOST('isEnabled');
			if (!is_array($isEnabled))
				$isEnabled = array();
			Debug::assert($list->getCount(), 'no styles found');
				
			// First check if any style is enabled
			$isAnyEnabled = false;
			$defaultEnabled = false;
			foreach ($list as $style) {
				$isAnyEnabled |= array_search($style->getID(), $isEnabled) !== false;
				$defaultEnabled |= array_search(Request::getPOST('default'), $isEnabled)
					!== false;
			}
			
			if (!$isAnyEnabled) {
				$error = 'noStyleEnabledError';
			} else if (!$defaultEnabled) {
				$error = 'defaultStyleDisabledError';
			} else {
				// To avoid disabling all styles, enable the first style at first
				$isFirst = true;
				foreach ($list as $style) {
					if ($isFirst) {
						$style->setIsEnabled(true);
						$isFirst = false;
					} else {
						$style->setIsEnabled(array_search($style->getID(),
							$isEnabled) !== false);
					}
				}
				$firstStyle = $list[0];
				$firstStyle->setIsEnabled(array_search($firstStyle->getID(),
					$isEnabled) !== false);
					
				$id = Request::getPOST('default');
				if (Types::isInteger($id) && $id >= 0) {
					$style = Style::getByID($id);
					if ($style) {
						Style::setDefault($style);
					}
				}
			}
		} else if ($notGuest) {
			// Guests may have manageStyle right - don't know how crazy admins are...
			$post = Request::getPOSTValues();
			foreach ($post as $name => $value) {
				if (substr($name, 0, 6) == 'select') {
					$id = substr($name, 7);
					$style = Style::getByID($id);
					if ($style)
						Environment::getCurrent()->getUser()->setStyle($style);
					break;
				}
			}
		}
		
		$page = new Page($this);
		$page->title = Translation::defaultGet('Premanager', 'styles');
		$page->createMainBlock(
			'<p>'.Translation::defaultGet('Premanager', 'stylesMessage').'</p>');
		
		$template = new Template('Premanager', 'styleListHead');
		$template->set('notGuest', $notGuest);
		$head = $template->get();
		
		$template = new Template('Premanager', 'styleListBody');
		$template->set('list', $list);
		$template->set('node', $this);
		$template->set('notGuest', $notGuest);
		$body = $template->get();
		
		$template = new Template('Premanager', 'styleListFrame');
		if ($error)
			$template->set('errors', array('styles' => array(
				Translation::defaultGet('Premanager', $error))));
		$frame = $template->get();
		
		$page->appendBlock(PageBlock::createTable($head, $body, $frame));
		
		return $page;
	} 
	
	/**
	 * Gets the list of projects sorted by title
	 * 
	 * @return Premanager\Modeling\QueryList the list of users
	 */
	private static function getList() {
		static $list;
		if (!$list) {
			$list = Style::getStyles();
			$list = $list->sort(array(
				new SortRule($list->exprMember('title'))));
		}
		return $list;
	}
}

?>
