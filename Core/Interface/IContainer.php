<?php

/**
* 容器接口
*/
interface IContainer
{
	// 赋值 对象
	public function instance();

	// make出一个对象
	public function make();

	// 自动以来注入
	public function bulid();

	// 注册对象
	

}