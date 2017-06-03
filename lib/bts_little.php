<?php
/*
OHSCE_V0.2.0.2_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
该文件禁止改名!否则可能会无法运行！
*/
function bts_bas_valueref($arr){ 
        $refs = array(); 
        foreach($arr as $key => $value) 
        $refs[$key] = &$arr[$key]; 
        return $refs; 

    }
function bts_bas_array2bin($arr){
	$res="";
	foreach($arr as $arrp){
		$nhex=dechex(intval($arrp));
		if(strlen($nhex)<2){
			$nhex='0'.$nhex;
		}
		$res .= hex2bin($arrp);
	}
	return $res;
}
function bts_bas_array2bind($arr){
	$res="";
	foreach($arr as $arrp){
		$nhex=dechex(intval($arrp));
		if(strlen($nhex)<2){
			$nhex='0'.$nhex;
		}
		$res .= hex2bin($nhex);
	}
	return $res;
}
function bts_is_json($str){  
    if(is_null(json_decode($str))){
		return false;
	}else{
		return true;
	}
}
function bts_bas_data2hex($data){
	return str_split($data,2);
}
function bts_hex2hex($hex,$len='x'){
	$hlen=strlen($hex);
	if($len=="x"){
		if(($hlen%2)!=0){
			$hex='0'.$hex;
			return $hex;
		}
	}
	$hlenc=($len*2)-$hlen;
	if($hlenc>0){
		    do{
			$hex='0'.$hex;
			$hlenc=$hlenc-1;
		    }while($hlenc>0);
		}
	return $hex;
}
function bts_str2bin($str){
	$stra=str_split($str);
	$res='';
	foreach($stra as &$straa){
		$res = $res.hex2bin(dechex(ord($straa)));
	}
	return $res;
}
function bts_bin2str($str){
	$str=bin2hex($str);
	$stra=str_split($str,2);
	$res='';
	foreach($stra as &$straa){
		echo $straa.'|';
		$res = $res.chr(hexdec($straa));
	}
	return $res;
}