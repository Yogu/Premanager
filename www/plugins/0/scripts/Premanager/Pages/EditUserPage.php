<?php
namespace Premanager\Pages;

use Premanager\Models\Right;
use Premanager\Execution\Rights;
use Premanager\Execution\Redirection;
use Premanager\Execution\ToolBarItem;
use Premanager\Models\User;
use Premanager\Premanager;
use Premanager\Execution\TreeListPageNode;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Translation;
use Premanager\Execution\Page;
use Premanager\Execution\StructurePageNode;
use Premanager\Execution\PageNode;
use Premanager\ArgumentNullException;
use Premanager\IO\Request;
use Premanager\Execution\Template;
use Premanager\Execution\ListPageNode;
use Premanager\ArgumentException;
use Premanager\IO\Output;

/**
 * A page that allows to edit an existing user
 */
class EditUserPage extends UserFormPage {	
	private $_user;

	// ===========================================================================
	
	/**
	 * Creates a new EditUserPage
	 * 
	 * @param Premanager\Execution\ParentNode $parent the parent node
	 * @param Premanager\Models\User $user the user to edit
	 */
	public function __construct($parent, User $user) {
		parent::__construct($parent, $user);

		$this->_user = $user;
	} 

	// ===========================================================================
	
	/**
	 * Gets the name that is used in urls
	 * 
	 * @return string
	 */
	public function getName() {
		return 'edit';
	}
	
	/**
	 * Gets the displayed title that is used when the titles of the parent nodes
	 * are also displayed
	 * 
	 * @return string
	 */
	public function getTitle() {
		return Translation::defaultGet('Premanager', 'editUser');
	}
	
	/**
	 * Applies the values and gets the response
	 * 
	 * Is called when the form is submitted and validated. 
	 * 
	 * @param array $values the array of values
	 * @return Premanager\Execution\Response the response to send
	 */
	protected function applyValues(array $values) {
		if (!Rights::requireRight(Right::getByName('Premanager', 'editUsers'),
			null, $errorResponse))
			return $errorResponse;
			
		if ($values['name'] != $this->_user->getName())
			$this->_user->setName($values['name']);
		if ($values['email'] != $this->_user->getEmail())
			$this->_user->setEmail($values['email']);
		if ($values['password'])
			$this->_user->setPassword($values['password']);
		if ($values['isEnabled'] != $this->_user->isEnabled())
			$this->_user->setIsEnabled($values['isEnabled']);
			
		return new Redirection($this->getParent()->getURL());
	}
	
	/**
	 * Gets the values for a form without POST data
	 * 
	 * @return array the array of values
	 */
	protected function getDefaultValues() {
		return array(
			'name' => $this->_user->getName(),
			'email' => $this->_user->getEmail(),
			'emailConfirmation' => $this->_user->getEmail(),
			'isEnabled' => $this->_user->isEnabled());
	}
	
	/**
	 * Gets the template used for the form
	 * 
	 * @return Premanager\Execution\Template the template
	 */
	protected function getTemplate() {
		$template = parent::getTemplate();
		$template->set('user', $this->_user);
		return $template;
	}
	
	/**
	 * Checks if this object represents the same page as $other
	 * 
	 * @param Premanager\Execution\PageNode $other
	 */
	public function equals(PageNode $other) {
		return $other instanceof EditUserPage &&
			$other->_user == $this->_user; 
	}	    
}

?>
