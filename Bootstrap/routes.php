<?php 

$route = $app->make('Route');

$route->get('/a', ['use'=> 'IndexController@index']);