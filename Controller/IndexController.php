<?php
class IndexController extends Controller
{
	private $instances;
	function __construct()
	{
		
	}

	function c(User $a, Input $i)
	{
		Route::get('/a', ['use'=>1]);
		var_dump($_SERVER);
		View::with(array('c'=>'123'));
	}
}