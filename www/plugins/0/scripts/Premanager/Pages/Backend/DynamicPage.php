<?php
namespace Premanager\Pages\Backend;

use Premanager\Execution\StringResponse;

use Premanager\Execution\Environment;

use Premanager\Execution\PageNotFoundNode;

use Premanager\IO\URLInfo;

use Premanager\Execution\XMLResponse;
use Premanager\Execution\BackendPageNode;
use Premanager\Premanager;
use Premanager\Execution\TreeListPageNode;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Translation;
use Premanager\Execution\Page;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\Models\Group;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\Execution\ListPageNode;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page that can send the content of another page
 */
class DynamicPage extends BackendPageNode {
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$url = Request::getGET('url');
		
		if ($url[0] == '/')
			$url = Environment::getCurrent()->getURLPrefix() .
				substr($url, 1, strlen($url));
		$urlInfo = new URLInfo($url);
		$pageNode = $urlInfo->getPageNode();

		$response = new XMLResponse();
		$w = $response->writer;
		$w->startElement('response');
		
		if ($pageNode instanceof PageNotFoundNode) {
			$w->writeAttribute('type', 'not-found');
			$w->startElement('message');
			$w->text('This page does not exist.');
			$w->endElement();
		} else if (($page = $pageNode->getResponse()) instanceof Page) {
			// Get list of node, parent of node, parent of parent of node ...
			$hierarchy = array();
			for ($node = $pageNode; $node != null; $node = $node->getParent()) {
				$hierarchy[] = $node;
			}
			
			
			$template = new Template('Premanager', 'dynamicPageResponse');
			$template->set('hierarchy', $hierarchy);
			$template->set('pageNode', $pageNode);
			$template->set('page', $page);
			
			return new StringResponse($template->get(), 'text/xml');
		} else {
			$w->writeAttribute('type', 'no-page');
			$w->startElement('message');
			$w->text('This url must be accessed directly.');
			$w->endElement();
		}
		
		$w->endElement();
		return $response;
	}
}

?>
