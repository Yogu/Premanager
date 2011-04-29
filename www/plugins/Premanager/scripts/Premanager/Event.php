<?php                             
namespace Premanager;
                   
/**
 * Defines a collection of callbacks assigned to a class and its instances 
 */
class Event extends Module {
	private $_ownerClass;
	private $_listeners = array();    

	// ===========================================================================
	
	/**
	 * Creates a new event and assigns it to a class
	 * 
	 * @param string $ownerClass the class name
	 */
	public function __construct($ownerClass) {
		if (!$ownerClass)
			throw new InvalidArgumentException('$ownerClass is null'); 

		parent::__construct();
		$this->_ownerClass = $ownerClass;	
	}     

	// ===========================================================================
	
	/**
	 * Registers an event listener
	 *
	 * If $obj is null, the listener is fired everytime this this event is called,
	 * no matter which object.
	 *
	 * If $obj is not null, the listener is only fired when $obj calls this event.
	 *
	 * @param callback $listener a callback
	 * @param Module $instance the instance on that the listener should be
	 *   assigned
	 */
	public function register($listener, Module $instance = null) {
		if ($obj != null && !($obj instanceof $this->_ownerClass))
			throw new InvalidArgumentException('$instance must be a '.
				$this->_ownerClass);
	
		if (!is_callable($listener))
			throw new InvalidAgrumentException('$listener is not callable');
		
		// $this->_listeners[x][0]: the obj the event listeners are assigned to
		// $this->_listeners[x][1]: an array with event listeners
		$found = false;
		foreach ($this->_listeners as &$tmp) {
			if ($tmp[0] === $instance) {
				if (!in_array($listener, $tmp[1]))
					$tmp[1][] = $listener;
				$found = true;
			}
		}
		if (!$found)
			$this->_listeners[] = array($instance, array($listener)); 
	}
	
	/**
	 * Fires this event
	 * 
	 * Calls all listeners assigned to $sender and all listeners assigned to the
	 * whole class.
	 * 
	 * You can also specify only one parameter.
	 *
	 * @param Module $sender instance that calls this event. Optional.
	 * @param array $args a list of arguments for the event. Optional.
	 */
	public function call($sender = null, array $args = array()) {  
		if ($sender !== null) {
			// Call with one parameter
			if (!count($args) && is_array($sender)) {
				$args = $sender;
				$sender = null;
			} else if (!($sender instanceof $this->_ownerClass))
				throw new InvalidArgumentException('$sender must be a '.
					$this->_ownerClass);
		}
			
		foreach ($this->_listeners as &$tmp) {
			if ($tmp[0] === $sender || $tmp[0] == null) {
				foreach ($tmp[1] as $listener) { 
					call_user_func($listener, $sender, $args);
				}
			}
		}
	}
}