<?php
namespace Premanager\Widgets;

use Premanager\Models\User;
use Premanager\Execution\Template;

class Sidebar extends WidgetCollection {
	/**
	 * Internal use only.
	 */
	public function __construct(StructureNode $structureNode = null,
		User $user = null)
	{	
		parent::__construct($structureNode, $user);
	}
	
	/**
	 * Gets the widget collection for a sidebar, either of a specific user or the
	 * default sidebar shown to all guests and users without own sidebars
	 * 
	 * @param Premanager\Models\User $user the user whose sidebar to get or null
	 *   to get the default sidebar
	 * @return Premanager\Widgets\Sidebar the sidebar
	 */
	public static function get(User $user = null) {
		if ($user && $user->getID() == 0)
			$user = null;
		
		return WidgetCollection::internalGet(null, $user, __CLASS__);
	}
	
	/**
	 * Gets the default sidebar shown to all guests and users without own sidebars
	 * 
	 * @return Premanager\Widgets\Sidebar the default sidebar
	 */
	public static function getDefault() {
		return self::get();
	}
	
	/**
	 * Gets the sidebar of a user, if the specified user has a sidebar, or the
	 * default sidebar
	 * 
	 * @param Premanager\Models\User $user the user whose sidebar to get or null
	 *   to get the default sidebar
	 * @return Premanager\Widgets\Sidebar
	 */
	public static function getExisting(User $user = null) {
		$sidebar = self::get($user);
		if (!$user || !$user->getID() || $sidebar->getCount())
			return $sidebar;
		else
			return self::getDefault();
	}
	
	/**
	 * Gets the html code of the whole sidebar
	 * 
	 * @return the html code of the whole sidebar
	 */
	public function getHTML() {
		$template = new Template('Premanager.Widgets', 'sidebar');
		$template->set('widgets', $this->getWidgets());
		return $template->get();
	}
}

?>