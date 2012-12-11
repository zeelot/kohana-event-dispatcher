<?php defined('SYSPATH') or die('No direct script access.');

interface Interface_Event {

	public function __construct( & $subject, array $arguments = array());
	public function & get_subject();
	public function get_arguments();
	public function stop_propagation();
	public function is_propagation_stopped();

}
