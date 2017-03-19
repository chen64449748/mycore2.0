<?php  
/**
* 
*/
class Import
{
	private static function path($path)
	{
		$path = ltrim($path , 'Vender/');
		$path = MYTP_VENDER.$path;
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