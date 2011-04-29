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
 * Provides a model descriptor for style models
 */
class StyleModel extends ModelDescriptor {
	private static $_instance;
	
	// ===========================================================================
	
	/**
	 * Loads the members calling addProperty()
	 */
	protected function loadMembers() {
		parent::loadMembers();
		
		$this->addProperty('plugin', Plugin::getDescriptor(), 'getPlugin',
			'pluginID');
		$this->addProperty('path', DataType::STRING, 'getPath', 'path');
		$this->addProperty('title', DataType::STRING, 'getTitle', '*title');
		$this->addProperty('description', DataType::STRING, 'getDescription',
			'*description');
		$this->addProperty('author', DataType::STRING, 'getAuthor', '*author');
		$this->addProperty('isEnabled', DataType::BOOLEAN, 'isEnabled',
			'isEnabled');
		$this->addProperty('isDefault', DataType::BOOLEAN, 'isEnabled',
			'isDefault');
	}
	
	// ===========================================================================
	
	/**
	 * Gets the single instance of Premanager\Models\StyleModel
	 * 
	 * @return Premanager\Models\StyleModel the single instance of this class
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
		return 'Premanager\Models\Style';
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
		return 'Styles';
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
	 * Creates a new style class and inserts it into data base
	 *
	 * @param Plugin $plugin the plugin that registers this style class             
	 * @param string $path the path to style.xml file, relative to the plugin's
	 *   static directory
	 * @param string $title a display title
	 * @param string $description a short description
	 * @param string $author the style's author(s)
	 * @return Premanager\Models\Style
	 */
	public static function createNew(Plugin $plugin, $path, $title, $description,
		$author)
	{
		$path = trim($path);
		
		$fileName = Config::getStaticPath() . '/' . $plugin->getName() . '/'.$path;
		if (!File::exists($fileName))
			throw new FileNotFoundException('The style file does not exist (file '.
				'name: '.$fileName);
			
		return $this->createNewBase(
			array(
				'pluginID' => $plugin->getID(),
				'path' => $path,
				'author' => $author,
			),
			array(
				'title' => $title,
				'description' => $description
			)
		);
	}    
}

