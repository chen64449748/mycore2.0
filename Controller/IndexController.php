<?php
class IndexController extends Controller
{
	private $instances;
	function __construct()
	{
		
	}

	function index(User $a)
	{
		// $redis = new Redis();
		// $redis->pconnect('127.0.0.1', 6379, 0);
		// $redis->lpush('mylist', 'task'.mt_rand());
		View::with(array('c'=>'123'));
	}
}