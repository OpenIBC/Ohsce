<?php
/*
OHSCE_V0.2.0_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
该文件禁止改名!否则可能会无法运行！
*/
if(file_exists('loadohsce.php')){
include('loadohsce.php');
}elseif(file_exists('./Ohsce/loadohsce.php')){
include('./Ohsce/loadohsce.php');
}elseif(file_exists('./ohsce/loadohsce.php')){
include('./ohsce/loadohsce.php');
}elseif(file_exists('./OHSCE/loadohsce.php')){
include('./OHSCE/loadohsce.php');
}else{
	exit('lodaohsce.php can not be found!-OHSCE '.OIBC_VERSON);
}
if($OHSCE_webapi!="on"){
	exit('Ohscewebapi has been closed!');
}
if($OHSCE_webapi_safe=="on"){
	if(!isset($_GET['token'],$_GET['key'])){
		exit('Forbidden!-OHSCE '.OIBC_VERSON);
	}
	if(ohsce_maketoken(trim($_GET['key']),$OHSCE_webapi_token)!=trim($_GET['token'])){
		exit('Forbidden!-OHSCE '.OIBC_VERSON);
	}
}
if(isset($_GET['xndrive'])){
	exit('This verson is not support XNDrive!');
}
if(!isset($_GET['drive'],$_GET['action'])){
	exit('drive&action is null! -OHSCE '.OIBC_VERSON);
}else{
	$ohsce_drive=trim($_GET['drive']);
	$ohsce_action=trim($_GET['action']);
}
if(!isset($_GET['data'])){
	$ohsce_data=null;
}else{
	$ohsce_data=trim($_GET['data']);
	$ohsce_data_b64=base64_decode($ohsce_data);
	if(($ohsce_data_b64!=null)and($ohsce_data_b64!=false)and($ohsce_data_b64!="")){
		$ohsce_data=$ohsce_data_b64;
	}
	unset($ohsce_data_b64);
}
if(!isset($ohsce_drive_list[$ohsce_drive])){
	exit('UnRegistered drive!-OHSCE '.OIBC_VERSON);
}
if(file_exists($ohsce_drive_list[$ohsce_drive])){
	$ohsce_DriveFile='./'.$ohsce_drive_list[$ohsce_drive].'ohsce_drive.php';
	include($ohsce_DriveFile);
}else{
	exit('DriveFile can not link!-OHSCE '.OIBC_VERSON);
}
if(bts_is_json($ohsce_action)){
	$ohsce_action=json_decode($ohsce_action,true);
}
if($ohsce_data!=null){
if(bts_is_json($ohsce_data)){
	$ohsce_data=json_decode($ohsce_data,true);
}
}
$ohsce_DriveName=str_replace('/','_',$ohsce_drive_list[$ohsce_drive].'ohsce');
$ohsce_rdata=$ohsce_DriveName($ohsce_drive,$ohsce_action,$ohsce_data);
if(isset($_GET['rejson'])){
echo json_decode($ohsce_rdata);
}else{
print_r($ohsce_rdata);
}