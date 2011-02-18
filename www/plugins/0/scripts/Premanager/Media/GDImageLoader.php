<?php
namespace Premanager\Media;

use Premanager\Module;

/**
 * An interface for classes to load images from file
 */
class GDImageLoader extends Module implements ImageLoader {
	/**
	 * Tries to load a file as an image
	 * 
	 * @param string $fileName the path to the file to load
	 * @return Premanager\Media\Image an image on success or null
	 */
	public function fromFile($fileName) {
		$info = GetImageSize($fileName);     
		$imageType = $info[2];  
		switch ($imageType) {
			case \IMAGETYPE_GIF:
				$res = imagecreatefromgif($fileName);
				break;
				
			case \IMAGETYPE_JPEG:
			case \IMAGETYPE_JPEG2000:
				$res = imagecreatefromjpeg($fileName);
				$jpeg = true;
				break;
				
			case \IMAGETYPE_PNG:
				$res = imagecreatefrompng($fileName);
				break;
				
			case \IMAGETYPE_WBMP:
				$res = imagecreatefromwbmp($fileName);
				break;
				
			case \IMAGETYPE_XBM:
				$res = imagecreatefromwxpm($fileName);
				break;
		}
		
		if ($res) {
			if ($jpeg)
				return new JPEGImage($res);
			else
				return new PNGImage($res);
		}  
	}
}

?>
