<?php
/*
OHSCE_V0.2.0_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
*/
ini_set('memory_limit',"88M");//重置php可以使用的内存大小为88M
set_time_limit(0);
ob_implicit_flush(1);
include('loadohsce.php');
$trya='ohsce_server_Example ';
function example(&$socket,$buf,$from,$port,$zv){  //收到数据时的回调函数
	global $trya;
	echo $buf;
	 Ohsce_socketsend($socket,$trya.'hi '.$buf,0,0,$from,$port);
	return true;
}
Ohsce_eng_socket_server($ohsceserver,'udp',7628,'127.0.0.1','example');//创建一个TCP服务端资源 绑定127.0.0.1:7628 并传入回调函数
@Ohsce_eng_socket_server_runudp($ohsceserver); //开始运行