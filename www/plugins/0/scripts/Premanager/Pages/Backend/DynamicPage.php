<?php
namespace Premanager\Pages\Backend;

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
		$response = new XMLResponse();
		$w = $response->writer;
		$w->startElement('error');
		$w->startElement('message');
		$w->text('This feature is not implemented yet.');
		$w->endElement();
		$w->endElement();
		return $response;
	}
}

?>
