<?php 

/**
* 
*/
class Router
{
	private $use;
	private $closure;

	function __construct($data)
	{
		if (is_array($data)) {
			$this->use = $data;
		} else if ($data instanceof Closure) {
			$this->closure = $data;
		}
	}

	/*
		返回 数组 或者 Closure
	*/
	function routeParse()
	{
		//解析路由
		if ($this->use) {
			list($controller, $action) = explode('@', $this->use['use']);
			$result = array('Controller' => $controller, 'Action'=> $action);
			
			$filter = array();
			if (isset($this->use['filter'])) {
				// 添加所有路由
				$filter = array('filter' => array());
				foreach ($this->use['filter'] as $filtername) {
					array_push($filter['filter'], $filtername);	
				}
				
			}

			$result = array_merge($this->use, $result, $filter);
		} else if ($this->closure){
			$result = $this->closure;
		}

		return 	$result;
	}

}