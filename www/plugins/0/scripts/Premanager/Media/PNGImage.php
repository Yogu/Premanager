<?php
namespace Premanager\Media;

/**
 * An image loaded and modified by the Graphics Draw library, saved as PNG file
 */
class PNGImage extends GDImage {     
	/**
	 * Creates a file and writes this image into it (either as image/png or
	 * image/jpeg).
	 *
	 * @param string $fileName the path to the file to write in
	 */
	public function saveToFile($fileName) {
		ImagePNG($this->getResource(), $fileName);	
	}
	          
	/**
	 * Gets the mime type this picture is saved as
	 * 
	 * Must be either image/png or image/jpeg. This is not required to be the
	 * original type of the loaded file.
	 *
	 * @return string
	 */
	public function getType() {
		return 'image/png';
	}
}

?>
