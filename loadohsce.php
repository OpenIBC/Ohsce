<?php
/*
OHSCE_V0.1.25_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
该文件禁止改名!否则可能会无法运行！
*/
error_reporting(E_ALL);
if(!defined('IN_OHSCE')){
define('IN_OHSCE', TRUE);
}
if(file_exists('./config/oibc_sce_config.php')){
define('OHSCE_ROOTDIR',dirname(__FILE__));
}elseif(file_exists('./Ohsce/config/oibc_sce_config.php')){
define('OHSCE_ROOTDIR','/Ohsce/'.dirname(__FILE__));
}elseif(file_exists('./OHSCE/config/oibc_sce_config.php')){
define('OHSCE_ROOTDIR','/OHSCE/'.dirname(__FILE__));
}else{
	exit('Can not find Ohsce or OHSCE!');
	sleep(30);
}
define('OIBC_VERSON','0.1.24_beta');
include(OHSCE_ROOTDIR.'/config/oibc_sce_config.php');
include(OHSCE_ROOTDIR.'/config/oibc_drive_config.php');
include(OHSCE_ROOTDIR.'/lib/bts_little.php');
include(OHSCE_ROOTDIR.'/lib/oibc_sce_fuc.php');
include(OHSCE_ROOTDIR.'/lib/oibc_sce_eng.php');
include(OHSCE_ROOTDIR.'/lib/ohsce_Channel.php');
define('OHSCE_OS',Ohsce_getos($oibc_sce_os));
$ohsce_eng_socket_client_wait=0;
$ohsce_eng_serial_wait=3;
