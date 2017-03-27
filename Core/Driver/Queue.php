<?php

/**
* 
*/
class Queue implements IDriver
{

	private $redis = null;

	public function run(App $app)
	{
		set_time_limit(0);

		if (!$this->isRedis()) {
			throw new Exception("没有找到redis扩展", 99);
		}
		$this->pathArvgs();
		$this->parseList();
		$this->getRedis();
		$this->dispatch($app);
	}

	public function error(Exception $e)
	{
		echo $e->getMessage().PHP_EOL;
	}
	private function isRedis()
	{
		return extension_loaded('redis');
	}

	private function getRedis()
	{

		if (!$this->redis) {
			try {
				$this->redis = new Redis();
				$this->redis->connect(C::get('redis.host', '127.0.0.1'), C::get('redis.port', 6379), C::get('redis.timeout', 0));
				$this->redis->ping();
				if ($this->redis->ping() != '+PONG') {
					throw new Exception("未开启redis服务", 99);
				}	
			} catch (Exception $e) {
				
			}
			
		}	
	}

	private function parseList()
	{
		define('ACTION', Input::get('action'));
	}

	private function pathArvgs()
	{
		return Input::getArgvs();
	}

	private function dispatch(App $app)
	{
		$queue = $app->getController('QueueController');
		$reflection = $app->getReflection('QueueController');
		$queue_action = $app->getAction($reflection, ACTION);

		$params = $app->parseAction($queue_action);
		while (true) {
			try {
				// 获取队列任务
				$data = $this->redis->brPop(ACTION, 0);
				array_unshift($params, $data);
				$queue_action->invokeArgs($queue, $params);
				ob_flush();
			    flush();
			} catch (Exception $e) {
				if ($this->redis->ping() != '+PONG') {
					$this->redis->connect(C::get('redis.host', '127.0.0.1'), C::get('redis.port', 6379), C::get('redis.timeout', 0));
				}
			}
			
		}

		
	}
}