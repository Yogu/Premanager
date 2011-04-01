<?php
namespace Premanager\Models;

use Premanager\IO\DataBase\DataBase;
use Premanager\Models\Right;
use Premanager\ArgumentException;
use Premanager\Types;

/**
 * A right
 */
class Right {
	private $_id;
	/**
	 * @var Premanager\Models\Plugin
	 */
	private $_plugin;
	private $_name;
	private $_title;
	private $_description;
	private $_scope;
	
	/**
	 * @var array
	 */
	private static $_rights;
	private static $_rightsByName;
	
	private function __construct($id, $plugin, $name, $title, $description,
		$scope) {
		$this->_id = $id;
		$this->_plugin = $plugin;
		$this->_name = $name;
		$this->_title = $title;
		$this->_description = $description;
		$this->_scope = $scope;
	}
	
	/**
	 * Gets a list of all rights
	 * 
	 * array(pluginName => array(rightName))
	 * 
	 * @return array an array with plugin names as keys and arrays of righ names
	 *   as values
	 */
	public static function getSimpleList() {
		static $cache;
		if ($cache === null) {
			$cache = array();
			
			$result = DataBase::query(
				"SELECT rght.name, plugin.name AS pluginName ".
				"FROM ".DataBase::formTableName('Premanager', 'Rights')." AS rght ". 
				"INNER JOIN ".DataBase::formTableName('Premanager', 'Plugins')." ".
					"AS plugin ".
					"ON plugin.id = rght.pluginID ".
				"ORDER BY plugin.id = 0 DESC, ".
					"plugin.name ASC, ".
					"rght.name ASC");
			while ($result->next()) {
				$plugin = $result->get('pluginName');
				$right = $result->get('name');
	
				if (!Types::isArray($cache[$plugin]))
					$cache[$plugin] = array();
	
				$cache[$plugin][$right] = true;
			}
		}
		return $cache;
	}
	
	/**
	 * Gets a list of all rights
	 * 
	 * @return array array of Premanager\Model\Right objects
	 */
	public static function getRights() {
		if (self::$_rights === null) {
			self::$_rights = array();
			self::$_rightsByName = array();
			
			$result = DataBase::query(
				"SELECT rght.id, rght.pluginID, rght.name, translation.title, ".
					"translation.description, rght.scope ".
				"FROM ".DataBase::formTableName('Premanager', 'Rights')." AS rght ". 
				"INNER JOIN ".DataBase::formTableName('Premanager', 'Plugins')." ".
					"AS plugin ".
					"ON plugin.id = rght.pluginID ",
				/* translating */
				"ORDER BY plugin.id = 0 DESC, ".
					"plugin.name ASC, ".
					"translation.title ASC");
			while ($result->next()) {
				switch ($result->get('scope')) {
					case 'projects':
						$scope = Scope::PROJECTS;
						break;
					case 'organization':
						$scope = Scope::ORGANIZATION;
						break;
					default:
						$scope = Scope::BOTH;
				}
				$right = new Right($result->get('id'),
					Plugin::getByID($result->get('pluginID')), $result->get('name'),
					$result->get('title'), $result->get('description'), $scope);
				self::$_rights[$result->get('id')] = $right;
				self::$_rightsByName[$right->_plugin->getName().'/'.$right->_name] =
					$right;
			}
		}
		return self::$_rights;
	}
	
	/**
	 * Gets a right using its id
	 * 
	 * @param int $id the id
	 * @return Premanager\Models\Right the right or null, if there is no
	 *   right with the specified id
	 */
	public static function getByID($id) {
		$id = (int)$id;
			
		$rights = self::getRights();
		return $rights[$id];
	}
	
	/**
	 * Gets a right using its name and the name of its plugin. Case sensitive.
	 * 
	 * @param string $pluginName the name of the plugin that contains the right
	 * @param string $rightName the name of the right
	 * @return Premanager\Models\Right the right or null, if there is no
	 *   right with the specified name
	 */
	public static function getByName($pluginName, $rightName) {
		self::getRights();
		return self::$_rightsByName[$pluginName.'/'.$rightName];
	}
	
	/**
	 * Gets the id of this right
	 * 
	 * @return int
	 */
	public function getID() {
		return $this->_id;
	}
	
	/**
	 * Gets the plugin that has registered this right
	 * 
	 * @return Premanager\Models\Plugin
	 */
	public function getPlugin() {
		return $this->_plugin;
	}
	
	/**
	 * Gets the scope
	 * 
	 * @return int (enum Premanager\Models\Scope
	 */
	public function getScope() {
		return $this->_scope;
	}
	
	/**
	 * Gets the name that is used for scripts
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}
	
	/**
	 * Gets the display title
	 * 
	 * @return string
	 */
	public function getTitle() {
		return $this->_title;
	}
	
	/**
	 * Gets a short description
	 * 
	 * @return string
	 */
	public function getDescription() {
		return $this->_description;
	}
}

?>
