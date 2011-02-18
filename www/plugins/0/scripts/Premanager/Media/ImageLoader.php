<?php
namespace Premanager\Media;

/**
 * An interface for classes to load images from file
 */
interface ImageLoader {
	/**
	 * Tries to load a file as an image
	 * 
	 * @param string $fileName the path to the file to load
	 * @return Premanager\Media\Image an image on success or null
	 */
	public function fromFile($fileName);
}

?>
