<?php
namespace Premanager;

use Premanager\Debug\Debug;

use Premanager\Media\GDImageLoader;
use Premanager\Media\Image;
use Premanager\Media\ImageLoader;
use Premanager\Execution\PluginInitializer;

/**
 * Provides two methods to initialize a plugin
 */
class Initializer extends Module implements PluginInitializer {
	/**
	 * Is called in the first initializing loop. This method is for registering
	 * event handlers, for that they will be called when other plugins do
	 * something interesting in the main initializing method
	 */
	public function primaryInit() {
		
	}
	
	/**
	 * Is called in the second initializing loop
	 */
	public function init() {
		Image::registerLoader(new GDImageLoader());
	}
}