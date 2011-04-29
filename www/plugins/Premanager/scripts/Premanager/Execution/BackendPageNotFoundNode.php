<?php
namespace Premanager\Execution;

use Premanager\IO\Request;
use Premanager\IO\StatusCode;
use Premanager\Execution\Template;
use Premanager\NotImplementedException;
use Premanager\ArgumentException;
use Premanager\Models\StructureNode;
use Premanager\IO\Output;
use Premanager\Module;

/**
 * Defines a backend page node that indicates that the requested page does not
 * exist
 */
class BackendPageNotFoundNode extends PageNotFoundNode {
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'backendPageTitle');
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$response = new XMLResponse(404);
		$w = $response->writer;
		$w->startElement('error');
		$w->startElement('message');
		$w->text('This page does not exist');
		$w->endElement();
		$w->endElement();
		return $response;
	}
}

?>
