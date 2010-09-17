<?php
namespace Premanager;

use Premanager\Debug\Debug;

use Premanager\IO\Request;
use Premanager\IO\Config;;
use Premanager\IO\Output;
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
			if ($plugin->initializer)
				$plugin->initializer->primaryInit();
		}
		// Call the main init routines of all plugins 
		foreach (Plugin::getPlugins() as $plugin) {
			if ($plugin->initializer)
				$plugin->initializer->init();
		}
		
		// Check if the user has accessed a valid url and that the request url
		// equals the url of the actual resource
		Request::validateURL();

		// If a user is logged in, note that it has made another request.
		if (Environment::getCurrent()->session)
			Environment::getCurrent()->session->hit();
			
		// Execute the request
		Request::getPageNode()->execute();
		
		// Output headers and content
		Output::finish();
	}
}

Premanager::__init();

?>
