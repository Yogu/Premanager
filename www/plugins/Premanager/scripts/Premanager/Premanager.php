<?php
namespace Premanager;

use Premanager\QueryList\QueryOperation;
use Premanager\QueryList\QueryExpression;
use Premanager\Module;
use Premanager\Models\User;
use Premanager\Models\Group;
use Premanager\Models\Plugin;
use Premanager\Execution\Environment;

class Premanager extends Module {
	public static function __init() {
		$time = microtime(true);
		
		$users = User::getUsers();
		$admin = $users->filter($users->exprEqual($users->exprMember('title'),
			'Administrator'))->get(0);
		
		$list = Group::getGroups();
		$sublist = $list->filter(
			$list->exprEqual(
				$list->exprMember('creator'),
				$admin
			)
		);
	
		foreach ($sublist as $group) {
			echo $group->name."<br />";
		}
		
		echo "<br />";
		echo (microtime(true)-$time) . ' seconds';
		
		/*// Call the primary init routines of all plugins, e.g. to assign event
		// handlers
		foreach (Plugin::getPlugins() as $plugin) {
			$plugin->primaryInit();
		}
		// Call the main init routines of all plugins 
		foreach (Plugin::getPlugins() as $plugin) {
			$plugin->init();
		}

		// If a user is logged in, note that it has made another request.
		if (Environment::getCurrent()->session)
			Environment::getCurrent()->session->hit();
			
		// Execute the request
		Environment::getCurrent()->pageNode->execute();*/
	}
}

Premanager::__init();

?>