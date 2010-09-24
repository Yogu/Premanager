<?php
namespace Premanager\Execution;

use Premanager\Module;

/**
 * Defines a style
 */
abstract class Style extends Module {
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Gets an array of stylesheets this style uses in the current environment
	 * 
	 * @return array of associative arrays (url: absolute url; fileName: absolute
	 *   path and file name; media: e. g. 'all' or 'print' (if 'all' is used, can
	 *   be omitted)
	 */
	public abstract function getStylesheets();
}

?>
