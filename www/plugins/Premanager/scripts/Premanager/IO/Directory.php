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
	
	/**
	 * Creates directories and sub-directories as specified in the path
	 * 
	 * @param string $path path to the directory create
	 */
	public static function createDirectory($path) {
		if (!self::exists($path))
			mkdir($path, 0777, true);
	}
}

?>