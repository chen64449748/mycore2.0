<?php 

/**
* 
*/
class Autoload
{
	public static function setDir()
	{
		define('MYTP_DIR'        , str_replace('Core','',str_replace('\\', '/', __DIR__)));
		define('MYTP_CORE'       , MYTP_DIR.'Core/');
		define('MYTP_MODEL'      , MYTP_DIR.'Model/');
		define('MYTP_CONTROLLER' , MYTP_DIR.'Controller/');
		define('MYTP_CONFIG'     , MYTP_DIR.'Config/');
		define('MYTP_VIEWS'      , MYTP_DIR.'Views/');
		define('MYTP_VENDER'     , MYTP_DIR.'Vender/');
		define('MYTP_VENDOR'     , MYTP_DIR.'vendor/');
		define('MYTP_SESSION_DRIVER', MYTP_CORE.'Sessiondriver/');
		define('MYTP_DRIVER'     , MYTP_CORE.'Driver/');
		define('MYTP_INTERFACE'  , MYTP_CORE.'Interface/');
	}

	// 加载配置
	public static function loadConfig()
	{
		$GLOBALS['config']['display_err'] = 'on';
		if (!file_exists(MYTP_CONFIG.'Config.php')) {
			throw new Exception('找不到配置文件:'.MYTP_CONFIG.'Config.php', 99);
		}
		$GLOBALS['config'] = array_merge($GLOBALS['config'], require_once(MYTP_CONFIG.'Config.php'));
		if (isset($GLOBALS['config']['require_config'])) {
			if (is_array($GLOBALS['config']['require_config']) && $require_config = $GLOBALS['config']['require_config']) {
				foreach ($require_config as $key => $value) {
					$GLOBALS['config'][$key] = $value;
				}
			}
		}
	}

	// $name为类名
	private static function loadCore($name)
	{
		$filename = MYTP_CORE.basename($name).'.php';
		if (file_exists($filename)) {
			include_once($filename);
		}
	}

	// 加载sessiondriver
	private static function loadSessionDriver($name)
	{
		$filename = MYTP_CORE.'Sessiondriver/'.basename($name).'.php';
		if (file_exists($filename)) {
			include_once($filename);
		}
	}

	// 加在功能驱动
	private static function loadDriver($name)
	{
		$filename = MYTP_DRIVER.basename($name).'.php';
		if (file_exists($filename)) {
			include_once($filename);
		}
	}

	// 加载接口
	private static function loadInterface($name)
	{
		$filename = MYTP_INTERFACE.basename($name).'.php';
		if (file_exists($filename)) {
			include_once($filename);
		}
	}

	// 自动加载Model
	public static function loadModel($name)
	{
		
		try {
			$filename = MYTP_MODEL.basename($name).'.php';
			//print_r($filename);exit;
			if (file_exists($filename)) {
				include_once($filename);
			}
			if (isset($GLOBALS['config']['MODEL_DIR']) && is_array($GLOBALS['config']['MODEL_DIR']) && $model_dir = $GLOBALS['config']['MODEL_DIR']) {

				// 用来配置 是否在Model 文件夹下新建文件夹
				foreach ($model_dir as $key => $value) {
					$value = trim($value, '/').'/';
					$filename = MYTP_MODEL.$value.basename($name).'.php';
					if (file_exists($filename)) {
						include_once($filename);
						self::$container->bind(basename($name));	
					}
					
				}
				
			}
		} catch (Exception $e) {
			
		}
		

		
	}

	// 自动加载
	private static function loadController($name)
	{
		try {
			$filename = MYTP_CONTROLLER.basename($name).'.php';
			if (file_exists($filename)) {
				include_once($filename);
			}		
			if (isset($GLOBALS['config']['CONTROLLER_DIR']) && is_array($GLOBALS['config']['CONTROLLER_DIR']) && $controller_dir = $GLOBALS['config']['CONTROLLER_DIR']) {
				// 用来配置 是否在Controller 文件夹下新建文件夹
				foreach ($controller_dir as $key => $value) {
					$value = trim($value, '/').'/';
					$filename = MYTP_CONTROLLER.$value.basename($name).'.php';
					if (file_exists($filename)) {
						include_once($filename);
					}
				}

			}
		} catch (Exception $e) {
			
		}
		
	}

	static function auto()
	{
		self::setDir();
		self::loadConfig();	
		spl_autoload_register('self::loadCore');
		spl_autoload_register('self::loadSessionDriver');
		spl_autoload_register('self::loadDriver');
		spl_autoload_register('self::loadInterface');
		spl_autoload_register('self::loadModel');
		spl_autoload_register('self::loadController');
		

	}
}