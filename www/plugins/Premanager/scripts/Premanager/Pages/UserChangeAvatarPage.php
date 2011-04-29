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
 * A page to change or delete the avatar of a user
 */
class UserChangeAvatarPage extends PageNode {
	/**
	 * @var Premanager\Models\User
	 */
	private $_user;
	/**
	 * @var bool
	 */
	private $_ownAvatar;

	// ===========================================================================
	
	/**
	 * Creates a new UserChangeAvatarPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\User $user the user to select
	 * @param bool $ownAvatar true, if messages should use 'your avatar' instead
	 *   of 'the avatar of user A' 
	 */
	public function __construct($parent, User $user, $ownAvatar = false) {
		parent::__construct($parent);
		
		$this->_user = $user;
		$this->_ownAvatar = $ownAvatar;
	}
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return 'change-avatar';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'userAvatarPageTitle');
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof UserChangeAvatarPage &&
			$other->_user === $this->_user;
	}

	/**
	 * Performs a call of this page and creates the response object
	 * 
	 * @return Premanager\Execution\Response the response object to send
	 */
	public function getResponse() {
		$template = new Template('Premanager', 'userChangeAvatar');
		$template->set('user', $this->_user);
		$template->set('ownAvatar', $this->_ownAvatar);
		
		if (Request::getPOST('submit')) {
			$fileName = Request::getUploadFileName('avatar');
			if (!$fileName && $_FILES['avatar']['fileName'])
				$error = Translation::defaultGet('Premanager',
					'changeAvatarFileTooLargeError', array('maxSize' =>
					Strings::formatFileSize(Config::getMaxUploadFileSize())));
			else if (!File::exists($fileName))
				$error = Translation::defaultGet('Premanager', 
					'changeAvatarNoFileSentError');
			else {
				$image = Image::fromFile($fileName);
				if (!$image)
					$error = Translation::defaultGet('Premanager',
						'pictureFileTypeNotSupportedError');
				else {
					// Own avatar can be changed without right
					if ($this->_user != Environment::getCurrent()->getUser() &&
						!Rights::requireRight(Right::getByName('Premanager', 'editUsers'),
						null, $errorResponse))
						return $errorResponse;
				
					$this->_user->setAvatar($image);
				}
			}
		} else if (Request::getPOST('delete') && $this->_user->hasAvatar()) {
			// Own avatar can be changed without right
			if ($this->_user != Environment::getCurrent()->getUser() &&
				!Rights::requireRight(Right::getByName('Premanager', 'editUsers'),
				null, $errorResponse))
				return $errorResponse;
			
			$this->_user->deleteAvatar();
			
			$template->set('action', 'deleted');
		}
		
		$template->set('error', $error);
		
		$page = new Page($this);
		$page->createMainBlock($template->get());
		return $page;
	}
}

?>
