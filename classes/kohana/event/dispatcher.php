<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Event_Dispatcher implements Interface_Dispatcher {

	public static function factory()
	{
		return new Kohana_Event_Dispatcher;
	}
	
	protected $_listeners = array();

	public function add_listener($event_name, $listener)
	{
		if ( ! isset($this->_listeners[$event_name]))
		{
			$this->_listeners[$event_name] = array();
		}
		
		if ( ! is_callable($listener))
			throw new Event_Exception();
		
		$this->_listeners[$event_name][] = $listener;
		
		return $this;
	}
	
	public function has_listeners($event_name = NULL) 
	{
		if ($event_name === NULL)
			return (bool) $this->_listeners;
		else
			return ! empty($this->_listeners[$event_name]);
	}
	
	public function get_listeners($event_name = NULL)
	{
		if ( ! $this->has_listeners($event_name))
			return array();
		elseif ($event_name !== NULL)
			return $this->_listeners[$event_name];
		else
			return $this->_listeners;
	}
	
	public function trigger($event_name, Interface_Event $event, $stop_on_return_value = FALSE) 
	{
		// If no listeners, then done
		if ( ! $this->has_listeners($event_name))
			return FALSE;
		
		// Execute all applicable listeners of the event
		$value = NULL;
		foreach ($this->get_listeners($event_name) as $listener)
		{
			// Stop propagating if the event has been stopped or a return value has been received
			if ($event->is_propagation_stopped() OR ($value AND $stop_on_return_value))
				break;
			
			// Tell the listener to handle the event
			$value = call_user_func($listener, $event);
		}
		
		return $value;
	}
	
	public function load_subscribers($directory = 'subscriber')
	{
		// Figure out the directory we are going to search in
		$base_dir = 'classes/';
		$directory = $base_dir.$directory;
		
		// Search for all subscribers
		foreach (Kohana::list_files($directory) as $file)
		{
			// Get the relative path from the classes directory
			$start = strpos($file, $directory) + strlen($base_dir);
			$file = substr($file, $start, -4); // -4 is for removing ".php"
			
			// Instantiate the subscriber class
			$class = str_replace('/', '_', $file);
			$subscriber = new $class;
			
			// Add the subscriber to the dispatcher
			$this->add_subscriber($subscriber);
		}
		
		return $this;
	}
	
	public function add_subscriber(Interface_Subscriber $subscriber)
	{
		foreach ($subscriber->get_listeners() as $event_name => $listener)
		{
			$this->add_listener($event_name, $listener);
		}
		
		return $this;
	}

}
