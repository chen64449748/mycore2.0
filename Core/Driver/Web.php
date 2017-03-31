<?php

/**
* web驱动器
*/
class Web implements IDriver
{


	function __construct()
	{

	}
	// 给每个页面设置 字符集
	private function setChar()
	{
		header('content-type:text/html;charset=utf-8');
	}

	// session配置
	private function startSession()
	{
		Session::start();
	}

	// 调用控制器  移交给路由解析
	// private function analyseURL()
	// {


	// 	if (isset($_SERVER['REQUEST_URI'])) {
	// 		$path_info = ltrim($_SERVER['REQUEST_URI'], '/');
	// 		$arr = explode('/', $path_info);
	// 		$module = isset($arr[0]) && $arr[0] != '' ? $arr[0] : 'Index';
	// 		$action = isset($arr[1]) && $arr[1] != '' ? $arr[1] : 'index';
	// 	} else {
	// 		$module = isset($_REQUEST['module'])?ucfirst(strtolower($_REQUEST['module'])):'Index';
	// 		$action = isset($_REQUEST['action'])?strtolower($_REQUEST['action']):'index';	
	// 	}

	// 	define('MODULE', $module);
	// 	define('ACTION', $action);
	// }
	
	// 包含文件
	public function inBootstrap()
	{
		include_once MYTP_DIR.'Bootstrap/function.php';
		include_once MYTP_DIR.'Bootstrap/Events.php';
		include_once MYTP_DIR.'Bootstrap/defined.php';
	}


	public function run(App $app)
	{
		$this->setChar();
		$this->startSession();
		$this->inBootstrap();
		$app->dispatch();
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
