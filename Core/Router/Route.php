<?php 

/**
*	路由 
*/
class Route
{
	private $routers = array();
	private $groups = array();

	protected $route_group;
	protected $input = '';
	protected $request_type = '';
	protected $request_uri = '';
	protected $router_result = '';
	protected $web;

	function __construct(Web $web)
	{
		$this->setUri();
		$this->web = $web;
	}

	public function __call($name, $arguments)
	{
		$add_route = $this->addRoute($arguments);

		$route_key = key($add_route);
		$request_arr_v = $add_route[$route_key];
		$this->routers[$name][$route_key] = new Router($request_arr_v);
	}

	protected function addRoute($arguments)
	{
		if (count($arguments) != 2) {
			throw new Exception("路由设置必须有两个参数", 99);
		}

		$request_arr = array();

		// 判断路由第二参数是否多值 
		if (is_array($arguments[1])) {
			$request_arr_v = $arguments[1];	
		} else if(is_string($arguments[1])) {
			$request_arr_v = ['use' => $arguments[1]];
		} else if ($arguments instanceof Closure) {
			$request_arr_v = $arguments[1];
		}
		
		if (!isset($request_arr_v['use'])) {
			throw new Exception("路由参数需设置 use", 99);
		}
		
		
		$route_key = ltrim($arguments[0], '/');
		return array($route_key => $request_arr_v);

	}

	public function group($prefix ,Closure $closure)
	{
		$route_group = $this->setRouteGroup($prefix);
		call_user_func_array($closure, array($route_group));
	}

	protected function setRouteGroup($prefix)
	{
		$route_group = new RouteGroup($prefix);
		$this->groups[] = $route_group;
		return $route_group;
	}

	protected function setInput(Input $input)
	{	
		$this->input = $input;
	}
	
	protected function getHttpMethod()
	{
		$this->request_type = strtolower($_SERVER['REQUEST_METHOD']);
		return $this->request_type;
	}

	// 获取上的路由地址
	protected function setUri()
	{
		$this->request_uri = trim($_SERVER['REQUEST_URI'], '/');
		return $this->request_uri;
	}

	protected function getRouters()
	{
		$http_method = strtolower($this->getHttpMethod());
		$routers = $this->routers[$http_method];


		foreach ($this->groups as $route_group) {
			$routers = array_merge($routers, $route_group->getRouters());
		}
		return $routers;
	}

	public function parseRouter()
	{
		$routers = $this->getRouters();
		
		if (!isset($routers[$this->request_uri])) {
			throw new Exception("不存在路由".$this->request_uri, 99);	
		}
		
		$router = $routers[$this->request_uri];

		$this->router_result = $router->routeParse();
		
	}

	// 路由
	public function run()
	{
		if (!$this->router_result) {
			throw new Exception("路由解析出错", 99);
		}

		if ($this->router_result instanceof Closure) {

			call_user_func($this->router_result);
		} else {

			define('MODULE', $this->router_result['Controller']);
			define('ACTION', $this->router_result['Action']);
			
			// 如果有路由过滤
			if (isset($this->router_result['filter'])) {
				$route_filter = $this->router_result['filter'];
				$route_filter->run();
			}

			$this->web->run();
		}
	}
}