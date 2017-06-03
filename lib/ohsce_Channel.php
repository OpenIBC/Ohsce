<?php
/*
OHSCE_V0.2.0.2_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
该文件禁止改名!否则可能会无法运行！
*/
function ohsce_channel_server_creat(&$channel,$date){
	if(!is_array($date)){
		$res[1]='date must be array!';
		goto terror;
	}
	if(!isset($date["mode"])){
		$date["mode"]='fastsocket';
	}else{
	$date["mode"]=strtolower($date["mode"]);
	}
	switch($date["mode"]){
		case "fastsocket":
		case "udp":
		case "1":
			if(!isset($date['cport'],$date['cip'])){
			$res[1]='date var lost';
			goto terror;
		    }
			goto udp;
		case "memory":
		case "2":
			goto memory;
		default:
			$res[1]='Mode is not ';
		goto terror;
	}
	udp:
		if(false==Ohsce_createSocket($channeludp,'udp',0)[0]){
		$res[1]='channel creat faild!';
		goto terror;
	    }
		Ohsce_socketbind($channeludp,$date['cport'],$date['cip']);
		$channel['mode']='udp';
		$channel['cs']='server';
		$channel['socket']=$channeludp;
		$channel['ip']=$date['cip'];
		$channel['port']=$date['cport'];
		$channel['listen']=true;
		$channel['wrsize']=2046;
		return $channel;
	memory:
	terror:
	return false;
}
function ohsce_channel_client_creat(&$channel,$date){
	if(!is_array($date)){
		$res[1]='date must be array!';
		goto terror;
	}
	if(!isset($date["mode"])){
		$date["mode"]='fastsocket';
	}else{
	$date["mode"]=strtolower($date["mode"]);
	}
	switch($date["mode"]){
		case "fastsocket":
		case "udp":
		case "1":
			if(!isset($date['cport'])){
			$date['cport']=null;
		    }
			if(!isset($date['cip'])){
			$date['cip']=null;
		    }
			goto udp;
		case "memory":
		case "2":
			goto memory;
		default:
			$res[1]='Mode is not ';
		goto terror;
	}
	udp:
		if(false==Ohsce_createSocket($channeludp,'udp',0)[0]){
		$res[1]='channel creat faild!';
		goto terror;
	    }
		if(($date['cport']!=null)and($date['cip']!=null)){
		Ohsce_socketbind($channeludp,$date['cport'],$date['cip']);
		}
		$channel['mode']='udp';
		$channel['cs']='client';
		$channel['socket']=$channeludp;
		$channel['ip']=$date['cip'];
		$channel['port']=$date['cport'];
		$channel['listen']=false;
		$channel['wrsize']=2046;
		return $channel;
	memory:
	terror:
	return false;
}
function ohsce_channel_read(&$channel,&$data,&$from=null,&$port=null){
	if(!is_array($channel)){
		$res[1]='channl error1!';
		goto terror;
	}
	if(!isset($channel['mode'])){
		$res[1]='channl error2!';
		goto terror;
	}	
	switch(strtolower($channel['mode'])){
		case "udp":
			if(!isset($channel['cs'],$channel['socket'],$channel['wrsize'])){
			$res[1]='channl error3!';
		    goto terror;
		    }
			goto udp;
		default:
			$res[1]='channl error4!';
		goto terror;
	}
	udp:
		if(!@Ohsce_socketrecvfrom($channel['socket'],$data,$channel['wrsize'],0,$from,$port)[0]){
		$res[1]='channl recv error!';
		goto terror;
	    }
		$res[0]=$channel;
		$res[1]=$data;
		$res[2]=$from;
		$res[3]=$port;
		return $res;
	terror:
	return false;
}
function ohsce_channel_write(&$channel,&$data,$to=null,$port=null){
	if(!is_array($channel)){
		$res[1]='channl error1!';
		goto terror;
	}
	if(!isset($channel['mode'])){
		$res[1]='channl error2!';
		goto terror;
	}	
	switch(strtolower($channel['mode'])){
		case "udp":
			if(!isset($channel['cs'],$channel['socket'],$channel['wrsize'])){
			$res[1]='channl error3!';
		    goto terror;
		    }
			if((is_null($to))or(is_null($port))){
			$res[1]='TO&PORT IS NULL!';
		    goto terror;
			}
			goto udp;
		default:
			$res[1]='channl error4!';
		goto terror;
	}
	udp:
		if(!Ohsce_socketsend($channel['socket'],$data,0,0,$to,$port)[0]){
		$res[1]='channl send error!';
		goto terror;
	    }
		$res[0]=$channel;
		$res[1]=$data;
		$res[2]=$to;
		$res[3]=$port;
		return $res;
	terror:
	return false;
}
function ohsce_reChannel(&$channel,$rdate,$to=null,$port=null){
ohsce_smEncode($rdate);
if((is_null($to))or(is_null($port))){
ohsce_channel_write($channel,$rdate);
return true;
}
ohsce_channel_write($channel,$rdate,$to,$port);
return true;
}