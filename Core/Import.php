<?php  
/**
* 
*/
class Import
{
	private static function path($path)
	{
		$path = ltrim($path , 'vendor/');
		$path = MYTP_VENDOR.$path;
		return $path;
	}

	public static function in($path)
	{
		$path = self::path($path);
		if (file_exists($path)) {
			include_once($path);
		}
	}
}
?>