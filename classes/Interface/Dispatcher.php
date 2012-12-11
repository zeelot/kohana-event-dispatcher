<?php defined('SYSPATH') or die('No direct script access.');

interface Interface_Dispatcher {

	public function add_listener($event_name, $listener);
	public function has_listeners($event_name = NULL);
	public function get_listeners($event_name = NULL);
	public function trigger($event_name, Interface_Event $event, $stop_on_return_value = FALSE);

}
