<?php

/**
* 
*/
class Driver extends ReflectionClass
{
	private $driver;

	function __construct($classname)
	{
		parent::__construct($classname);
	}

	public function getDriver()
	{
		return $this->newInstance();
	}
}