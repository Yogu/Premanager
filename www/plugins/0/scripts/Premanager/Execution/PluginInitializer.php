<?php
namespace Premanager\Execution;

/**
 * Provides two methods to initialize a plugin
 */
interface PluginInitializer {
	/**
	 * Is called in the first initializing loop. This method is for registering
	 * event handlers, for that they will be called when other plugins do
	 * something interesting in the main initializing method
	 */
	public function primaryInit();
	
	/**
	 * Is called in the second initializing loop
	 */
	public function init();
}