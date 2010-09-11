<?php
namespace Premanager\IO\DataBase;

use Premanager\Module;

class DataBaseResult extends Module {
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
		else
			return $this->_row[$fieldName];
	}
}

?>