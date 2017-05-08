<?php 

# Ioc 容器

class Container
{
	// 存放绑定事件
	protected $binds = array();

	// 存放对象
	protected $instances = array();

	// 存放反射
	protected $refleces = array();

	public function instance($abstract, $instance)
	{	
		$this->instances[$abstract] = $instance;
	}

	public function bind($abstract, $mix = null)
	{
		if (is_null($mix)) {
			$mix = $abstract;
		}
		$this->binds[$abstract] = $mix;
	}

	public function checkBind($abstract)
	{
		if (!isset($this->binds[$abstract])) {
			$this->bind($abstract);
		}
	}

	public function make($abstract, $params = [])
	{
		$this->checkBind($abstract);
		// 如果已经创建
		if (isset($this->instances[$abstract])) {
			return $this->instances[$abstract];
		}

		$obj = null;


		if ($this->binds[$abstract] == $abstract) {
			return $this->build($abstract);
		}

		// 如果有 Closure
		if ($this->binds[$abstract] instanceof Closure) {
			array_unshift($params, $this);
			return call_user_func_array($this->binds[$abstract], $params);
		}
		$this->instance($abstract, $obj);
		return $obj;
	}

	public function getReflection($abstract)
	{

		if (isset($this->refleces[$abstract])) {
			return $this->refleces[$abstract];
		}

		$reflection = new ReflectionClass(ucfirst($abstract));

		if (!$reflection->isInstantiable()) {
			throw new Exception("$abstract 不可实例化", 99);
		}
		
		$this->refleces[$abstract] = $reflection;
		return $reflection;
	}


	public function parseReflectionParameter(ReflectionParameter $parameter, $closure, &$decs_arr)
	{
		$class_name = $parameter->getClass();
		
		if ($class_name) {
			$obj = $this->build($class_name->name, $closure);	
			$decs_arr[] = $obj;
		} else {
			$decs_arr[] = call_user_func_array($closure, array($this, $parameter->name));
		}
		
		
	}

	// 自动注入 Closure 用来做遇到不是 对象注入的情况
	public function build($abstract, Closure $closure = null)
	{
		$reflection = $this->getReflection($abstract);
		
		$constructor = $reflection->getConstructor();

		if ($constructor) {
			$construct_params = $constructor->getParameters();
		} else {
			$construct_params = array();
		}
		
		$construct_arr = array();
		foreach ($construct_params as $parameter) {
			$this->parseReflectionParameter($parameter, $closure, $construct_arr);
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

	public function __set($name, $value)
	{
		$this->instance($name, $value);
	}
}