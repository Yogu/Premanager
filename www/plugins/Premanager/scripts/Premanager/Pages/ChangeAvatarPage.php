<?php
namespace Premanager\Pages;

use Premanager\Execution\Environment;
use Premanager\IO\Config;
use Premanager\Strings;
use Premanager\Media\Image;
use Premanager\IO\File;
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
 * A page to change or delete the own avatar
 */
class ChangeAvatarPage extends UserChangeAvatarPage {
	/**
	 * @var Premanager\Models\StructureNode
	 */
	private $_structureNode;
	
	/**
	 * Creates a new ChangeAvatarPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\StructureNode $structureNode the structure node
	 *   this page node is embedded in
	 */
	public function __construct($parent, StructureNode $structureNode) {
		parent::__construct($parent, Environment::getCurrent()->getUser(), true);
		$this->_structureNode = $structureNode;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_structureNode->getname();
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->_structureNode->gettitle();
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof ChangeAvatarPage &&
			$other->_structureNode === $this->_structureNode;
	}
	
	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		if (!Environment::getCurrent()->getUser()->getID())
			return Page::createMessagePage($this,
				Translation::defaultGet('Premanager', 'guestChangesAvatarMessage'));
		
		return parent::getResponse();
	}
}

?>
