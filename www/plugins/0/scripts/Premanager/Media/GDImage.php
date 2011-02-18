<?php
namespace Premanager\Media;

/**
 * An image loaded and modified by the Graphics Draw library  
 */
abstract class GDImage extends Image {
	/**
	 * @var resource
	 */
	private $_resource;
	/**
	 * @var int
	 */
	private $_width;
	/**
	 * @var int
	 */
	private $_height;
	
	// ===========================================================================
	
	/**
	 * Creates a new GDImage using a resource
	 * 
	 * @param resource $resource the resource of the GDImage
	 */
	public function __construct($resource) {
		parent::__construct();
		
		
		$this->_resource = $resource;	   
		imagesavealpha($this->_resource, true);
		$this->_width = imagesx($this->_resource);
		$this->_height = imagesy($this->_resource);
	}
	
	// ===========================================================================
	
	/**
	 * Gets the width of this image in pixels
	 *
	 * @return int
	 */
	public function getWidth() {
		return $this->_width;
	}
	
	/**
	 * Gets the height of this image in pixels
	 *
	 * @return int
	 */
	public function getHeight() {
		return $this->_height;
	}       
	    
	/**
	 * Resizes to a exact size
	 *
	 * @param int $width the new width in pixels
	 * @param int $height the new height in pixels
	 */
	public function resize($width, $height) {
		
		$gdVersion = self::getGDVersion();
		
		if ($gdVersion >=2 ) {
			$resource = imagecreatetruecolor($width, $height);   
			imagealphablending($resource, false);
			$color = imagecolortransparent($resource,
				imagecolorallocatealpha($resource, 0, 0, 0, 127));
			imagefill($resource, 0, 0, $color);       
			imagesavealpha($resource, true);
		} else
			$resource = imagecreate($width, $height);

		if ($gdVersion >=2) 
			imagecopyresampled($resource, $this->_resource, 0, 0, 0, 0,
				$width, $height, $this->_width, $this->_height);
		else
			imagecopyresized($resource, $this->resource, 0, 0, 0, 0,
				$width, $height, $this->_width, $this->_height);
		      
     imagedestroy($this->_resource);
     $this->_resource = $resource;
     $this->_width = $width;
     $this->_height = $height;
	}
	      
	/**
	 * Creates a file and writes this image into it (either as image/png or
	 * image/jpeg).
	 *
	 * @param string $fileName the path to the file to write in
	 */
	public function saveToFile($fileName) {
		ImagePNG($this->_resource, $fileName);	
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

	// ===========================================================================
	
	/**
	 * Gets the GD resource
	 * 
	 * @return resource the GD resource
	 */
	protected function getResource() {
		return $this->_resource;
	}

	// ===========================================================================
	
	private static function getGDVersion() {
		static $gdversion;
		if ($gdversion === null) {
			$ver_info = gd_info();
			preg_match('/\d/', $ver_info['GD Version'], $match);
			$gdversion = $match[0];
		}
		return $gdversion;
	}
}

?>
