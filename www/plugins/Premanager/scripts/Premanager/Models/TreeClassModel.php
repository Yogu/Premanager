<?php
namespace Premanager\Models;

use Premanager\Modeling\ModelDescriptor;
use Premanager\Modeling\DataType;
use Premanager\IO\DataBase\QueryBuilder;
use Premanager\NotSupportedException;
use Premanager\IO\DataBase\DataBase;
use Premanager\Pages\AddGroupHomePage;
use Premanager\Models\User;
use Premanager\Modeling\ModelFlags;
use Premanager\InvalidOperationException;
use Premanager\ArgumentException;
use Premanager\Module;

/**
 * Provides a model descriptor for tree class models
 */
class TreeClassModel extends ModelDescriptor {
	private static $_instance;
	
	// ===========================================================================
	
	/**
	 * Loads the members calling addProperty()
	 */
	protected function loadMembers() {
		parent::loadMembers();
	
		$this->addProperty('plugin', Plugin::getDescriptor(), 'getPlugin',
			'pluginID');
		$this->addProperty('className', DataType::STRING, 'getClassName',
			'class');
		$this->addProperty('scope', DataType::NUMBER, 'getScope',
			"CASE !scope! WHEN 'organization' THEN 0 WHEN 'projects' THEN 1 ".
			"ELSE 2 END");
	}
	
	// ===========================================================================
	
	/**
	 * Gets the single instance of Premanager\Models\TreeClassModel
	 * 
	 * @return Premanager\Models\TreeClassModel the single instance of this class
	 */
	public static function getInstance() {
		if (self::$_instance === null)
			self::$_instance = new self();
		return self::$_instance;
	}
	
	/**
	 * Gets the name of the class this descriptor describes
	 * 
	 * @return string
	 */
	public function getClassName() {
		return 'Premanager\Models\TreeClass';
	}
	
	/**
	 * Gets the name of the plugin containing the models
	 * 
	 * @return string
	 */
	public function getPluginName() {
		return 'Premanager';
	}
	
	/**
	 * Gets the name of the model's table
	 * 
	 * @return string
	 */
	public function getTable() {
		return 'Trees';
	}
	
	/**
	 * Gets flags set for this model descriptor 
	 * 
	 * @return int (enum set Premanager\Modeling\ModelFlags)
	 */
	public function getFlags() {
		return ModelFlags::NO_NAME;
	}
	
	/**
	 * Gets an SQL expression that determines the name group for an item (alias
	 * for item table is 'item')
	 * 
	 * @return string an SQL expression
	 */
	public function getNameGroupSQL() {
		return '';
	}

	/**
	 * Creates a new tree class and inserts it into data base
	 *
	 * @param Premanager\Models\Plugin $plugin the plugin that registers this
	 *   tree class             
	 * @param string $className the class name for the tree
	 * @param int $scope enum Premanager\Models\Scope
	 * @param string $key a unique string to identify the tree class within plugin
	 *   context
	 * @return Premanager\Models\TreeClass
	 */
	public static function createNew(Plugin $plugin, $className, $scope, $key) {
		$className = trim($className);
		
		if (!$plugin)
			throw new ArgumentNullException('plugin');
			
		if (!\class_exists($className))
			throw new ArgumentException('$className does not refer to an '.
				'existing class', 'className');
		
		// Check if the class extends PageNode
		$class = $className;
		while ($class != 'Premanager\Execution\PageNode') {
			$class = get_parent_class($class);
			if (!$class)
				throw new ArgumentException('The class specified by $className '.
					'does not inherit from Premanager\Execution\PageNode', 'className');
		}
		
		// Check if the key already exists
		if ($key && self::getByKey($plugin->getName(), $key))
			throw new ArgumentException('The key \''.$key.'\' already exists for '.
				'plugin '.$plugin->getName(), 'key');
	
		switch ($scope) {
			case Scope::BOTH:
				$dbScope = 'both';
				break;
			case Scope::ORGANIZATION:
				$dbScope = 'organization';
				break;
			case Scope::PROJECTS:
				$dbScope = 'projects';
				break;
			default:
				throw new InvalidEnumArgumentException('scope', $scope,
					'Premanager\Scope');
		}
		
		return $this->createNewBase(
			array(
				'class' => $className,
				'scop' => $dbScope
			),
			array());
	}
}

