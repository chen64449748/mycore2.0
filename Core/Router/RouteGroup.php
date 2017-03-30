<?php 

class RouteGroup extends Route
{

	private $routers = array();

	private $prefix = array();

	function __construct($prefix)
	{
		$this->prefix = $prefix;
	}

	function __call($name, $arguments)
	{
		// 给数据里加 成员
		$add_route = $this->addRoute($arguments);
		
		$route_key = key($add_route);

		if (isset($this->prefix['filter'])) {

			$add_route[$route_key]['filter'] = $this->prefix['filter'];
		}

		if (isset($this->prefix['prefix'])) {
			$route_key = $this->prefix['prefix'].$route_key;
		}

		$this->routers[strtolower($name)][$route_key] = new Router($add_route[key($add_route)]);
	}

	function getRouters()
	{

		$http_method = strtolower($this->getHttpMethod());

		$routers = $this->routers[$http_method];
		return $routers;
	}
}