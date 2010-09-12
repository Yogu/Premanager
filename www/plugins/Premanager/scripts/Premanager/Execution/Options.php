<?php
namespace Premanager\Execution;

use Premanager\Module;
use Premanager\ArgumentException;
use Premanager\IO\DataBase\DataBase;
use Premanager\Debug\Debug;

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
	/**
	 * @var bool
	 */
	private $_projectValuesLoaded;
	/**
	 * @var bool
	 */
	private $_userValuesLoaded;

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
	 * @param bool $skipUserOptions if true, user options will not be loaded (note
	 *   that if they are already loaded, changes made by them will not be undone)
	 * @return string the value of the option
	 */
	public function get($plugin, $name, $skipUserOptions = false) {
		$this->autoLoad();
		
		if (!isset($this->_values[$plugin]) ||
			!isset($this->_values[$plugin][$name]))
			throw new ArgumentException('The accessed option ('.$plugin.'.'.$name.
				') does not exist');
		else
			return $this->_values[$plugin][$name];	
	}	
	
	/**
	 * Gets the value of an option using the option set of the current environment
	 * 
	 * @param string $plugin the name of the plugin that owns the option
	 * @param string $name the name of the option
	 * @param bool $skipUserOptions if true, user options will not be loaded (note
	 *   that if they are already loaded, changes made by them will not be undone)
	 * @return string the value of the option
	 */
	public static function defaultGet($plugin, $name, $skipUserOptions = false) {
		return Environment::getCurrent()->options->get($plugin, $name,
			$skipUserOptions);	
	}	

	// ===========================================================================
	
	/**
	 * Loads the option structure and global option values
	 */
	private function load() {
		// Load global values
		$result = DataBase::query(
			"SELECT plugin.name AS pluginName, optn.name AS optionName, ".
				"IFNULL(optn.globalValue, optn.defaultValue) AS value ".
			"FROM ".DataBase::formTableName('Premanager_Options')." AS optn ".
			"INNER JOIN ".DataBase::formTableName('Premanager_Plugins')." AS plugin ".
				"ON optn.pluginID = plugin.id");
		while ($result->next()) {
			$pluginName = $result->get('pluginName');
			$optionName = $result->get('optionName');
			$value = $result->get('value');
			
			if (!array_key_exists($pluginName, $this->_values))
				$this->_values[$pluginName] = array();
				
			$this->_values[$pluginName][$optionName] = $value;		
		}
		
		$this->_loaded = true;
	}
	
	/**
	 * Loads the option values defined by project
	 */
	private function loadProjectOptions() {
		Debug::assert($this->_loaded, 'Tried to load option values defined by '.
			'project, but global option values have not been loaded yet');
		
		$result = DataBase::query(
			"SELECT plugin.name AS pluginName, optn.name AS optionName, ".
				"projectOption.value ".
			"FROM ".DataBase::formTableName('Premanager_ProjectOptions')." AS ".
				"projectOption ".
			"INNER JOIN ".DataBase::formTableName('Premanager_Options')." AS optn ".
				"ON optn.id = projectOption.optionID ".
			"INNER JOIN ".DataBase::formTableName('Premanager_Plugins')." AS plugin ".
				"ON optn.pluginID = plugin.id ".
			"WHERE projectOption.projectID = ".
				$this->_environment->project->id);
		while ($result->next()) {
			$pluginName = $result->get('pluginName');
			$optionName = $result->get('optionName');
			$value = $result->get('value');
				
			$this->_values[$pluginName][$optionName] = $value;		
		}
		
		$this->_projectValuesLoaded = true;
	}
	
	/**
	 * Loads the option values defined by user
	 */
	private function loadUserOptions() {
		Debug::assert($this->_loaded, 'Tried to load option values defined by '.
			'user, but global option values have not been loaded yet');
		
		$result = DataBase::query(
			"SELECT plugin.name AS pluginName, optn.name AS optionName, ".
				"userOption.value ".
			"FROM ".DataBase::formTableName('Premanager_UserOptions')." AS ".
				" userOption ".
			"INNER JOIN ".DataBase::formTableName('Premanager_Options')." AS optn ".
				"ON optn.id = userOption.optionID ".
			"INNER JOIN ".DataBase::formTableName('Premanager_Plugins')." AS plugin ".
				"ON optn.pluginID = plugin.id ".
			"WHERE userOption.userID = ".
				$this->_environment->me->id);
		while ($result->next()) {
			$pluginName = $result->get('pluginName');
			$optionName = $result->get('optionName');
			$value = $result->get('value');
				
			$this->_values[$pluginName][$optionName] = $value;		
		}
		
		$this->_userValuesLoaded = true;
	}
	
	/**
	 * Loads that values that are not already loaded, if they are available
	 */
	private function autoLoad() {
		if (!$this->_loaded)
			$this->load();
		if (!$this->_projectValuesLoaded &&
			$this->_environment->isProjectAvailable())
			$this->loadProjectOptions();
		if (!$this->_userValuesLoaded && $this->_environment->isMeAvailable())
			$this->loadUserOptions();
	}
}

?>