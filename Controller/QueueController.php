<?php
class QueueController extends Controller
{
	function mylist($data)
	{

	}
}




/**
* 
*/
class Web
{
	
	function __construct(argument)
	{
		# code...
	}


	function page()
	{
		Auth::login();
		throw new Exception("Error Processing Request", 1);
		
		echo 1;
	}

}

代理模式


class WebPoxy
{

	function page($a = 'web')
	{
		$ref = ref('web');
		

		try {

			Auth::login();
			$ref->page();
			P::ADD();
		} catch (Exception $e) {
			Auth::logout();
		}
		
		
	}

}


aop

前通知

后通知

异常通知

$wp = new WebPoxy();

$wp->page();

/**
* 
*/
class Auth
{
	
	function __construct(argument)
	{
		# code...
	}

	function login()
	{
		return true;
	}
}




