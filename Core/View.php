<?php 
/**
* 
*/
class View
{
	private static $var = array();
	private static $view = null;
	private static $view_path = null;

	private function __construct()
	{

	}

	public static function init()
	{
		if (is_null(self::$view)) {
			self::$view = new View();
		}
		return self::$view;
	}

	public static function with($arr)
	{
		self::init();
		self::$var = array_merge(self::$var, $arr);
		return self::$view;
	}

	// 默认调用 以控制器为名的文件夹 下的 action为名的文件
	private static function getDefaultPath()
	{
		$path = str_replace('Controller/', 'Views/', CONTROLLER_PATH);
		$path = str_replace('Controller.php', '', $path);

		$path = $path.'/'.ACTION.'.html';
		return $path;
	}

	// 存在path变量时调用路径
	private static function getPath($path)
	{

		$tmp_path = ltrim($path, '/Views');
		$tmp_path = ltrim($tmp_path, './Views');
		$tmp_path = rtrim($tmp_path, '.html');
		$tmp_path = $tmp_path.'.html';
		$real_path = MYTP_VIEWS.$tmp_path;
		return $real_path;
	}

	private static function setViewPath($path = null)
	{
		if ($path) {
			self::$view_path = self::getPath($path);
		} else {
			self::$view_path = self::getDefaultPath();
		}
	}

	public static function getVar()
	{
		return self::$var;
	}

	public static function display($path = null)
	{
		Import::in('Smarty/libs/Smarty.class.php');

		self::setViewPath($path);

		$smarty = new Smarty();
		$smarty->debugging = true;
		$smarty->caching = true;
		$smarty->cache_lifetime = 120;
		$data = self::$var;
		foreach ($data as $key => $value) {
			$smarty->assign($key, $value);
		}
		$smarty->display(self::$view_path);

	}
}
?>