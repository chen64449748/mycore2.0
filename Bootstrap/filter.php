<?php 

$route_filter = RouteFilter::init();
$route_filter->setContainer($app);


$route_filter->filter('a', function(Input $i) {
	return true;
});