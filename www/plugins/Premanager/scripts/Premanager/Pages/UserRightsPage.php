<?php
namespace Premanager\Pages;

use Premanager\Execution\Rights;

use Premanager\Models\Scope;
use Premanager\Models\Right;
use Premanager\Execution\Redirection;
use Premanager\Types;
use Premanager\Execution\FormPageNode;
use Premanager\Execution\ToolBarItem;
use Premanager\Models\Project;
use Premanager\Models\User;
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
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page that shows the rights of a user
 */
class UserRightsPage extends PageNode {
	/**
	 * @var Premanager\Models\User
	 */
	private $_user;

	// ===========================================================================
	
	/**
	 * Creates a new UserRightsPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\User $parent the user to select
	 */
	public function __construct($parent, User $user) {
		parent::__construct($parent);
		
		$this->_user = $user;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return 'rights';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'viewUserRights');
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof UserRightsPage && $other->_user === $this->_user;
	}

	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$projects = Project::getProjects();
		$projects = $projects->sort(array(
			new SortRule($projects->exprEqual($projects->exprMember('id'), 0),
				SortDirection::DESCENDING),
			new SortRule($projects->exprMember('title'))));
			
		$headTemplate = new Template('Premanager', 'userRightListHead');
		$headTemplate->set('node', $this);
		$headTemplate->set('user', $this->_user);
		$bodyTemplate = new Template('Premanager', 'userRightListBody');
		$bodyTemplate->set('node', $this);
		$headTemplate->set('user', $this->_user);
		$blocks = array();
		
		$right = Right::getByName('Premanager', 'manageRights');
		foreach ($projects as $project) {
			if (Rights::hasRight($right, $project)) {
				$rights = $this->_user->getRights($project);
				if (count($rights)) {
					$headTemplate->set('project', $project);
					$headTemplate->set('rights', $rights);
					$bodyTemplate->set('project', $project);
					$bodyTemplate->set('rights', $rights);
					$body = trim($bodyTemplate->get());
					if ($body)
						$blocks[] = PageBlock::createTable($headTemplate->get(), $body);
				}
			}
		}
		
		if (count($blocks))
			$message = Translation::defaultGet('Premanager', 'viewUserRightsMessage');
		else
			$message =
				Translation::defaultGet('Premanager', 'viewUserRightsEmptyMessage');
				
		$page = new Page($this);
		$page->createMainBlock('<p>'.$message.'</p>');
		foreach ($blocks as $block)
			$page->appendBlock($block);
			
		return $page;
	}
}

?>
