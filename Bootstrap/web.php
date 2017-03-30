<?php

require_once __DIR__.'/autoload.php';

// require_once __DIR__.'routes.php'

// $app->run($app->make('Web'));

$route = $app->make('Route');

$route->get('/a', ['use'=> 'IndexController@index']);

$route->group(['prefix'=>'admin_', 'filter'=>'a'], function($route) {
	$route->get('/b', ['use' => 'IndexController@index']);
});

$route->group(['prefix'=>'user_'], function($route) {
	$route->get('/c', ['use' => 'IndexController@index']);
});
// echo 1;
// print_r($_SERVER);
$route->parseRouter();