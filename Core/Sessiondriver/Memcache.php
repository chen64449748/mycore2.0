<?php 
/**
* session的 memcache驱动
*/
class Memcache
{
	private $config = array();
	private $memcache = null;
	function __construct()
	{
		$this->config = array( 'host' => C::get('cache.host'), 'port'=> C::get('cache.port'));
		$this->memcache = Cache::init();
	}

	function get($key)
	{
		return $this->memcache->get($key);
	}

	function set($key, $value)
	{
		return $this->memcache->set($key, $value);
	}
}
 ?>