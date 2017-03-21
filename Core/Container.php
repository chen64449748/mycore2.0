<?php 

# Ioc 容器

class Container implements IContainer
{
	// 存放绑定事件
	protected $binds = array();

	// 存放对象
	protected $instances = array();

	public function bindAuto($abstract)
	{
		$this->binds[$abstract] = $abstract;
	}

	public function instance($abstract, $instance)
	{	
		$this->instances[$abstract] = $instance;
	}

	public function bind($abstract, $mix)
	{
		$this->binds[$abstract] = $mix;
	}

	public function make($abstract, $params = [])
	{
		// 如果已经创建
		if (isset($this->instances[$abstract])) {
			return $this->instances[$abstract];
		}

		$obj = null;


		if ($this->binds[$abstract] == $abstract) {
			return $this->build($abstract, $params);
		}

		// 如果有 Closure

		return $obj;
	}

	protected function getReflection($abstract)
	{
		$reflection = new ReflectionClass(ucfirst($abstract));

		if (!$reflection->isInstantiable()) {
			throw new Exception("$abstract 不可实例化", 99);
		}
		
		return $reflection;
	}


	public function parseReflectionParameter(ReflectionParameter $parameter, $parameters, &$decs_arr)
	{
		$class_name = $parameter->getClass();

		$obj = $this->build($class_name, $parameters);

		$decs_arr[] = $obj;
	}

	// 自动注入
	protected function build($abstract, $params = [])
	{
		$reflection = $this->getReflection($abstract);
		
		$constructor = $reflection->getConstructor();
		$construct_params = $constructor->getParameters();
		$construct_arr = array();
		foreach ($construct_params as $parameter) {
			$this->parseReflectionParameter($parameter, $params, $construct_arr);
		}

		if ($construct_arr) {
			$obj = $reflection->newInstanceArgs($construct_arr);
		} else {
			$obj = $reflection->newInstance();
		}

		return $obj;
	}

	//
	// private function build($abstract, $params = [])
	// {
	// 	$reflection = $this->getReflection(ucfirst($abstract));
	// }


	public function __get($name)
	{
		if (isset($this->instances[$name])) {
			return $this->instances[$name];
		} else {
			return null;
		}
	}
}