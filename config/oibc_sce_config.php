<?php
/*
OHSCE_V0.2.0.2_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
该文件禁止改名!否则可能会无法运行！
*/
define('OHSCE_MYIP', '127.0.0.1');//本机IP地址   实际的用户网地址
define('OHSCE_MYIP_SYSTEM', '127.0.0.1');//本机IP地址（系统内）   实际的设备网地址
define('OHSCE_PHPDIR', 'php');//您的PHP所在路径  如c:/phpdir/php.exe
define('OHSCE_OLMD_MADDRESS',"20");//OLMD内存起始
define('OHSCE_OLMD_MADDRESSBACKUPS',"10");//OLMD内存起始（备）
define('OHSCE_OLMD_MADDRESSPORT',"7698");//OLMD监听端口
define('OHSCE_OLMD_MADDRESSPORTY',"7699");//OLMD监听端口(对外读取)
define('OHSCE_OLMD_MADDRESSPASS',"ohsceolmdpassword"); //OLMD写入密钥，如果你不善于维护防火墙，那么你必须修改它
define('OHSCE_OLMD_MADDRESSPASSY',"password");//这是OLMD对外读取密钥，如果你不善于维护防火墙，那么你必须修改它
//define('OHSCE_ROOTDIR',‘’);
$OHSCE_PLdir=OHSCE_ROOTDIR.'/PcenterRun/';//PCENTER直启目录
$OHSCE_pdefend=OHSCE_ROOTDIR.'/OHSceRun.php -r engine -m pdefend -p';
$OHSCE_pdefend_vbs=OHSCE_ROOTDIR.'/pdefend.vbs';
$OHSCE_pdefendC_vbs=OHSCE_ROOTDIR.'/pdefendC.vbs';
$OHSCE_pdefend_recalltime=5;//当二级守护进程死亡重新呼叫间隔，不要太小！
//---------------WebApis
$OHSCE_webapi="on";//on-开启  off-关闭 WEBAPI
$OHSCE_webapi_safe="off";//on-开启验证Token off-不验证Token 
$OHSCE_webapi_token="webapitoken";//Token种子，必须修改。
