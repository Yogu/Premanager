<?php
namespace Premanager\IO;

/**
 * Provides information about files in the local file system
 */
use Premanager\ArgumentNullException;

class File {
	/**
	 * Checks whether a file exists
	 * 
	 * @param string $path the path to the file
	 * @return bool true, if the file exists
	 */
	public static function exists($path) {
		return \file_exists($path) && \is_file($path);
	}
	
	/**
	 * Deletes a file
	 *
	 * @param string $path the path to the file to be deleted 
	 */
	public static function delete($path) {
		if ($path === null)
			throw new ArgumentNullException('fileName');
		if (!self::exists($path))
			throw new FileNotFoundException('Tried to delete a file which does not'.
				'exist', $path);
		if (!@\unlink($path))
			throw new IOException('Failed to delete file \''.$fileName.'\'');
	}
}

?>