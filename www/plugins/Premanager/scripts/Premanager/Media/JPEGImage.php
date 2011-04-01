<?php
namespace Premanager\Media;

/**
 * An image loaded and modified by the Graphics Draw library, saved as JPEG file
 */
class JPEGImage extends GDImage {
	/**
	 * Quality for saved jpeg files
	 * 
	 * @var int the quality from 0 to 100
	 */
	const QUALITY = 95;
	
	/**
	 * Creates a file and writes this image into it (either as image/png or
	 * image/jpeg).
	 *
	 * @param string $fileName the path to the file to write in
	 */
	public function saveToFile($fileName) {
		ImageJPEG($this->getResource(), $fileName, self::QUALITY);	
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
		return 'image/jpeg';
	}
}

?>
