<?php


class Shell implements IDriver
{

	private function pathArvgs()
	{
		return Input::getArgvs();
	}

	private function parseUrl()
	{

		$argv = $this->pathArvgs();
		define('MODULE', 'CrontabController');
		define('ACTION', $argv['action']);
	} 

	public function run(App $app)
	{
		$this->parseUrl();
		$app->dispatch();
	}

	public function log()
	{

	}

	public function error(Exception $e)
	{
		echo $e->getMessage().PHP_EOL;
	}
}