<?php
/*
OHSCE_V0.1.20_A
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
*/
define('OHSCE_MYIP', '127.0.0.1');//本机IP地址
define('OHSCE_PHPDIR', 'd:\php\5.4\php.exe');//您的PHP所在路径
define('OHSCE_OLMD_MADDRESS',"20");//OLMD内存起始
define('OHSCE_OLMD_MADDRESSBACKUPS',"10");//OLMD内存起始（备）
define('OHSCE_OLMD_MADDRESSPORT',"7698");//OLMD监听端口
define('OHSCE_OLMD_MADDRESSPASS',"ohsceolmdpassword"); //如果你不善于维护防火墙，那么你必须修改它
$OHSCE_PLdir=OHSCE_ROOTDIR.'\PcenterRun\\';//PCENTER直启目录
$OHSCE_pdefend=OHSCE_ROOTDIR.'\OHSceRun.php -r engine -m pdefend -p';
$OHSCE_pdefend_vbs=OHSCE_ROOTDIR.'\pdefend.vbs';
$OHSCE_pdefend_recalltime=5;//当二级守护进程死亡重新呼叫间隔，不要太小！