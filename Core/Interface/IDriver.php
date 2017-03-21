<?php

// 驱动器接口
interface IDriver 
{
	public function run(App $app);

	public function error(Exception $e);

}