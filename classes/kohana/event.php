<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Event implements Interface_Event {

	protected $_subject = NULL;
	protected $_arguments = array();
	protected $_propagation_stopped = FALSE;

	public function __construct($subject = NULL, array $arguments = array())
	{
		$this->_subject = $subject;
		$this->_arguments = $arguments;
	}

	public function get_subject()
	{
		return $this->_subject;
	}

	public function get_arguments()
	{
		return $this->_arguments;
	}

	public function stop_propagation()
	{
		return $this->_propagation_stopped = TRUE;
	}

	public function is_propagation_stopped()
	{
		return $this->_propagation_stopped;
	}

}
