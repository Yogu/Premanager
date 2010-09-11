<?php 
namespace Premanager\IO\DataBase;

use Premanager\Exception;
use Premanager\IO\IOException;

/**
 * Is thrown when an invalid sql string is tried to be executed
 */
class SQLException extends DataBaseException {
	private $_error;
	private $_query;
	
	/**
	 * Creates a new SQLException
	 *
	 * @param string $query the query string 
	 * @param string $error the error message
	 */
	public function __construct($query, $error) {
		parent::__construct($error. ' (Query: '.$query.')');
		$this->_erorr = $error;
		$this->_query = $query;
	}
	
	/**
	 * Gets the error message
	 * 
	 * @return string
	 */
	public function getError() {
		return $this->_error;
	}
	
	/**
	 * Gets the query string
	 * 
	 * @return string
	 */
	public function getQuery() {
		return $this->_query;
	}
}

?>