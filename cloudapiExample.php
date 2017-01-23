<?php
/*
OHSCE_V0.1.27_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
*/
ini_set('memory_limit',"88M");//重置php可以使用的内存大小为64M
set_time_limit(0);
ob_implicit_flush(1);
include('loadohsce.php');

error_reporting(1);
print_r(ohsce_drive_cloud_modbus($sr,"RTU","01",'01',"40001","0002"));

sleep(30);

