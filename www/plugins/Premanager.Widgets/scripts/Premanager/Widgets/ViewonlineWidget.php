<?php
namespace Premanager\Widgets;

use Premanager\Execution\PageNode;
use Premanager\Execution\Template;
use Premanager\Pages\ViewonlinePage;
use Premanager\Execution\Translation;
use Premanager\Execution\PageBlock;

class ViewonlineWidget extends Widget {
	/**
	 * Gets the content of this widget in HTML
	 * 
	 * @return string the content in HTML
	 */
	public function getContent() {
		$list = ViewonlinePage::getList()->getAll();
					
		$template = new Template('Premanager.Widgets', 'viewonlineWidget');
		$template->set('sessions', $list);
		return $template->get();
	}
	
	/**
	 * Gets a sample content in HTML
	 * 
	 * @return string the content in HMTL
	 */
	public static function getSampleContent() {
		$list = ViewonlinePage::getList()->getAll();
					
		$template = new Template('Premanager.Widgets', 'viewonlineWidget');
		$template->set('sessions', $list);
		return $template->get();
	}
	
	/**
	 * Gets a url, if this widget should be linked to it
	 * 
	 * @return string the relative url or an empty string
	 */
	public function getLinkURL() {
		return PageNode::getTreeURL('Premanager', 'viewonline');
	}
}

?>