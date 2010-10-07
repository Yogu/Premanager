<?php
namespace Premanager\Pages;

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
 * A page that shows information about a group
 */
class GroupPage extends PageNode {
	/**
	 * @var Premanager\Models\Group
	 */
	private $_group;

	// ===========================================================================
	
	/**
	 * Creates a new page node
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\Group $parent the group to view
	 */
	public function __construct($parent, Group $group) {
		parent::__construct($parent);
		
		$this->_group = $group;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_group->name;
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->_group->name;
	}

	/**
	 * Performs a call of this page
	 */
	public function execute() {
		$page = new Page($this);
		
		$template = new Template('Premanager', 'groupView');
		$template->set('node', $this);
		$template->set('group', $this->_group);
		
		$page->createMainBlock($template->get());
		
		Output::select($page);
	}

	// ===========================================================================

	/**
	 * Gets the group represented by this page
	 * 
	 * @return Premanager\Models\Group the group represented by this page
	 */
	public function getGroup() {
		return $this->_group;
	}
}

?>
