<?php
namespace Premanager\Execution;

use Premanager\Models\Plugin;

use Premanager\IO\Directory;

use Premanager\Module;
use Premanager\IO\FileNotFoundException;
use Premanager\ArgumentException;
use Premanager\IO\File;
use Premanager\IO\Config;

/**
 * A template with data
 */
class Template extends Module {
	/**
	 * @var Dwoo
	 */
	private $_dwoo;
	/**
	 * @var Dwoo_Data
	 */
	private $_data;
	/**
	 * @var string
	 */
	private $_path;
	/**
	 * @var Dwoo_Compiler
	 */
	private $_compiler;
	
	/**
	 * @var Dwoo_Compiler
	 */
	private static $_defaultCompiler;
	/**
	 * @var Dwoo_Compiler
	 */
	private static $_compilerWithoutCompressor;
	
	/**
	 * Creates a template with the file
	 * /plugins/{pluginName}/templates/{templateName}.tpl
	 * 
	 * @param string $pluginName the name of the plugin that stores the template
	 * @param string $templateName the file name without extension 
	 * @param bool $disableCompressor true, if the output should not be compressed
	 * @throws FileNotFoundException the file given by $pluginName and
	 *   $templateName does not exist
	 */
	public function __construct($pluginName, $templateName,
		$disableCompressor = false) {
		parent::__construct();
		
		$this->_path = Config::getPluginPathOf($pluginName).'/templates/'.
			$templateName.'.tpl';
		if (!File::exists($this->_path))
			throw new FileNotFoundException('The template file does not exist '.
				'(Plugin: '.$pluginName.' ('.$pluginID.'); Template: '.$templateName.
				')', $this->_path);
		
		require_once(Config::getPluginPathOf('Premanager').
			'/thirdparty/dwoo/dwooAutoload.php');
				
		$this->_dwoo = new \Dwoo();
		$this->_data = new \Dwoo_Data();
		$dwooPath = Config::getCachePathOf('Premanager').'/dwoo';
		Directory::createDirectory($dwooPath);
		$this->_dwoo->setCompileDir($dwooPath);
		
		// Select the compiler for this template
		$enableCompressing = !Config::isDebugMode() && !$disableCompressor;
		if ($enableCompressing)
			$this->_compiler =& self::$_defaultCompiler;
		else
			$this->_compiler =& self::$_compilerWithoutCompressor;
			
		// If that kind of compiler has not been used before, create it
		if (!$this->_compiler) {
			$this->_compiler = new \Dwoo_Compiler();
			
			if ($enableCompressing)
				$this->_compiler->addPreProcessor('compress', true);
		}
	}
		
	/**
	 * Sets a key-value pair of data
	 * 
	 * @param string $name the name
	 * @param mixed $value the value
	 */
	public function set($name, $value) {
		$this->_data->assign($name, $value);
	}
		
	/**
	 * Sets a key-value pair of data
	 * 
	 * @param string $name the name
	 * @param mixed $value the value
	 */
	public function __set($name, $value) {
		$this->set($name, $value);
	}
		
	/**
	 * Gets a value of the key-value pairs
	 * 
	 * @param string $name the name
	 * @return mixed the value
	 */
	public function getValue($name) {
		if ($this->_data->isAssigned($name))
			return $this->_data->get($name);
		else
			return null;
	}
		
	/**
	 * Gets a value of the key-value pairs
	 * 
	 * @param string $name the name
	 * @return mixed the value
	 */
	public function __get($name) {
		return $this->getValue($name);
	}
	
	/**
	 * Checks whether there is a data entry with the specified name
	 * .
	 * @param string $name the name to check whether existing or not
	 * @return boolean true, if this name exists, false if it does not
	 */
	public function __isset($name) {
		return $this->_data->isAssigned($name);
	}
	
	/**
	 * Clears all data set by set()
	 */
	public function clear() {
		$this->_data->clear();
	}
	
	/**
	 * Gets the template rendered with the data given by set()
	 * 
	 * @return string the rendered template content
	 */
	public function get() {
		$tpl = new \Dwoo_Template_File($this->_path); 
		return $this->_dwoo->get($tpl, $this->_data, $this->_compiler);
	}
}

?>
