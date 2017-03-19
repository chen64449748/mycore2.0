<?php
class IndexController extends Controller
{
	private $instances;
	function __construct()
	{
		
	}

	function index()
	{
		// $redis = new Redis();
		// $redis->pconnect('127.0.0.1', 6379, 0);
		// $redis->lpush('mylist', 'task'.mt_rand());
		User::index();
		View::with(array('c'=>'123'));
	}
}