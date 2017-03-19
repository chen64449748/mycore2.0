<?php


class Shell implements IDriver
{

	private static function pathArvgs()
	{
		return Input::getArgvs();
	}

	public static function dispatch()
	{
		$controller = 'CrontabController';
		$action = self::pathArvgs()['action'];
		$ref = new ReflectionClass($controller);
		if (!$ref) {
			throw new Exception('没有找到controller:Crontab', 99);
		}
		if (!$ref->hasMethod($action)) {
			throw new Exception('没有找到action:'.$action, 99);
		}
		$shell = $ref->newInstance();
		$shell->$action(self::pathArvgs());
	}

	public function run()
	{
		self::dispatch();
	}

	public function log()
	{

	}

	public function error(Exception $e)
	{

	}
}