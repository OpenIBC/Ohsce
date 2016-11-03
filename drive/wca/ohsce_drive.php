<?php
/*
OHSCE_V0.1.23_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
该文件禁止改名!否则可能会无法运行！
*/
if(!defined('IN_OHSCE')){
exit('Forbidden!-ohsce_wca_v0.0.1');
}
function drive_wca_ohsce($drivename,$inaction=null,$indata=null){
	if(empty($inaction)){
		return 'Forbidden! -ohsce_wca_v0.0.1';
	}
	if(!isset($_GET['com'])){
		return 'Forbidden! Undefind Com!';
	}
	Ohsce_eng_serial_creat($hscecom,trim($_GET['com'])); 
	openagain:
    if(!Ohsce_eng_serial_open($hscecom,false)['open']){
		if(!isset($tryopen)){
			$tryopen=1;
		}else{
			$tryopen++;
		}
		if($tryopen>10){
			return 'Can not open com!';
		}
		sleep(1);
		goto openagain;
	}
	switch($inaction){
		case "wr":
			if(!isset($_GET['comdata'])){
			return 'Undefind Comdata!';
		    }
			$comdata=trim($_GET['comdata']);
			if((strlen($comdata)%2)!=0){
				$comdata='0'.$comdata;
			}
			Ohsce_eng_serial_write($hscecom,$comdata,true);
            Ohsce_eng_serial_read($hscecom,$redata,null,true);
			goto freturn;
		default:
			return 'UnDefind Action!';
		    goto terror;
	}
	freturn:
		Ohsce_eng_serial_close($hscecom);
	    return $redata;
	terror:
		return 'An error!';
}