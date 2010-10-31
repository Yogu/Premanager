<?php
namespace Premanager;

use Premanager\InvalidOperationException;
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

/**
 * The starter class for the Premanager site
 */
class Premanager extends Module {
	private static $_isRunning = false;
	
	/**
	 * Starts Premanager and outputs the requested page
	 * 
	 * @throws InvalidOperationException Premanager is already running (see
	 *   isRunning())
	 */
	public static function run() {
		if (self::$_isRunning)
			throw new InvalidOperationException('Premanager is already running');
		
		// Call the primary init routines of all plugins, e.g. to assign event
		// handlers
		foreach (Plugin::getPlugins() as $plugin) {
			if ($plugin->getinitializer())
				$plugin->getinitializer()->primaryInit();
		}
		// Call the main init routines of all plugins 
		foreach (Plugin::getPlugins() as $plugin) {
			if ($plugin->getinitializer())
				$plugin->getinitializer()->init();
		}
		
		// Check if the user has accessed a valid url and that the request url
		// equals the url of the actual resource
		Request::validateURL();

		// If a user is logged in, note that it has made another request.
		if (Environment::getCurrent()->getsession())
			Environment::getCurrent()->getsession()->hit();
			
		// Execute the request
		Output::send(Request::getPageNode()->getResponse());
	}
	
	/**
	 * Checks whether Premanager has already been started
	 * 
	 * @return bool true, if Premanager is running
	 */
	public static function isRunning() {
		return self::$_isRunning;
	}
}

?>
