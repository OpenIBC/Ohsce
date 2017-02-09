<?php
/*
OHSCE_V0.2.0_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
*/
ini_set('memory_limit',"88M");//重置php可以使用的内存大小为64M
set_time_limit(0);
ob_implicit_flush(1);
error_reporting(0);
include('loadohsce.php');
Ohsce_eng_socket_client($ohsceclient,'tcp',7626,'127.0.0.1'); //创建一个TCP客户端资源并连接27.0.0.1:7626
Ohsce_socketsend($ohsceclient['socket'],"\x01\x03\x00\x01\x00\x04\x15\xc9");  //发送数据
//Ohsce_socketsend($ohsceclient['socket'],array('in'=>"01030001000415c9",'bin'=>true));
echo Ohsce_socketread($ohsceclient['socket'],1024)[1]; //收取回复数据
sleep(30);
