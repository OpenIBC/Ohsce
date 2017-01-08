<?php
/*
OHSCE_V0.1.25_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
*/
ini_set('memory_limit',"88M");//重置php可以使用的内存大小为64M
set_time_limit(0);
ob_implicit_flush(1);
include('loadohsce.php');

//print_r(ohsce_ext_ohscecloudapi('modbus',array('modbus'=>'RTU','do'=>'check','data'=>'0103020017F84A')));
/*
print_r(ohsce_ext_ohscecloudapi('modbus',array('modbus'=>'RTU','do'=>'01','address'=>'01','start'=>'00000','len'=>'0002')));
sleep(30);
*/
error_reporting(1);
print_r(ohsce_drive_cloud_modbus($sr,"RTU","01",'01',"40001","0002"));

sleep(30);

/*
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt=base64_encode(json_encode(array('modbus'=>'RTU','do'=>'check','data'=>'0103020017F84A')));
fwrite($myfile, $txt);
fclose($myfile);
sleep(10);
*/
