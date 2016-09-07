<?php
/*
OHSCE_V0.1.20_A
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
*/
ini_set('memory_limit',"88M");//重置php可以使用的内存大小为64M
set_time_limit(0);
ob_implicit_flush(1);
include('loadohsce.php');
function example(&$socket,$buf,$len,$zv){  //收到数据时的回调函数
	echo $buf;
	Ohsce_socketwrite($socket,'hi '.$buf);
	return true;
}
function exampleaccept(&$socket,$ip,$port,$zv){  //新客户端到访时的回调函数
	Ohsce_socketwrite($socket,'Welcome'.$ip.':'.$port);
	return true;
}
Ohsce_eng_socket_server($ohsceserver,'tcp',7626,'127.0.0.1','example','exampleaccept');//创建一个TCP服务端资源 绑定127.0.0.1:7626 并传入回调函数
Ohsce_eng_socket_server_runtcp($ohsceserver); //开始运行