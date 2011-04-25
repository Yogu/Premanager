<?php
namespace Premanager\Pages;

use Premanager\Execution\FileResponse;
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
 * A page to download the avatar a user
 */
class UserAvatarPage extends PageNode {
	/**
	 * @var Premanager\Models\User
	 */
	private $_user;

	// ===========================================================================
	
	/**
	 * Creates a new UserAvatarPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\User $user the user to select
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
		return 'avatar';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'avatar');
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof UserAvatarPage &&
			$other->_user == $this->_user;
	}

	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		if ($this->_user->hasAvatar()) {
			$fileName = $this->_user->getAvatarFileName();
			$type = $this->_user->getAvatarType();
		} else {
			$fileName = User::getDefaultAvatarFileName();
			$type = User::getDefaultAvatarType();
		}
		
		$displayFileName = Translation::defaultGet('Premanager', 'avatarOf', array(
			'userName' => $this->_user->getName()));
		if ($type == 'image/jpeg')
			$displayFileName .= '.jpg';
		else
			$displayFileName .= '.png';
		
		return new FileResponse($fileName, $displayFileName, $type);
	}
}

?>
