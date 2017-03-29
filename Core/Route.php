<?php 

/**
*	路由 
*/
class Route
{
	private static $get = array();
	private static $post = array();
	private static $any = array();
	private static $input = '';

	public static function __callStatic($name, $arguments)
	{
		if (count($arguments) != 2) {
			throw new Exception("路由设置必须有两个参数", 99);
		}

		$request_arr = array();

		// 判断路由第二参数是否多值 
		if (is_array($arguments[1])) {
			$request_arr_v = $arguments[1];	
		} else if(is_string($arguments[1])) {
			$request_arr_v = ['use' => $arguments[1]];
		} else if ($arguments instanceof Closure) {
			$request_arr_v = $arguments[1];
		}
		
		if (!isset($request_arr_v['use'])) {
			throw new Exception("路由参数需设置 use", 99);
		}
		
		$request_arr[$arguments[0]]['route'] = $request_arr_v;

		array_push(static::$$name, $request_arr_v);

	}

	private static function setInput(Input $input)
	{	
		self::$input = $input;
	}
	
	private static function getHttpMethod()
	{
		return $_SERVER[''];
	}

	// 获取上的路由地址
	private static function getUrl()
	{
		return $_SERVER['REQUEST_URI'];
	}

	public static function parseUrl()
	{

	}

}