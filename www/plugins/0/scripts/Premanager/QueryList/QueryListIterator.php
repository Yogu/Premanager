<?php
namespace Premanager\QueryList;

use Premanager\Module;

class QueryListIterator extends Module implements \Iterator {
	private $_list;
	private $_index;
	
	public function __construct(QueryList $list) {
		parent::__construct();
		$this->_list = $list;
		$this->_index = 0;
	}
	
	/**
	 * Gets the current item
	 * 
	 * @return mixed
	 */
	public function current() {
		return $this->_list->get($this->_index);
	}
	
	/**
	 * Gets the index of the current item
	 * 
	 * @return int
	 */
	public function key() {
		return $this->_index;
	}
	
	/**
	 * Selects the next item
	 */
	public function next() {
		$this->_index++;
	}
	
	/**
	 * Selects the first item
	 */
	public function rewind() {
		$this->_index = 0;
	}
	
	/**
	 * Checks whether the current index is valid
	 * 
	 * @return bool
	 */
	public function valid() {
		return $this->_index < $this->_list->getcount();
	}
}

?>