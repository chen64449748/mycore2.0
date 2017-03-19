<?php

/**
* 
*/
class Queue implements IDriver
{

	private static $redis = null;

	public function run()
	{
		set_time_limit(0);

		if (!self::isRedis()) {
			throw new Exception("没有找到redis扩展", 99);
		}
		self::getRedis();
		self::dispatch();
	}

	public function error(Exception $e)
	{
		echo $e->getMessage().PHP_EOL;
	}
	private static function isRedis()
	{
		return extension_loaded('redis');
	}

	private static function getRedis()
	{

		if (!self::$redis) {
			try {
				self::$redis = new Redis();
				self::$redis->connect(C::get('redis.host', '127.0.0.1'), C::get('redis.port', 6379), C::get('redis.timeout', 0));
				self::$redis->ping();
				if (self::$redis->ping() != '+PONG') {
					throw new Exception("未开启redis服务", 99);
				}	
			} catch (Exception $e) {
				
			}
			
		}	
	}

	private static function pathArvgs()
	{
		return Input::getArgvs();
	}

	public static function dispatch()
	{
		$controller = 'QueueController';
		$action = self::pathArvgs()['action'];
		$ref = new ReflectionClass($controller);
		if (!$ref) {
			throw new Exception('没有找到controller:Queue', 99);
		}
		if (!$ref->hasMethod($action)) {
			throw new Exception('没有找到action:'.$action, 99);
		}
		$queue = $ref->newInstance();

		while (true) {
			try {
				// 获取队列任务
				$data = self::$redis->brPop($action, 0);
				$queue->$action($data, self::pathArvgs());
				ob_flush();
			    flush();
			} catch (Exception $e) {
				if (self::$redis->ping() != '+PONG') {
					self::$redis->connect(C::get('redis.host', '127.0.0.1'), C::get('redis.port', 6379), C::get('redis.timeout', 0));
				}
			}
			
		}

		
	}
}