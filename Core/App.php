<?php
/**
* 框架主要类  负责框架前期工作
自动加载  设置字符集   配置文件
*/
class App extends Container
{
	public function __construct()
	{
		
	}

	// APP运行
	public function run($drivename)
	{
	
		// 调用不同的驱动 加载不同模式
		try {
			$this->bind('Web','Web');

			$web = $this->build('Web');
			print_r($web);exit;
			$driver = new Driver($drivename);
			$app = $driver->getDriver();
			$app->run();	
			if ($app instanceof Web) $app->log();
		} catch (Exception $e) {
			$app->error($e);
		}
		
	}
	
	// 设置超时时间
	private function setLimit()
	{
		//set_time_limit(0);
	}

	// 设置php
	private function setSys()
	{
		// ini_set('display_errors', $GLOBALS['config']['display_err']);
		// ini_set('error_reporting', E_ALL);
	}

	
}

