<?php 
class Cache 
{

	protected static $cache = null;

	private function __construct()
	{

	}

	// 
	public static function init()
	{
		if (is_null(self::$cache)) {
			self::$cache = memcache_connect(C::get('cache.host'), C::get('cache.port'));
		}
		return self::$cache;
	}

	// return str
	public static function get($key)
	{
		self::init();
		return memcache_get(self::$cache, $key);
	}

	// return bool
	public static function set($key, $data)
	{
		self::init();
		return memcache_set(self::$cache, $key, $data);
	}

	// return bool
	public static function flush()
	{
		self::init();
		return memcache_flush(self::$cache);
	}

}

?>