<?php
/*
OHSCE_V0.1.20_A
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
*/
error_reporting(E_ALL);
if(!defined('IN_OHSCE')){
define('IN_OHSCE', TRUE);
}
define('OHSCE_ROOTDIR',dirname(__FILE__));
define('OIBC_VERSON','0.1.1_alpha');
include(OHSCE_ROOTDIR.'/config/oibc_sce_config.php');
include(OHSCE_ROOTDIR.'/lib/bts_little.php');
include(OHSCE_ROOTDIR.'/lib/oibc_sce_fuc.php');
include(OHSCE_ROOTDIR.'/lib/oibc_sce_eng.php');
include(OHSCE_ROOTDIR.'/lib/ohsce_Channel.php');
define('OHSCE_OS',Ohsce_getos($oibc_sce_os));