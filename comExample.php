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
include('loadohsce.php');
ohsce_ext_ohscecomto("com1"); //设置串口COM1的超时时间为1
Ohsce_eng_serial_creat($hscecom,"com1"); //OHSCE会默认为你创建一个 9600,n,8,1 写读的串口资源
Ohsce_eng_serial_open($hscecom); //一旦通过该函数成功开启了串口，此串口就被OHSCE进程占用了 此时串口资源变为可用状态
Ohsce_eng_serial_write($hscecom,"01030001000415c9",true);//向串口设备发送数据 以16进制发送
Ohsce_eng_serial_read($hscecom,$data,null,true); // 读取串口数据 返回数据长度为未知 以16进制返回
echo $data; //输出数据
sleep(30);