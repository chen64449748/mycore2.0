<?php

// 自动加载
require_once __DIR__.'/autoload.php';

// 读取路由
require_once __DIR__.'/routes.php';

$app->run($route);


