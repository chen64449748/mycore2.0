<?php
/**
* 框架主要类  负责框架前期工作
自动加载  设置字符集   配置文件
*/
class App extends Container
{
	public function __construct()
	{
		// $this->bind('Web');
		// $this->bind('Shell');
		// $this->bind('Queue');
	}

	// APP运行
	public function run(IDriver $driver)
	{
		$this->setSys();
		// 调用不同的驱动 加载不同模式
		try {
			$driver->run($this);	
			if ($driver instanceof Web) $driver->log();
		} catch (Exception $e) {
			$driver->error($e);
			
		}
		
	}
	
	// 设置超时时间
	private function setLimit()
	{
		set_time_limit(0);
	}

	// 设置php
	private function setSys()
	{
		ini_set('display_errors', $GLOBALS['config']['display_err']);
		error_reporting(E_ALL || E_NOTICE);
	}

	// 请求
	public function dispatch()
	{
		$module = MODULE;
		$action = ACTION;

		$controller = $this->getController($module);
		$reflection = $this->getReflection($module);

		define('CONTROLLER_PATH', str_replace('\\', '/', $reflection->getFileName()));

		$web_action = $this->getAction($reflection, $action);

		$params = $this->parseAction($web_action);	

		$result = $web_action->invokeArgs($controller, $params);
		echo $result;
		ob_flush();
	}

	public function getController($controller_name)
	{
		return $this->make($controller_name);
	}
	
	public function getAction($reflection, $action)
	{
		return $reflection->getMethod($action);
	}

	public function parseAction(ReflectionMethod $action)
	{
		$params = array();
		foreach ($action->getParameters() as $parameter) {
			$this->parseReflectionParameter(
				$parameter,
				function ($container, $name) 
				{
					$input = $container->make('Input');
					return $input->get($name, null);
				},
				$params // 参数引用类型
			);
		}

		return $params;
	}
}

