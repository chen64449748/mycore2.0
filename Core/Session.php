<?php
/**
* Session 调用
*/
class Session
{
	private static $session_type = null;//C::get('session_type');
	private static $session_driver = null;

	public static function start()
	{
		self::$session_type = C::get('session_type');

		if (strcasecmp(self::$session_type, 'file') != 0) {
			echo self::$session_type;
			session_set_save_handler('self::sessBegin', 'self::sessEnd', 'self::sessRead', 'self::sessWrite', 'self::sessDelete', 'self::sessGC');
		}
		session_start();
	}

	private static function sessBegin()
	{
		if (is_null(self::$session_driver)) {
			// 实例驱动器
			self::$session_driver = new SessionDriver(self::$session_type);
		}
	}

	private static function sessRead($session_id)
	{	
		return self::$session_driver->get($session_id);
	}

	private static function sessWrite($session_id, $session_content)
	{
		
	}

	private static function sessDelete($session_id)
	{

	}

	// $limite_time 最大有效时间
	private static function sessGC($limit_time)
	{

	}

	private static function sessEnd()
	{
		return true;
	}
}
?>