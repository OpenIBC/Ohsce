<?php
/*
OHSCE_V0.2.0_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
该文件禁止改名!否则可能会无法运行！
*/
if(!defined('IN_OHSCE')){
exit('Forbidden!-ohsce_wca_v0.0.4');
}
function ohsce_ext_ohscecomto($com=null){
	if($com==null){
		return false;
	}
	switch(OHSCE_OS){
	case "Windows":
	$exsms="mode ".$com.": to=on";
	Ohsce_exec($exsms,0);
	if(Ohsce_getos_64()){
    $exsms=OHSCE_ROOTDIR.'/ext/ohscecomto/ohscecomto_x64.exe -com '.$com;
	}else{
	$exsms=OHSCE_ROOTDIR.'/ext/ohscecomto/ohscecomto_x86.exe -com '.$com;
	}
	break;
	case "linux":
		$exsms="stty -F ".$com." min 0 time 10";
	break;
	case "OSX":
		$exsms="stty -F ".$com." min 0 time 10";
		break;
	default:
		return false;
	break;
	}
	Ohsce_exec($exsms,2);
	return true;
}