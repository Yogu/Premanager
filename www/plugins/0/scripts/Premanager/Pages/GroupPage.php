<?php
namespace Premanager\Pages;

use Premanager\Execution\ToolBarItem;
use Premanager\Models\TreeClass;
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
		return $this->_group->getname();
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->_group->getname();
	}
	
	/**
	 * Gets the child specified by its name
	 * 
	 * @param string $name the child's expected name
	 * @return Premanager\Execution\PageNode the child node or null if not found
	 */
	public function getChildByName($name) {
		if ($name == 'edit')
			return new EditGroupPage($this, $this->_group);
		if ($name == 'delete' && $this->_group->getID())
			return new DeleteGroupPage($this, $this->_group);
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$page = new Page($this);
		
		$template = new Template('Premanager', 'groupView');
		$template->set('node', $this);
		$template->set('group', $this->_group);
		$template->set('projectsURL',
			PageNode::getTreeURL('Premanager', 'projects'));
		
		$page->createMainBlock($template->get());
		
		$page->toolbar[] = new ToolBarItem($this->getURL().'/edit',
			Translation::defaultGet('Premanager', 'editGroup'), 
			Translation::defaultGet('Premanager', 'editGroupDescription'),
			'Premanager/images/tools/edit.png');
		
		$page->toolbar[] = new ToolBarItem($this->getURL().'/delete',
			Translation::defaultGet('Premanager', 'deleteGroup'), 
			Translation::defaultGet('Premanager', 'deleteGroupDescription'),
			'Premanager/images/tools/delete.png');
				
		return $page;
	}

	/**
	 * Gets the group represented by this page
	 * 
	 * @return Premanager\Models\Group the group represented by this page
	 */
	public function getGroup() {
		return $this->_group;
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof GroupPage &&
			$other->_group == $this->_group; 
	}	    
}

?>
