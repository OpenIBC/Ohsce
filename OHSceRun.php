<?php
/*
OHSCE_V0.2.0.2_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
*/
ini_set('memory_limit',"128M");//重置php可以使用的内存大小为128M
set_time_limit(0);
ob_implicit_flush(1);
include('loadohsce.php');
//error_reporting(0);
$ohsce_ml=getopt('r:m:');
$mode=$ohsce_ml['m'];
switch(strtolower($ohsce_ml['r'])){
case "1":
case "engine":
case "ohsce_engine":
include_once('./engine/Ohsce_engine.php');
break;
default:
	include_once($ohsce_ml['r']);
break;
}