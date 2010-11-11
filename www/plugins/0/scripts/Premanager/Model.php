<?php 
namespace Premanager;

/**
 * Defines a model
 * 
 * Should implement the static function getDescriptor()
 */
abstract class Model extends Module {
	/**
	 * Gets the id of this model
	 *
	 * @return int
	 */
	public abstract function getID();
}

?>
