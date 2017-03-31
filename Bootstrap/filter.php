<?php 

$route_filter = RouteFilter::init();
$route_filter->setContainer($app);


$route_filter->filter('a', function(Input $i) {
	echo $i->get('ss');
	echo 'filter a';
	return true;
});