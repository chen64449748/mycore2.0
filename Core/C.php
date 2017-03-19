<?php  
class C
{
	// $key 如果取多重下标用 . 号分开
	public static function get($key, $default = null)
	{
		$keys = explode('.', $key);
		$config = $GLOBALS['config'];
		foreach ($keys as $k) {
			if (!isset($config[$k])) return $default;

			$config = $config[$k];
			if (is_string($config)) {
				return $config;
			}
		}

		return $default;
	}
	public static function test()
	{
		echo 1;
	}
}
?>