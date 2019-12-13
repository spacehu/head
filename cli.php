<?php

//确保在连接客户端时不会超时
set_time_limit(0);
date_default_timezone_set('PRC');
include_once('env.php');
include_once('./mod/init.php');
include_once('./mod/autoload.php');
$config = include_once('./config/config.php');
$run = new mod\init($config);
$run->cli("mq","checkMQ");
//$run->cli("RpcClient","client");