<?php
namespace Premanager\Execution;

use Premanager\DateTime;
use Premanager\Debug\Debug;
use Premanager\Module;
use Premanager\IO\DataBase\DataBase;
use Premanager\ArgumentException;
use Premanager\Strings;

/**
 * Provides access to translation strings
 */
class Translation extends Module {
	/**
	 * @var Premanager\Execution\Environment
	 */
	private $_environment;
	/**
	 * A two-dimensional array grouped by plugin name and string name
	 * @var array
	 */
	private $_values = array();
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
	 * Gets a translation string by its name and the name of the owning plugin
	 * 
	 * @param string $pluginName the name of the plugin that owns the string
	 * @param string $stringName the name of the translation string
	 * @param array $params an optional array of parameters
	 * @return string the value of the translation string
	 */
	public function get($pluginName, $stringName, array $params = array()) {
		if (!$this->_loaded)
			$this->load();
		
		if (!array_key_exists($pluginName, $this->_values) ||
			!array_key_exists($stringName, $this->_values[$pluginName]))
			throw new ArgumentException('Accessed translation string ('.
				$pluginName.'.'.$stringName.') does not exist');
				
		return
			$this->formatString($this->_values[$pluginName][$stringName], $params);
	}
	
	/**
	 * Gets a translation string using the translation of the current environment
	 * 
	 * @param string $pluginName the name of the plugin that owns the string
	 * @param string $stringName the name of the translation string
	 * @param array $params an optional array of parameters
	 * @return string the value of the translation string
	 */
	public static function defaultGet($pluginName, $stringName,
		array $params = array()) {
		return Environment::getCurrent()->gettranslation()->get($pluginName, $stringName,
			$params);
	}
	
	/**
	 * Formats a translation string
	 * 
	 * @param string $value the translation string (a pattern)
	 * @param array $params optional array of parameters
	 * @return string the formatted string 
	 */
	public function formatString($value, array $params = array()) {
		// Split $value into plain-text and expression parts
		$parts = \preg_split('/[\{\}]/', $value);
		$isExpression = false;
		$result = '';
		foreach ($parts as $part) {
			if (!$isExpression)
				$value = $part;
			else {
				if ($part[0] == '\'') {
					$part = substr($part, 1);
					if (($pos = strpos($part, '\'')) === false)
						$pos = strpos($part, ' ');
					$value = substr($part, 0, $pos);
					$part = substr($part, $pos+1);
					$modifiers = explode(' ', $part);
				} else {
					$modifiers = explode(' ', $part);
					$name = array_shift($modifiers);
					$value = $params[$name];
				}

				foreach ($modifiers as $modifier) {
					switch ($modifier) {
						case 'html':
							$value = htmlspecialchars($value);
						break;					
						
						case 'url':
							$value = rawurlencode($value);
						break;					      
						
						case 'escape':
							$value = addslashes($value);
						break;							
						
						case 'lower':
							$value = Strings::toLower($value);
						break;			      
						
						case 'upper':
							$value = Strings::toUpper($value);
						break;				      
						
						case 'longDateTime':
							$value = $value instanceof DateTime ? $value->format(
								$this->_environment->getLanguage()->getLongDateTimeFormat()) : '';
						break;	         				      
						
						case 'longDate':
							$value = $value instanceof DateTime ? $value->format(
								$this->_environment->getLanguage()->getLongDateFormat()) : '';
						break;	           				      
						
						case 'longTime':
							$value = $value instanceof DateTime ? $value->format(
								$this->_environment->getLanguage()->getLongTimeFormat()) : '';
						break;	   				      
						
						case 'shortDateTime':
							$value = $value instanceof DateTime ? $value->format(
								$this->_environment->getLanguage()->getShortDateTimeFormat()) : '';
						break;	         				      
						
						case 'shortDate':
							$value = $value instanceof DateTime ? $value->format(
								$this->_environment->getLanguage()->getShortDateFormat()) : '';
						break;
						
						case 'shortTime':
							$value = $value instanceof DateTime ? $value->format(
								$this->_environment->getLanguage()->getShortTimeFormat()) : '';
						break;
						
						case 'dateTimePhrase':
							$value = $value instanceof DateTime ? $value->format(
								$this->_environment->getLanguage()->getDateTimePhraseFormat()) : '';
						break;
						
						default:
							if (Strings::substring($modifier, 0, 2) == 'if') {
								if (preg_match(
									'/if\((?<name>[a-zA-Z0-9]+)'.
									'(?<operator>(==)|=|(!=)|<|>|<=|>=)'.
									'(?<value>[a-zA-Z0-9]+)\)/',
									$modifier, $matches)) {
									$value1 = $params[$matches['name']];
									$value2 = $matches['value'];
									$operator = $matches['operator'];
									
									switch ($operator) {
										case '==':
										case '=':
											if ($value1 != $value2)
												$value = '';
										break;
																			
										case '!=':
											if ($value1 == $value2)
												$value = '';
										break;  
										
										case '>':
											if ($value1 <= $value2)
												$value = '';
										break;     
										
										case '<':
											if ($value1 >= $value2)
												$value = '';
										break;   
										
										case '>=':
											if ($value1 < $value2)
												$value = '';
										break;  
										
										case '<=':
											if ($value1 > $value2)
												$value = '';
										break;
									}
								}
							}
						break; 
					}				
				}			
			}
			
			$result .= $value;
			$isExpression = !$isExpression;
		} 	
		
		return $result;				
	}

	// ===========================================================================
	
	/**
	 * Loads all values to the selected language
	 */
	private function load() {
		// First, select the environment of this translation because DataBase::query
		// uses the current environment to get the language
		$pushNeeded = Environment::getCurrent() != $this->_environment;
		
		if ($pushNeeded)
			Environment::push($this->_environment);
		try {
			$result = DataBase::query(
				"SELECT string.name AS stringName, plugin.name AS pluginName, ".
					"translation.value ".
				"FROM ".DataBase::formTableName('Premanager', 'Strings')." AS string ".
				"INNER JOIN ".DataBase::formTableName('Premanager', 'Plugins').
					" plugin ".
					"ON plugin.id = string.pluginID ",
				/* translating */
				'');
			while ($arr = $result->getNextRow()) {
				$pluginName = $arr['pluginName'];
				$stringName = $arr['stringName'];
				$value = $arr['value'];
				if (!$value)
					$value = $stringName;
				
				if (!isset($this->_values[$pluginName]))
					$this->_values[$pluginName] = array();
					
				$this->_values[$pluginName][$stringName] = $value;
			}
			
			$this->_loaded = true;
		} catch (Exception $e) {
			if ($pushNeeded) {
				Environment::pop();
				throw $e;
			}
			if ($pushNeeded)
				Environment::pop();
		}
	}
}

?>