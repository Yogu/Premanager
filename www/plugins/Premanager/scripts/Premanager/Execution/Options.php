<?php
namespace Premanager\Execution;

use Premanager\Module;
use Premanager\ArgumentException;
use Premanager\IO\DataBase\DataBase;

/**
 * Provides access to the options
 */
class Options extends Module {
	/**
	 * @var array
	 */
	private $_values = array();
	/**
	 * @var Premanager\Execution\Environment
	 */
	private $_environment;
	/**
	 * @var bool
	 */
	private $_loaded;

	// ===========================================================================
	
	public function __construct(Environment $environment) {
		$this->_environment = $environment;
	}

	// ===========================================================================
	
	/**
	 * Gets the value of an option
	 * 
	 * @param string $plugin the name of the plugin that owns the option
	 * @param string $name the name of the option
	 * @return string the value of the option
	 */
	public function get($plugin, $name) {
		if (!$this->_loaded)
			$this->load();
		
		$value = $this->_values[$plugin][$option]; 
		if ($value === null)
			throw new ArgumentException('The accessed option ('.$plugin.'.'.$name.
				') does not exist');
		else
			return $value;	
	}	
	
	/**
	 * Gets the value of an option using the option set of the current environment
	 * 
	 * @param string $plugin the name of the plugin that owns the option
	 * @param string $name the name of the option
	 * @return string the value of the option
	 */
	public static function defaultGet($plugin, $name) {
		return Environment::getCurrent()->options->get($plugin, $name);	
	}	

	// ===========================================================================
	
	private function load() {
		// Load global values
		$result = DataBase::query(
			"SELECT plugin.name AS pluginName, optn.name AS optionName, ".
				"IFNULL(optn.globalValue, optn.defaultValue) AS value ".
			"FROM ".DataBase::formTableName('Premanager_Options')." optn ".
			"INNER JOIN ".DataBase::formTableName('Premanager_Plugins')." plugin ".
				"ON optn.pluginID = plugin.pluginID");
		while ($result->next()) {
			$pluginName = $result->get('pluginName');
			$optionName = $result->get('optionName');
			$value = $result->get('value');
			
			if (!array_key_exists($pluginName, self::$_values))
				self::$_values[$pluginName] = array();
				
			self::$_values[$pluginName][$optionName] = $value;		
		}
		
		// Load project values
		$result = DataBase::query(
			"SELECT plugin.name AS pluginName, optn.name AS optionName, ".
				"projectOption.value ".
			"FROM ".DataBase::formTableName('Premanager_ProjectOptions').
				" projectOption ".
			"INNER JOIN ".DataBase::formTableName('Premanager_Options')." optn ".
				"ON optn.optionID = projectOption.optionID ".
			"INNER JOIN ".DataBase::formTableName('Premanager_Plugins')." plugin ".
				"ON optn.pluginID = plugin.pluginID ".
			"WHERE projectOptions.projectID = ".
				$environment->project->id);
		while ($result->next()) {
			$pluginName = $result->get('pluginName');
			$optionName = $result->get('optionName');
			$value = $result->get('value');
				
			self::$_values[$pluginName][$optionName] = $value;		
		}
		
		// Load user values
		$result = DataBase::query(
			"SELECT plugin.name AS pluginName, optn.name AS optionName, ".
				"userOption.value ".
			"FROM ".DataBase::formTableName('Premanager_UserOptions').
				" userOption ".
			"INNER JOIN ".DataBase::formTableName('Premanager_Options')." optn ".
				"ON optn.optionID = userOption.optionID ".
			"INNER JOIN ".DataBase::formTableName('Premanager_Plugins')." plugin ".
				"ON optn.pluginID = plugin.pluginID ".
			"WHERE userOption.userID = ".
				$environment->me->id);
		while ($result->next()) {
			$pluginName = $result->get('pluginName');
			$optionName = $result->get('optionName');
			$value = $result->get('value');
				
			self::$_values[$pluginName][$optionName] = $value;		
		}
		
		$this->_loaded = true;
	}
}

?>