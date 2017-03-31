<?php 

class RouteFilter 
{
	// 过滤方法
	private $closure = array();
	
	private static $filter;

	// 存放ioc
	private $container;
	// 单例
	private function __construct()
	{
		
	}

	public static function init()
	{

		if (!self::$filter) {
			self::$filter = new self();
			
		}

		return self::$filter;
	}

	public function setContainer(Container $container = null)
	{
		$this->container = $container;
	}

	public function filter($filtername, $closure)
	{
		$this->closure[$filtername] = $closure; 
	}

	public function run($filtername, Exception $e = null)
	{
		if (!isset($this->closure[$filtername])) {
			return false;
		}

		if (!$this->container) {
			throw new Exception("RouteFilter 需要注入Ioc(setContainer方法)");
		}

		$function_reflection = $this->getFunctionReflection($this->closure[$filtername]);
		$params = array();

		$parameters = $function_reflection->getParameters();

		foreach ($parameters as $parameter) {
			$this->parseParameters(
				$parameter,
				function ($container, $name) {
					$input = $container->make('Input');
					return $input->get($name, null);
				},
				$params
			);
		}

		$params[] = $e;

		return call_user_func_array($this->closure[$filtername], $params);
	}

	private function getFunctionReflection($closure)
	{
		return new ReflectionFunction($closure);
	}

	private function parseParameters(ReflectionParameter $parameter, $closure, array &$params)
	{
		$class = $parameter->getClass();

		if ($class) {
			$params[] = $this->container->make($class->name);
		} else {
			$params[] = call_user_func_array($closure, array($this->container, $parameter->name));
		}

		return $params;
	}
}