<?php
/*
OHSCE_V0.2.0_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
该文件禁止改名!否则可能会无法运行！

WCA 0.0.4
*/
if(!defined('IN_OHSCE')){
exit('Forbidden!-ohsce_wca_v0.0.5');
}
function drive_wca_ohsce($drivename,$inaction=null,$indata=null){
	if(empty($inaction)){
		return 'Forbidden! -ohsce_wca_v0.0.1';
	}
	switch($inaction){
		case "w":
			$flags=2;
			break;
		default:
			$flags=1;
		break;
	}
	
	if(!isset($_GET['com'])){
		return 'Forbidden! Undefind Com!';
	}else{
		if(!file_exists(trim($_GET['com']))){
			return 'Forbidden! Com can not be find!';
		}
	}
	$comfirst=false;
	if(!isset($_GET['baud'])){
		$gbaud=9600;
	}else{
		$gbaud=intval(trim($_GET['baud']));
		$comfirst=true;
	}
	if(!isset($_GET['parity'])){
		$gparity='n';
	}else{
		$gparity=trim($_GET['parity']);
		$comfirst=true;
	}
	if(!isset($_GET['cdata'])){
		$gdata=8;
	}else{
		$gdata=intval(trim($_GET['cdata']));
		$comfirst=true;
	}
	if(!isset($_GET['cstop'])){
		$gstop=1;
	}else{
		$gstop=intval(trim($_GET['cstop']));
		$comfirst=true;
	}
	if(!isset($_GET['fc'])){
		$gfc='none';
	}else{
		$gfc=trim($_GET['fc']);
		$comfirst=true;
	}
	if(!isset($_GET['init'])){
		$ginit=true;
	}else{
		$ginit=trim($_GET['init']);
		if($ginit=="false"){
			$ginit=false;
		}else{
			$ginit=true;
		}
	}
	if(($init==true)&&($comfirst==true)){
		$comfirst=true;
	}else{
		$comfirst=false;
	}
	Ohsce_eng_serial_creat($hscecom,trim($_GET['com']),$flags,0,$baud=$gbaud,$parity=$gparity,$data=$gdata,$stop=$gstop,$gfc='none'); 
	openagain:
    if(!Ohsce_eng_serial_open($hscecom,$comfirst)['open']){
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
		case "w":
			if(!isset($_GET['comdata'])){
			return 'Undefind Comdata!';
		    }
			$comdata=trim($_GET['comdata']);
			if((strlen($comdata)%2)!=0){
				$comdata='0'.$comdata;
			}
			Ohsce_eng_serial_write($hscecom,$comdata,true);
            $redata=true;
			goto freturn;
		case "r":
			if(!isset($_GET['comdata'])){
			return 'Undefind Comdata!';
		    }
			$comdata=trim($_GET['comdata']);
			if((strlen($comdata)%2)!=0){
				$comdata='0'.$comdata;
			}
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