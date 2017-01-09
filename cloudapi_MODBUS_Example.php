<?php
/*
OHSCE_V0.1.26_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
*/
ini_set('memory_limit',"88M");//重置php可以使用的内存大小为64M
set_time_limit(0);
ob_implicit_flush(1);
include('loadohsce.php');

Ohsce_eng_serial_creat($hscecom,"com1");
Ohsce_eng_serial_open($hscecom); 
//ohsce_drive_cloud_modbus($hscecom,"RTU","01",'01',"40001","0002");//调用云MODBUS驱动 方式一
Ohsce_eng_serial_write($hscecom,ohsce_drive_cloud_modbus(null,"RTU","01",'01',"40001","0002")['data'],false);//调用云MODBUS驱动  方式二
Ohsce_eng_serial_close($hscecom);

sleep(30);

