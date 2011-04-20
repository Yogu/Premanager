<?php
namespace Premanager\Widgets;

use Premanager\Execution\Rights;
use Premanager\Models\Right;
use Premanager\Execution\Translation;
use Premanager\Execution\ToolBarItem;
use Premanager\Pages\UserPage;
use Premanager\Widgets\Pages\MySidebarPage;
use Premanager\Widgets\Pages\SidebarPage;
use Premanager\Execution\Template;
use Premanager\Execution\PageNode;
use Premanager\Widgets\Pages\SidebarAdminPage;
use Premanager\Module;
use Premanager\Widgets\Sidebar;
use Premanager\Execution\Environment;
use Premanager\Widgets\WidgetCollection;
use Premanager\Execution\Page;
use Premanager\IO\Config;
use Premanager\Debug\Debug;
use Premanager\Media\Image;
use Premanager\Media\ImageLoader;
use Premanager\Execution\PluginInitializer;

/**
 * Initializes the Premanager.Widgets plugin
 */
class Initializer extends Module implements PluginInitializer {
	/**
	 * Is called in the first initializing loop. This method is for registering
	 * event handlers, for that they will be called when other plugins do
	 * something interesting in the main initializing method
	 */
	public function primaryInit() {
		Page::$templatePreparedEvent->register(function($sender, $params) {
			$pageNode = Environment::getCurrent()->getPageNode();
			if ($pageNode instanceof SidebarAdminPage)
				$sidebar = Sidebar::getDefault();
			else
				$sidebar = Sidebar::getExisting(Environment::getCurrent()->getUser());

			if (Environment::getCurrent()->getPageNode() instanceof SidebarPage) {
				$sidebarTemplate =
					new Template('Premanager.Widgets', 'sidebarAdminSidebar');
				$sidebarTemplate->set('widgets', $sidebar->getWidgets());
				$sidebarHTML = $sidebarTemplate->get();
			} else {
				$sidebarHTML = $sidebar->getHTML();
			}
		
			$template = $params['template'];
			if (trim($sidebarHTML)) {
				$template->afterNavigationTree .= $sidebarHTML;
				$template->bodyClasses .= ' has-sidebar';
			}
		});
		Page::$generatingContentEvent->register(function($sender, $params) {
			$page = $sender;
			$page->addStylesheet('Premanager.Widgets/stylesheets/stylesheet.css');
			
			if ($page->getNode() instanceof UserPage) {
				$user = $page->getNode()->getUser();
				if ($user->getID() && Rights::hasRight(Right::getByName(
					'Premanager.Widgets','editUserSidebars')))
				{
					$page->toolbar[] = new ToolBarItem(
						PageNode::getTreeURL('Premanager.Widgets', 'sidebar-admin').
							'?user='.$user->getName(),
						Translation::defaultGet('Premanager.Widgets', 'userSidebar'), 
						Translation::defaultGet('Premanager.Widgets',
							'userSidebarDescription'),
						'Premanager.Widgets/images/tools/sidebar.png');
					}
			} 
		});
	}
	
	/**
	 * Is called in the second initializing loop
	 */
	public function init() {
		
	}
}