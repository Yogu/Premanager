<?php
namespace Premanager\Widgets;

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
 * Provides a model descriptor for widget models
 */
class WidgetModel extends ModelDescriptor {
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
	}
	
	// ===========================================================================
	
	/**
	 * Gets the single instance of Premanager\Models\WidgetModel
	 * 
	 * @return Premanager\Models\WidgetModel the single instance of this class
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
		return 'Premanager\Widgets\Widget';
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
		return 'Widgets';
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
	 * Instanciates the model class and assigns the id
	 * 
	 * @param $id the id to assign
	 * @return Premanager\Modeling\Model the created instance
	 */
	protected function createFromID($id) {
		// Get widget class
		$result = DataBase::query(
			"SELECT widgetClass.class ".
			"FROM ".DataBase::formTableName('Premanager.Widgets', 'WidgetClasses').
			" AS widgetClass ".
			"INNER JOIN ".DataBase::formTableName('Premanager.Widgets', 'Widgets').
			" AS widget ON widget.widgetClassID = widgetClass.id ".
			"WHERE widget.id = '$id'");
		if (!$result->next())
			return null;
		$class = $result->get('class');
		
		$instance = new $class($id, false);
		return $instance;
	} 
}

