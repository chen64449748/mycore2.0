<?php
/**
* SessionDriver session启动器
*/
class SessionDriver
{
	
	function __construct($driver)
	{

		$reflection = new ReflectionClass(ucfirst($driver));
		if (!$reflection) {
			throw new Exception("没有找到Session驱动:".$driver, 99);
		}

		return $reflection->newInstance();
	}
}
?>