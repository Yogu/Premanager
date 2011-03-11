<?php
namespace Premanager\Widgets;

use Premanager\Module;
use Premanager\Widgets\Sidebar;
use Premanager\Execution\Environment;
use Premanager\Widgets\WidgetCollection;
use Premanager\Execution\Page;
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
			$sidebar = Sidebar::getExisting(Environment::getCurrent()->getUser());
			$template = $params['template'];
			$template->afterNavigationTree .= $sidebar->getHTML();
		});
	}
	
	/**
	 * Is called in the second initializing loop
	 */
	public function init() {
		
	}
}