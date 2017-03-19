<?php

/**
* 框架事件
*/
class Events
{
	// 普通事件
	protected static $nomal_events = array();

	// 自动执行事件
	protected static $auto_events = array();

	// 绑定普通事件
	public static function addEvent($eventname, $func)
	{
		self::$nomal_events[$eventname] = $func;
	}

	// 执行普通事件
	public static function onListen($eventname, $data = array())
	{
		if (self::existsEvent($eventname)) {
			call_user_func(self::$nomal_events[$eventname], $data);
		}
	}

	// 判断事件是否存在
	public static function existsEvent($eventname)
	{
		return isset(self::$nomal_events[$eventname]);
	}

	// 判断自动事件是否存在
	public static function existsAutoEvent($eventname)
	{
		return isset(self::$auto_events[$eventname]);
	}

	// 绑定事件以及参数
	public static function addAutoEvent($eventname, $func, $data = array())
	{
		self::$auto_events[$eventname]['func'] = $func;
		self::$auto_events[$eventname]['argv'] = $data;
	}

	// 执行自动事件
	public static function runAutoEvents(){
		foreach (self::$auto_events as $eventname => $event) {
			call_user_func($event['func'], $event['argv']);
		}
	}

	public static function printEvents()
	{
		print_r(self::$nomal_events);
		print_r(self::$auto_events);
	}
}