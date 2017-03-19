<?php

// 驱动器接口
interface IDriver 
{
	public function run();

	public function error(Exception $e);

	public static function dispatch();
}