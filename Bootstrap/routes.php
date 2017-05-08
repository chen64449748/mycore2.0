<?php 

require_once __DIR__.'/filter.php';

$route = $app->make('Route');

// 注入过滤器
$route->setFilter($route_filter);


$route->get('/', ['use'=> 'IndexController@index']);


$route->group(['prefix'=>'admin_', 'filter'=>'a' ], function($route) {
	$route->get('/b', ['use' => 'IndexController@soap']);
});

$route->group(['prefix'=>'user_'], function($route) {
	$route->get('/c', ['use' => 'IndexController@index']);
});
