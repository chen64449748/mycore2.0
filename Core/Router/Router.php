<?php 

/**
* 
*/
class Router
{
	private $use;
	private $closure;

	function __construct($data)
	{
		if (is_array($data)) {
			$this->use = $data;
		} else if ($data instanceof Closure) {
			$this->closure = $data;
		}
	}
}