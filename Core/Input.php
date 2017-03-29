<?php  
/**
* 用来接收前端提参数
*/
class Input
{
	public static function get($key, $default = null)
	{
		return isset($_REQUEST[$key])?$_REQUEST[$key]:$default;
	}

	public static function post($key, $default = null)
	{
		return isset($_POST[$key])?$_POST[$key]:$default;
	}

	public static function all()
	{
		return $_REQUEST;
	}

	public static function import($arr)
	{
		$_REQUEST = array_merge($_REQUEST, $arr);
	}

	public static function getArgvs()
	{
		if (isset($_SERVER['argv'])) {
			$argv = $_SERVER['argv'];
			unset($argv[0]);	
		} else {
			return $_REQUEST;
		}
		
		$return_arr = array();
		foreach ($argv as $value) {
			$tmp_arr = explode('=', $value);
			$return_arr[trim($tmp_arr[0], '-')] = $tmp_arr[1];
			$_REQUEST[trim($tmp_arr[0], '-')] = $tmp_arr[1];
		}
		return $return_arr;
	}
}
?>