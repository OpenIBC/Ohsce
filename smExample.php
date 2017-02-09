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

Ohsce_eng_sm_creat($sm,60);                   //创建一块可复用的共享内存操作资源
again:
Ohsce_eng_sm_open($sm);                       //尝试打开
Ohsce_eng_sm_write($sm,'Hello OHSCE!');       //写入一段数据
echo Ohsce_eng_sm_read($sm);                  //读并显示
Ohsce_eng_sm_close($sm);                      //关闭该资源
sleep(3);
goto again;