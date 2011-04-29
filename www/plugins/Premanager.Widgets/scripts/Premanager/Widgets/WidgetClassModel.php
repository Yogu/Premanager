<?php
namespace Premanager\Widgets;

use Premanager\Models\Plugin;
use Premanager\Models\StructureNode;
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
 * Provides a model descriptor for WidgetClass models
 */
class WidgetClassModel extends ModelDescriptor {
	private static $_instance;
	
	// ===========================================================================
	
	/**
	 * Loads the members calling addProperty()
	 */
	protected function loadMembers() {
		parent::loadMembers();
		
		$this->addProperty('widgetClass', WidgetClass::getDescriptor(),
			'getWidgetClass', 'widgetClassID');
		$this->addProperty('structureNode', StructureNode::getDescriptor(),
			'getStructureNode', 'nodeID');
		$this->addProperty('user', User::getDescriptor(), 'getUser', 'userID');
		$this->addProperty('order', DataType::NUMBER, 'getOrder', 'order');
		
		$this->addProperty('plugin', Plugin::getDescriptor(), 'getPlugin',
			'pluginID');
		$this->addProperty('className', DataType::STRING, 'getClassName', 'class');
		$this->addProperty('title', DataType::NUMBER, 'getTitle', '*title');
		$this->addProperty('description', DataType::NUMBER, 'getDescription',
			'*description');
	}
	
	// ===========================================================================
	
	/**
	 * Gets the single instance of Premanager\Models\WidgetClassModel
	 * 
	 * @return Premanager\Models\WidgetClassModel the single instance of this
	 *   class
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
		return 'Premanager\Widgets\WidgetClass';
	}
	
	/**
	 * Gets the name of the plugin containing the models
	 * 
	 * @return string
	 */
	public function getPluginName() {
		return 'Premanager.Widgets';
	}
	
	/**
	 * Gets the name of the model's table
	 * 
	 * @return string
	 */
	public function getTable() {
		return 'WidgetClasses';
	}
	
	/**
	 * Gets flags set for this model descriptor 
	 * 
	 * @return int (enum set Premanager\Modeling\ModelFlags)
	 */
	public function getFlags() {
		return ModelFlags::NO_NAME | ModelFlags::HAS_TRANSLATION;
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
	 * Creates a new widget class and inserts it into data base
	 *
	 * @param Premanager\Models\Plugin $plugin the plugin that registers this
	 *   widget class             
	 * @param string $className the class name for the widget class
	 * @param string $title a title
	 * @param string $description a short description
	 * @return Premanager\Widgets\WidgetClass
	 */
	public static function createNew(Plugin $plugin, $className, $title,
		$description)
	{
		$className = trim($className);
			
		if (!\class_exists($className))
			throw new ArgumentException('$className does not refer to an '.
				'existing class', 'className');
		
		// Check if the class extends WidgetClass
		$class = $className;
		while ($class != 'Premanager\Widgets\WidgetClass') {
			$class = get_parent_class($class);
			if (!$class)
				throw new ArgumentException('The class specified by $className '.
					'does not inherit from Premanager\Widgets\WidgetClass',
					'className');
		}
		
		return $this->createNewBase(
			array(
				'pluginID' => $plugin->getID(),
				'class' => $className),
			array(
				'title' => $title,
				'description' => $description
			)
		);
	}   
}
