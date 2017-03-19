<?php 

# Ioc 容器

class Container implements IContainer
{
	// 存放绑定事件
	protected $binds = array();

	// 存放对象
	protected $instances = array();

	public function instance($abstract, $instance)
	{	
		$this->instances[$abstract] = $instance;
	}

	
	public function make($abstract, $params = [])
	{
		
	}

	public function build($abstract, $)
	{

	}


	public function __get($name)
	{
		if (isset($this->instances[$name])) {
			return $this->instances[$name];
		} else {
			return null;
		}
	}
}