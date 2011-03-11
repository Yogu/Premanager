<?php
namespace Premanager\Media;

use Premanager\Module;

/**
 * An abstract class for any image
 */
abstract class Image extends Module {
	private static $_loaders = array();

	/**
	 * Registers an image loader
	 *
	 * @param Premanager\MediaImageLoader $loader the image loader to register
	 */
	public static function registerLoader(ImageLoader $loader) {
		if (!array_search($loader, self::$_loaders))
			self::$_loaders[] = $loader;
	}

	/**
	 * Tries to load a file as image
	 *
	 * @param string $fileName the path to the file to load
	 * @return Premanager\Media\Image the image on success, or null if there is no
	 *   loader that can load this type of image
	 */
	public static function fromFile($fileName) {
		foreach (self::$_loaders as $loader) {
			$image = $loader->fromFile($fileName);
			if ($image instanceof Image)
				return $image;
		}
		return null;
	}

	// ===========================================================================

	/**
	 * Gets the width of this image in pixels
	 *
	 * @return int
	 */
	public abstract function getWidth();

	/**
	 * Gets the height of this image in pixels
	 *
	 * @return int
	 */
	public abstract function getHeight();

	/**
	 * Resizes to a exact size
	 *
	 * @param int $width the new width in pixels
	 * @param int $height the new height in pixels
	 */
	public abstract function resize($width, $height);

	/**
	 * Creates a file and writes this image into it (either as image/png or
	 * image/jpeg).
	 *
	 * @param string $fileName the path to the file to write in
	 */
	public abstract function saveToFile($fileName);

	/**
	 * Gets the mime type this picture is saved as
	 *
	 * Must be either image/png or image/jpeg. This is not required to be the
	 * original type of the loaded file.
	 *
	 * @return string
	 */
	public abstract function getType();

	/**
	 * Gets the file extension for the type this image is saved in (.png or .jpeg)
	 *
	 * @return string the file extension (including the dot)
	 */
	public function getFileExtension() {
		if ($this->getType() == 'image/jpeg')
			return '.jpeg';
		else
			return '.png';
	}

	/**
	 * Makes sure that the image is not larger than the specified size
	 *
	 * Scales the image down, if it is larger than $width or $height.
	 *
	 * @param int $width the maximum width
	 * @param int $height the maximum height
	 */
	public function resizeProportionally($width, $height) {
		$currentWidth = $this->getWidth();
		$currentHeight = $this->getHeight();

		if ($width && $currentWidth > $width) {
			$currentHeight = $width * $currentHeight / $currentWidth;
			$currentWidth = $width;
   		$resize = true;
		}

		if ($height && $currentHeight > $height) {
			$currentWidth = $height * $currentWidth / $currentHeight;
			$currentHeight = $height;
   		$resize = true;
		}

		if ($resize)
			$this->resize($currentWidth, $currentHeight);
	}
}

?>
