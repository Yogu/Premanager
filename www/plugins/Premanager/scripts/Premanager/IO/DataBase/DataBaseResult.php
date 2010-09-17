<?php
namespace Premanager\IO\DataBase;

use Premanager\Execution\Theme;
use Premanager\ArgumentException;
use Premanager\Module;

class DataBaseResult extends Module implements \ArrayAccess, \Countable {
	private $_resource;
	private $_row;
	private $_nextCalled = false;
	private $_eof = false;
	
	/**
	 * The count of rows in this result
	 * 
	 * @var int
	 */
	public $rowCount = Module::PROPERTY_GET;
	
	/**
	 * Creates a new DataBaseResult linked to a data base result ressource
	 * Enter description here ...
	 * @param unknown_type $ressource
	 */
	public function __construct($ressource) {
		$this->_resource = $ressource;
	}

	/**
	 * Frees the ressource
	 */
	function __destruct() {
		if ($this->_resource)
			\mysql_free_result($this->_resource);
		$this->_resource = null;
	}

	/**
	 * Gets the count of rows
	 * 
	 * @return int
	 */
	public function getRowCount() {
		return \mysql_num_rows($this->_resource);
	}                                

	/**
	 * Selects the next row
	 * 
	 * @return bool true on success, false if the end of result is exeeded
	 */
	public function next() {
		if ($this->_eof)
			return false;
			
 		$this->_nextCalled = true;
		$this->_eof = !($this->_row = \mysql_fetch_array($this->_resource));
		return (!$this->_eof);				
	}

	/**
	 * Gets the value of a field in the current row
	 * 
	 * @param string $fieldName the field name
	 * @return string the field value
	 */
	public function get($fieldName) {
 		if (!$this->_nextCalled) 
   		$this->next(); 
		if ($this->_eof)
			return null;
		else {
			if (isset($this->_row[$fieldName]))
				return $this->_row[$fieldName];
			else
				throw new ArgumentException('The specified field does not exist');
		}
	}
	
	/**
	 * Gets the count of fields of the current row
	 * 
	 * @return int the count of fields
	 */
	public function getFieldCount() {
 		if (!$this->_nextCalled) 
   		$this->next(); 
		if ($this->_eof)
			return null;
		else
			return count($this->_row);
	}
	
	/**
	 * Gets the count of fields in the current row
	 * 
	 * @return int the count of fields
	 */
	public function count() {
		// This method is needed to implement the Countable interface
		return $this->getFieldCount();
	}
	
	/**
	 * Checks whether the field specified by its name exists in the current row
	 * 
	 * @param mixed $offset
	 * @return bool true if the field exists, false if it does not exist or this
	 *   result is empty
	 */
	public function offsetExists($offset) {
 		if (!$this->_nextCalled) 
   		$this->next(); 
		if ($this->_eof)
			return false;
		else
			return isset($this->_row[$offset]);;
	}
	
	/**
	 * Gets the a value of a field in the current row specified by its field name
	 * 
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset) {
		return $this->get($offset);
	}
	
	public function offsetSet($offset, $value) {
		throw new InvalidOperationException('A DataBaseResult can not be changed');
	}
	
	public function offsetUnset($offset) {
		throw new InvalidOperationException('A DataBaseResult can not be changed');
	}
}

?>