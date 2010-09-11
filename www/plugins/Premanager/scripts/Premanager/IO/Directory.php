<?php
namespace Premanager\IO;

/**
 * Provides information about directories in the local file system
 */
class Directory {
	/**
	 * Checks whether a directories exists
	 * 
	 * @param string $path the path to the directory
	 * @return bool true, if the directory exists
	 */
	public static function exists($path) {
		return \file_exists($path) && \is_dir($path);
	}
}

?>