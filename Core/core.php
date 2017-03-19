<?php 

// 入口文件
include_once 'Autoload.php';

Autoload::auto();

$app = new App();
$app->run('Web');