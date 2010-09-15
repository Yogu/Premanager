<?php
namespace Premanager;

use Premanager\IO\Request;

use Premanager\IO\Config;

use Premanager\Execution\PageNode;

use Premanager\Execution\StructurePageNode;

use Premanager\QueryList\QueryOperation;
use Premanager\QueryList\QueryExpression;
use Premanager\Module;
use Premanager\Models\User;
use Premanager\Models\Group;
use Premanager\Models\Plugin;
use Premanager\Execution\Environment;

class Premanager extends Module {
	public static function __init() {
		// Call the primary init routines of all plugins, e.g. to assign event
		// handlers
		foreach (Plugin::getPlugins() as $plugin) {
			$plugin->getInitializer()->primaryInit();
		}
		// Call the main init routines of all plugins 
		foreach (Plugin::getPlugins() as $plugin) {
			$plugin->getInitializer()->init();
		}

		// If a user is logged in, note that it has made another request.
		if (Environment::getCurrent()->session)
			Environment::getCurrent()->session->hit();
			
		// Execute the request
		Request::getPageNode()->execute();
	}
}

Premanager::__init();

?>
