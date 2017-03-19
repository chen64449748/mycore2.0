<?php

/**
* web驱动器
*/
class Web implements IDriver
{
	// 给每个页面设置 字符集
	private static function setChar()
	{
		header('content-type:text/html;charset=utf-8');
	}

	// session配置
	private static function startSession()
	{
		Session::start();
	}

	// 调用控制器
	private static function analyseURL()
	{
		if (isset($_SERVER['PATH_INFO'])) {
			$path_info = ltrim($_SERVER['PATH_INFO'], '/');
			$arr = explode('/', $path_info);
			$module = $arr[0];
			$action = $arr[1];
		} else {
			$module = isset($_REQUEST['module'])?ucfirst(strtolower($_REQUEST['module'])):'Index';
			$action = isset($_REQUEST['action'])?strtolower($_REQUEST['action']):'index';	
		}
		
		define('MODULE', $module);
		define('ACTION', $action);
	}

	// 请求
	public static function dispatch()
	{
		$module = MODULE.'Controller';
		$action = ACTION;
		$reflection = new ReflectionClass($module);
		if (!$reflection) {
			throw new Exception('没有找到controller:'.MODULE, 99);
		}
		if (!$reflection->hasMethod($action)) {
			throw new Exception('没有找到action:'.ACTION, 99);
		}
		
		define('CONTROLLER_PATH', str_replace('\\', '/', $reflection->getFileName()));
		$web = $reflection->newInstance();
		$result = $web->$action();
		echo $result;
		
	}
	
	// 包含文件
	public static function inBootstrap()
	{
		include_once MYTP_DIR.'Bootstrap/function.php';
		include_once MYTP_DIR.'Bootstrap/Events.php';
		include_once MYTP_DIR.'Bootstrap/defined.php';
	}


	public function run()
	{
		self::setChar();
		self::startSession();
		self::analyseURL();
		self::inBootstrap();
		self::dispatch();
		echo 1;
	}

	public function error(Exception $e)
	{
		echo $e->getMessage();
	}

	public function log()
	{
		//Events::runAutoEvents();
		$module = MODULE;
		$action = ACTION;
		//echo '日志';
	}
}
