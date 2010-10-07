<?php
namespace Premanager\IO;

use Premanager\Models\Plugin;

use Premanager\URL;

class Config {
	private static $_rootPath;
	private static $_dataBaseHost;
	private static $_dataBaseUser;
	private static $_dataBasePassword;
	private static $_dataBaseName;
	private static $_dataBasePrefix;
	private static $_storePath;
	private static $_cachePath;
	private static $_staticPath;
	private static $_urlTemplate;
	private static $_emptyURLPrefix;
	private static $_staticURLPrefix;
	private static $_useWWW;
	private static $_securityCode;
	private static $_varDumpReturnsHTML;
	private static $_isLoginDisabled;
	private static $_isDebugMode;
	
	/**
	 * Gets the root path of this premanager installation (without trailing slash)
	 * 
	 * The root path contains at least the sub-folders "plugins" and "config".
	 * 
	 * @return string
	 */
	public static function getRootPath() {
		if (!self::$_rootPath) {
			$path = __DIR__;
			$numFoldersUp = 5;
			for ($i = \strlen($path)-1; $i--; $i >= 0) {
				if ($path[$i] == '\\' || $path[$i] == '/')
					$numFoldersUp--;
				if ($numFoldersUp == 0)
					break;
			}
	
			self::$_rootPath = \substr($path, 0, $i);
		}
		
		return self::$_rootPath;
	}
	
	/**
	 * Gets the path to the folder that contains all plugins (without trailing
	 * slash)
	 * 
	 * @return string
	 */
	public static function getPluginPath() {
		return self::getRootPath().'/plugins';
	}
	
	/**
	 * Gets the path to the plugin folder specified by the plugin name (without
	 * trailing slash)
	 * 
	 * @return string
	 */
	public static function getPluginPathOf($pluginName) {
		return self::getPluginPath().'/'.Plugin::getIDFromName($pluginName);
	}
	
	/**
	 * Gets the path to the folder that contains configuration files (without
	 * trailing slash)
	 * 
	 * @return string
	 */
	public static function getConfigPath() {
		return self::getRootPath().'/config';
	}
	
	/**
	 * Gets the path to the store folder (without trailing slash)
	 * 
	 * @return string
	 */
	public static function getStorePath() {
		if (self::$_storePath === null)
			self::loadFromFile();
		return self::$_storePath;
	}
	
	/**
	 * Gets the path to the store folder of the plugin specified by its name
	 * (without trailing slash)
	 * 
	 * @return string
	 */
	public static function getStorePathOf($pluginName) {
		return self::getStorePath().'/'.Plugin::getIDFromName($pluginName);
	}
	
	/**
	 * Gets the path to the cache folder (without trailing slash)
	 * 
	 * @return string
	 */
	public static function getCachePath() {
		if (self::$_cachePath === null)
			self::loadFromFile();
		return self::$_cachePath;
	}
	
	/**
	 * Gets the path to the cache folder of the plugin specified by its name
	 * (without trailing slash)
	 * 
	 * @return string
	 */
	public static function getCachePathOf($pluginName) {
		return self::getCachePath().'/'.Plugin::getIDFromName($pluginName);
	}
	
	/**
	 * Gets the path to the static folder (without trailing slash)
	 * 
	 * @return string
	 */
	public static function getStaticPath() {
		if (self::$_staticPath === null)
			self::loadFromFile();
		return self::$_staticPath;
	}
	
	/**
	 * Specifies whether the login function is disabled
	 * 
	 * @return bool
	 */
	public static function isLoginDisabled() {
		if (self::$_isLoginDisabled === null) {
			self::$_isLoginDisabled =
				File::exists(self::getConfigPath().'/disablelogin');
		}
		return self::$_isLoginDisabled;
	}
	
	/**
	 * Specifies whether the debug mode is enabled
	 * 
	 * @return bool
	 */
	public static function isDebugMode() {
		if (self::$_isDebugMode === null) {
			self::$_isDebugMode =
				File::exists(self::getConfigPath().'/debug');
		}
		return self::$_isDebugMode;
	}
	
	/**
	 * Gets the host name of the server for default data base connection
	 * 
	 * @return string
	 */
	public static function getDataBaseHost() {
		if (self::$_dataBaseHost === null)
			self::loadFromFile();
		return self::$_dataBaseHost;
	}
	
	/**
	 * Gets the user name for default data base connection
	 * 
	 * @return string
	 */
	public static function getDataBaseUser() {
		if (self::$_dataBaseUser === null)
			self::loadFromFile();
		return self::$_dataBaseUser;
	}
	
	/**
	 * Gets the password for default data base connection
	 * 
	 * @return string
	 */
	public static function getDataBasePassword() {
		if (self::$_dataBasePassword === null)
			self::loadFromFile();
		return self::$_dataBasePassword;
	}
	
	/**
	 * Gets the data base name for default data base connection
	 * 
	 * @return string
	 */
	public static function getDataBaseName() {
		if (self::$_dataBaseName === null)
			self::loadFromFile();
		return self::$_dataBaseName;
	}
	
	/**
	 * Gets the table prefix for default data base connection
	 * 
	 * @return string
	 */
	public static function getDataBasePrefix() {
		if (self::$_dataBasePrefix === null)
			self::loadFromFile();
		return self::$_dataBasePrefix;
	}
	
	/**
	 * Gets the template for common urls
	 * 
	 * @return string
	 */
	public static function getURLTemplate() {
		if (self::$_urlTemplate === null)
			self::loadFromFile();
		return self::$_urlTemplate;
	}
	
	/**
	 * Gets the prefix for urls without language, project or edition information
	 * 
	 * @return string
	 */
	public static function getEmptyURLPrefix() {
		if (self::$_urlTemplate === null)
			self::loadFromFile();
		if (self::$_emptyURLPrefix === null)
			self::$_emptyURLPrefix = URL::fromTemplateUsingStrings('', '', '');
		return self::$_emptyURLPrefix;
	}
	
	/**
	 * Gets the prefix for static urls
	 * 
	 * @return string
	 */
	public static function getStaticURLPrefix() {
		if (self::$_staticURLPrefix === null)
			self::loadFromFile();
		return self::$_staticURLPrefix;
	}
	
	/**
	 * Specifies whether the www subdomain to be used
	 * 
	 * @return bool
	 */
	public static function getUseWWW() {
		if (self::$_useWWW === null)
			self::loadFromFile();
		return self::$_useWWW;
	}
	
	/**
	 * Gets the security code
	 * 
	 * @return string
	 */
	public static function getSecurityCode() {
		if (self::$_securityCode === null)
			self::loadFromFile();
		return self::$_securityCode;
	}
	
	/**
	 * Specifies whether var_dump() returns HTML code instead of plain text
	 * 
	 * @return string
	 */
	public static function getVarDumpReturnsHTML() {
		if (self::$_securityCode === null)
			self::loadFromFile();
		return self::$_securityCode;
	}
	
	private static function loadFromFile() {
  	// Load configuration from INI file
  	$fileName = self::getConfigPath().'/config.ini'; 
  	if (!File::exists($fileName))
  		throw new CorruptDataException('Config file is missing (Path: '.
  			$fileName.')');
  			
  	$ini = \parse_ini_file($fileName, true);
  	if ($ini === false)
  		throw new CorruptDataException('Config file is not a vaild ini file '.
  			'(Path: '.$fileName.')');

  	// Data Base
  	self::$_dataBaseHost = $ini['DataBase']['Host'];
		self::$_dataBaseName = $ini['DataBase']['DataBase'];
		self::$_dataBaseUser = $ini['DataBase']['User'];
		self::$_dataBasePassword = $ini['DataBase']['Password'];
		self::$_dataBasePrefix = $ini['DataBase']['Prefix'];

		// File System
		self::$_storePath = self::getRootPath().'/'.
			rtrim($ini['FileSystem']['StorePath'], '/');
		self::$_cachePath = self::getRootPath().'/'.
			rtrim($ini['FileSystem']['CachePath'], '/');
		self::$_staticPath = self::getRootPath().'/'.
			rtrim($ini['FileSystem']['StaticPath'], '/');

		if (!Directory::exists(self::$_storePath))
			throw new CorruptDataException('Store directory does not exist');
		if (!Directory::exists(self::$_cachePath))
			throw new CorruptDataException('Cache directory does not exist');
		if (!Directory::exists(self::$_staticPath))
			throw new CorruptDataException('Static directory does not exist');
		
		// URL
		self::$_urlTemplate = $ini['URL']['Template'];
		self::$_staticURLPrefix = rtrim($ini['URL']['StaticPrefix'], '/').'/';
		self::$_useWWW = $ini['URL']['UseWWW'] == 'true';  
		
		// Security
		self::$_securityCode = \hash('sha256',
			'c4151b76f0f33f33be99efd94fac69511e7ac04848193fa4c5f5c17d9c113f5f'.
			$ini['Security']['Code']);
			
		// PHP
		self::$_varDumpReturnsHTML = $ini['PHP']['VarDumpReturnsHTML'] == 'true';    
	}
}