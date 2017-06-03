<?php
/*
OHSCE_V0.2.0.2_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
*/
function Ohsce_eng_socket_client(&$res,$protocol,$port,$ip=null,$AF='ipv4',$sync=true,$mode='defalut',$space=0){
	$protocol=strtoupper($protocol);
	$port=intval($port);
	$mode=strtolower($mode);
	$AF=strtolower($AF);
	if(is_null($ip)) $ip=OHSCE_MYIP;
	if((is_null($ip))or($ip=="")) $ip=OHSCE_MYIP;
	if($sync){
		$block=1;
	}elseif(false==$sync){
		$block=0;
	}
	switch($mode){
		case 'default':
			default:
			if(!isset($block)) $block=1;
		    $reuse=null;
			$rtime=2;
			$stime=3;
			$rtimeu=0;
			$stimeu=0;
	}
	if(!Ohsce_createSocket($socket,$protocol,$block,true,$rtime,$stime,$rtimeu,$stimeu,$AF)[0]){
		$res['msg']='OibcSceCanNotCreatSocket_OibcSceV'.Ohsce_geterror($socket).OIBC_VERSON;
		goto terror;
	}
	if($protocol=="TCP"){
	if(!Ohsce_socketconnect($socket,$ip,$port)){
		$res['msg']='OibcSceCanNotConnectTO'.$ip.':'.$port.'  _OibcSceV'.Ohsce_geterror($socket).OIBC_VERSON;
		goto terror;
	}
	}
	$res['connect']=true;
	$res['socket']=$socket;
	$res['AF']=$AF;
	$res['protocol']=$protocol;
	$res['ip']=$ip;
	$res['port']=$port;
	$res['sync']=$sync;
	$res['reuse']=$reuse;
	$res['rtime']=$rtime;
	$res['stime']=$stime;
	$res['rtimeu']=$rtimeu;
	$res['stimeu']=$stimeu;
	$res['last']=microtime()-100;
	if($space!="0") goto setspace;
	global $ohsce_eng_socket_client_wait;
	if(isset($ohsce_eng_socket_client_wait)){
	$res['space']=intval($ohsce_eng_socket_client_wait);
	}else{
	$ohsce_eng_socket_client_wait=0;
	setspace:
	$res['space']=intval($space);
	}
	terror:
		$res['connect']=false;
	return $res;
}
function Ohsce_eng_socket_client_setspace($spacetime=0){
	if($spacetime<0){
		return false;
	}
	global $ohsce_eng_socket_client_wait;
	$ohsce_eng_socket_client_wait=intval($spacetime);
	return true;
}
function Ohsce_eng_socket_server(&$res,$protocol,$port,$ip=null,$fuclist,$callbackaccept=null,$AF='ipv4',$sync=true,$mode='defalut',$max=null){
	/*0.2.0之后移除callbackaccept输入*/
	if(is_array($fuclist)){
		$callback=$fuclist['callback'];
		$callbackaccept=$fuclist['accept'];
		if(isset($fuclist['fap'])){
		$ohscefap=$fuclist['fap'];
		}else{
		$ohscefap=null;
		}
	}else{
		$callback=$fuclist;
		$ohscefap=null;
	}
	/*0.2.0之后移除callbackaccept输入*/
	$protocol=strtoupper($protocol);
	Ohsce_casepr($protocol,$res['read']);
	$port=intval($port);
	$mode=strtolower($mode);
	$AF=strtolower($AF);
	if((is_null($ip))or($ip=="")) $ip=OHSCE_MYIP;
	if($sync){
		$block=1;
	}elseif(false==$sync){
		$block=0;
	}
	switch($mode){
		case 'default':
			default:
			if(!isset($block)) $block=1;
		    $reuse=null;
			$rtime=2;
			$stime=3;
			$rtimeu=0;
			$stimeu=0;
			$max=null;
			//---------------
			$len=1024;
			$rbinadry=true;
			$rfast=true;
			$rflags=0;
	}
	if(!Ohsce_createSocket($socket,$protocol,$block,$reuse,$rtime,$stime,$rtimeu,$stimeu,$AF)[0]){
		$res['msg']='OibcSceCanNotCreatSocket_OibcSceV'.Ohsce_geterror($socket).OIBC_VERSON;
		goto terror;
	}
	Ohsce_socketbind($socket,$port,$ip);
	//Ohsce_socketsetbuff($socket,0,0,"MAX");
	$res['connect']=true;
	$res['socket']=$socket;
	$res['AF']=$AF;
	$res['protocol']=$protocol;
	$res['ip']=$ip;
	$res['port']=$port;
	$res['sync']=$sync;
	$res['reuse']=$reuse;
	$res['rtime']=$rtime;
	$res['stime']=$stime;
	$res['rtimeu']=$rtimeu;
	$res['stimeu']=$stimeu;
	$res['max']=$max;
	$res['callback']=$callback;
	$res['callbackaccept']=$callbackaccept;
	$res['fap']=$ohscefap;
	$res['last']=microtime()-100;
	$res['space']=0;
	return $res;
	terror:
		$res['connect']=false;
	return $res;
}
function Ohsce_eng_socket_server_close(&$res){
	Ohsce_socketclose($res['socket'],true);
	$res['connect']=false;
	return $res;
}
function Ohsce_eng_socket_reConnect(&$res){
	if($res['protocol']=="TCP"){
	if(!Ohsce_socketconnect($res['socket'],$res['ip'],$res['port'])){
		$res['msg']='OibcSceCanNotConnectTO'.$res['ip'].':'.$res['port'].'  _OibcSceV'.Ohsce_geterror($res['socket']).OIBC_VERSON;
		return false;
	}else{
		return true;
	}
	}else{
		return true;
	}
}
function oibc_sce_socket_send(&$oibc_sce,$in,$len=null,$mode=null,$to=null,$port=null,$flags=0,$fast=null){
	$ohsce_eng_socket_client_wait=$oibc_sce['space'];
	$mnow=microtime();
	if($ohsce_eng_socket_client_wait!=0){
	if($ohsce_eng_socket_client_wait>1){
	$mnowc=$mnow-$oibc_sce['last'];
	if($mnowc<$ohsce_eng_socket_client_wait){
		$mnowc=$ohsce_eng_socket_client_wait-$mnowc;
		usleep($mnowc);
		unset($mnowc);
	}
	}
	}else{
	$ohsce_eng_socket_client_wait=0;
	}
	if(null==$len){
		$len=strlen($in);
	}
	if(null==$fast){
		$fast=false;
	}
	if(strtolower($oibc_sce['protocol'])=="udp"){
		if((is_null($to))or(is_null($port))){
			goto terror;
		}
		$mode='send';
	}
	switch($mode){
		case "send":
			goto send;
		case "write":
			goto write;
		default:
			goto write;
	}
	send:
	if((null==$to)and(null==$port)){
    if(Ohsce_socketsend($oibc_sce['socket'],$in,$len,$flags,0,0,$fast)[0]){
	goto js;
	}
	}else{
		if((null!=$to)and(null!=$port)){
	if(Ohsce_socketsend($oibc_sce['socket'],$in,$len,$flags,$to,$port,$fast)[0]){
	goto js;
	}
		}
		goto terror;
	}
	write:
		if(Ohsce_socketwrite($oibc_sce['socket'],$in,$len,$fast)[0]){
		goto js;
	    }
		goto terror;
	js:
		return true;
	terror:
		return false;
}
function oibc_sce_socket_recv(&$oibc_sce,$in=null,$len=null,$mode='recv'){
	switch($mode){
	case "read":
	$oibc_jg=Ohsce_socketread($oibc_read_socket,8219);
	break;
	case "recv":
	default:
	Ohsce_socketrecv($oibc_sce['socket'],$oibc_jg);
	break;
	}
	if($oibc_jg==null){
			$oibc_jg[0]=false;
	}
	if($oibc_jg[0]==false){
		return false;
	}
	return $oibc_jg[1];
}
function Ohsce_eng_socket_server_runtcp($Ohsce,$stop=null,$speed=0,$callstop=false){
	if((is_null($stop))or($stop=="")){
		$stop=0;
	}
	if(($callstop!=false)or(substr($callstop,0,7)=="oibcmix")){
		$memo=shmop_open(dechex(substr($callstop,7,5)),"c",0644,100);
	}
	if(is_null($Ohsce['max'])){
	if(!socket_listen($Ohsce['socket'])) goto terror;
	}else{
	if(!socket_listen($Ohsce['socket'],intval($Ohsce['max']))) goto terror;
	}
	echo $Ohsce['socket'];
	$oibc_clients = array($Ohsce['socket']);
	$oibc_skey = array_search($Ohsce['socket'],$oibc_clients);
	$oibc_clients_id_ip[$oibc_skey]=$Ohsce['ip'];
    $oibc_clients_id[$oibc_skey]=$Ohsce['socket'];
	$oibc_clients_zv=array("clients"=>&$oibc_clients,"ip"=>&$oibc_clients_id_ip,"id"=>&$oibc_clients_id);
	$i=0;
	$speed=10*$speed;
	starloop:
	do{
	if(!is_null($Ohsce['fap'])){
		$Ohsce['fap']($oibc_clients_zv);
	}
	$oibc_read=$oibc_clients;
	$oibc_write=null;
	$oibc_except=null;
	if(socket_select($oibc_read,$oibc_write,$oibc_except,0)<1){
		goto starloop;
	}
	if($Ohsce['sync']){
		if(in_array($Ohsce['socket'],$oibc_read)){	
		$oibc_newsocket=socket_accept($Ohsce['socket']);
		$oibc_clients[]=$oibc_newsocket;
		socket_getpeername($oibc_newsocket,$oibc_sip,$oibc_spo);
		$oibc_runACB_res=call_user_func_array($Ohsce['callbackaccept'],bts_bas_valueref(array($oibc_newsocket,$oibc_sip,$oibc_spo,$oibc_clients_zv)));
		if($oibc_runACB_res==false){
			$oibc_dskey = array_search($oibc_newsocket,$oibc_clients);
			if($oibc_dskey==false){
			unset($oibc_clients[$oibc_dskey]);
			}
			unset($oibc_dskey);
		}
		$oibc_clients_id[$i]=$oibc_newsocket;
		$oibc_clients_id_ip[$i]=$oibc_sip;
		$oibc_skey = array_search($Ohsce['socket'],$oibc_read);
		unset($oibc_read[$oibc_skey]);
	    }
	}else{
		if(in_array($Ohsce['socket'],$oibc_read)){
		asyncagain:
		$oibc_newsocket=socket_accept($Ohsce['socket']);
		if($oibc_newsocket==false){
			usleep(100);
			goto asyncgoon;
		}
		$oibc_clients[]=$oibc_newsocket;
		socket_getpeername($oibc_newsocket,$oibc_sip,$oibc_spo);
		$oibc_runACB_res=call_user_func_array($Ohsce['callbackaccept'],bts_bas_valueref(array($oibc_newsocket,$oibc_sip,$oibc_spo,$oibc_clients_zv)));
		if($oibc_runACB_res==false){
			$oibc_dskey = array_search($oibc_newsocket,$oibc_clients);
			if($oibc_dskey==false){
			unset($oibc_clients[$oibc_dskey]);
			}
			unset($oibc_dskey);
		}
		$oibc_clients_id[$i]=$oibc_newsocket;
		$oibc_clients_id_ip[$i]=$oibc_sip;
		goto asyncagain;
		asyncgoon:
		$oibc_skey = array_search($Ohsce['socket'],$oibc_read);
		unset($oibc_read[$oibc_skey]);
	    }
	}
	foreach($oibc_read as $oibc_read_socket){
		$buf=null;
		switch($Ohsce['read']){
		case "1":
		$oibc_jg=Ohsce_socketread($oibc_read_socket,8219);
		break;
		case "0":
		default:
		Ohsce_socketrecv($oibc_read_socket,$oibc_jg);
		break;
		}
		if($oibc_jg==null){
			$oibc_jg[0]=false;
		}
		$oibc_skeyy = array_search($oibc_read_socket,$oibc_clients_id);
		if($oibc_jg[0]==false){
			$oibc_skey = array_search($oibc_read_socket,$oibc_clients);
			unset($oibc_clients[$oibc_skey]);
			unset($oibc_clients_id_ip[$oibc_skeyy]);
			unset($oibc_clients_id[$oibc_skeyy]);
			if(isset($oibc_clients_id_panbody[$oibc_skeyy])){
				unset($oibc_clients_id_panbody[$oibc_skeyy]);
			}
			Ohsce_socketclose($oibc_read_socket,true);
			continue;
		}else{
			$oibc_runACB_res=call_user_func_array($Ohsce['callback'],bts_bas_valueref(array($oibc_read_socket,$oibc_jg[1],$oibc_jg[2],$oibc_clients_zv)));
			if($oibc_runACB_res==false){
			$oibc_dskey = array_search($oibc_read_socket,$oibc_clients);
			if($oibc_dskey==false){
			unset($oibc_clients[$oibc_dskey]);
			}
			unset($oibc_dskey);
		    }
		}
	}
	$i++;
	if(($callstop!=false)or(substr($callstop,0,7)=="oibcmix")){
		if(shmop_read($memo,0,0)){
			goto stop;
		}
		if(substr($callstop,0,7)!="oibcmix"){
			usleep($speed);
			goto starloop;
		}
	}
	usleep($speed);
    }while(($stop==0)or($i<$stop));
	stop:
	$res['run']=false;
	if(!isset($res['msg'])) $res['msg']='stop'.OIBC_VERSON;
	return $res;
	terror:
	js:
	$res['run']=false;
	if(!isset($res['msg'])) $res['msg']='guest'.OIBC_VERSON;
	return $res;
}
function Ohsce_eng_socket_server_runudp($Ohsce,$stop=null,$speed=1,$callstop=false){
	if((is_null($stop))or($stop=="")){
		$stop=0;
	}
	if(($callstop!=false)or(substr($callstop,0,7)=="oibcmix")){
		$memo=shmop_open(dechex(substr($callstop,7,5)),"c",0644,100);
	}
	echo $Ohsce['socket'];
	$oibc_clients_zv=array("ohscesocket"=>$Ohsce['socket']);
	$i=0;
	$speed=10*$speed;
	starloop:
	do{
	if(!is_null($Ohsce['fap'])){
		$Ohsce['fap']($oibc_clients_zv);
	}
	$oibc_jg=Ohsce_socketrecvfrom($Ohsce['socket'],$buf,0,0,$from,$port);
	if($oibc_jg[0]==false){
		unset($oibc_jg);
		//usleep(10);
		goto starloop;
	}
	$oibc_runACB_res=call_user_func_array($Ohsce['callback'],bts_bas_valueref(array($Ohsce['socket'],$buf,$from,$port,$oibc_clients_zv)));
	$i++;
	if(($callstop!=false)or(substr($callstop,0,7)=="oibcmix")){
		if(shmop_read($memo,0,0)){
			goto stop;
		}
		if(substr($callstop,0,7)!="oibcmix"){
			usleep($speed);
			goto starloop;
		}
	}
	usleep($speed);
    }while(($stop==0)or($i<$stop));
	stop:
	$res['run']=false;
	if(!isset($res['msg'])) $res['msg']='stop'.OIBC_VERSON;
	return $res;
	terror:
	js:
	$res['run']=false;
	if(!isset($res['msg'])) $res['msg']='guest'.OIBC_VERSON;
	return $res;
}
function Ohsce_eng_serial_creat(&$res,$com,$flags="1",$mode=0,$baud=9600,$parity='n',$data=8,$stop=1,$fc='none',$xon='off',$to='un',$octs='off',$odsr='off',$idsr='off',$dtr='on',$rts='off',$space=0){
	if(OHSCE_OS=="Windows") $baud=Ohsce_getbaud($baud);
	$res['open']=false;
	$res['comr']=null;
	$res['com']=$com;
	$res['flags']=$flags;
	$res['baud']=$baud;
	$res['parity']=$parity;
	$res['data']=$data;
	$res['stop']=$stop;
	$res['fc']=$fc;
	$res['xon']=$xon;
	$res['to']=$to;
	$res['octs']=$octs;
	$res['odsr']=$odsr;
	$res['idsr']=$idsr;
	$res['dtr']=$dtr;
	$res['rts']=$rts;
	$res['mode']=$mode;
	$res['last']=microtime()-100;
	if($space!="0") goto setspace;
	global $ohsce_eng_serial_wait;
	if(isset($ohsce_eng_serial_wait)){
	$res['space']=intval($ohsce_eng_serial_wait);
	}else{
	$ohsce_eng_serial_wait=0;
	setspace:
	$res['space']=intval($space);
	}
	return $res;
}
function Ohsce_eng_serial_setspace($spacetime=0){
	if($spacetime<0){
		return false;
	}
	global $ohsce_eng_serial_wait;
	$ohsce_eng_serial_wait=intval($spacetime);
	return true;
}
function Ohsce_eng_serial_open(&$oibc,$set=true){
	if($set!=false){
	Ohsce_comset($oibc['com'],$oibc['baud'],$oibc['parity'],$oibc['data'],$oibc['stop'],$oibc['fc'],$oibc['xon'],$oibc['to'],$oibc['octs'],$oibc['odsr'],$oibc['idsr'],$oibc['dtr'],$oibc['rts']);
	}
	Ohsce_comopen($oibc['comr'],$oibc['com'],$oibc['flags'],$oibc['mode']);
	if($oibc['comr']==false){
	$oibc['open']=false;
	}else{
	$oibc['open']=true;
	}
	return $oibc;
}
function Ohsce_eng_serial_write(&$oibc,$data,$thex=false){
	$ohsce_eng_serial_wait=$oibc['space'];
	$mnow=microtime();
	if($ohsce_eng_serial_wait!=0){
	if($ohsce_eng_serial_wait>1){
	$mnowc=$mnow-$oibc['last'];
	if($mnowc<$ohsce_eng_serial_wait){
		$mnowc=$ohsce_eng_serial_wait-$mnowc;
		usleep($mnowc);
		unset($mnowc);
	}
	}
	}else{
	$ohsce_eng_serial_wait=0;
	}
	$oibc['last']=$mnow;
	if($oibc['comr']!=true){
		$res[0]=false;
		$res[1]='OpenIBCSCE_U MUST OPEN FIRST!'.OIBC_VERSON;
		return $res;
	}
	if(substr($data,-4)=="/r/n"){
		$data_mw="/r/n";
		$data=substr($data,0,(strlen($data)-4));
	}elseif(substr($data,-2)=="/n"){
		$data_mw="/n";
		$data=substr($data,0,(strlen($data)-2));
	}
	if((strcmp($thex,"string")==0)or(strcmp($thex,"String")==0)or(strcmp($thex,"STRING")==0)or(strcmp($thex,"str")==0)or(strcmp($thex,"STR")==0)or(strcmp($thex,"Str")==0)){
		$thex=false;
		$data=bts_str2bin($data);
	}
	if((strcmp($thex,"Dec")==0)or(strcmp($thex,"dec")==0)or(strcmp($thex,"DEC")==0)){
		$thex=true;
		$data=dechex($data);
	}
	if($thex){
		$data=hex2bin($data);
	}
	if(isset($data_mw)){
		$data=$data.$data_mw;
	}
	return Ohsce_comwrite($oibc['comr'],$data);
}
function Ohsce_eng_serial_read(&$oibc,&$data,$len=null,$thex=false,$timeout=3){
	if($oibc['comr']!=true){
		$res[0]=false;
		$res[1]='OpenIBCSCE_U MUST OPEN FIRST!'.OIBC_VERSON;
		return $res;
	}
	$data=null;
	//Ohsce_comread($oibc['comr'],$data,$len);
	if(null==$len){
	Ohsce_ReadCom($oibc['comr'],$data);
	}else{
	$datab=null;
	$stime=time();
	do{
	Ohsce_comread($oibc['comr'],$datab,$len);
	$data .=$datab;
	$len=$len-strlen($datab);
	}while(($len>0)and((time()-$stime)<3));
	}
	if(($data=="")or($data==null)){
		$data=null;
		goto frjs;
	}
	if($thex=="string"){
		$thex=false;
		$data=bts_bin2str($data);
	}
	if($thex){
		$data=bin2hex($data);
	}
	frjs:
	return $data;
}
function Ohsce_eng_serial_comwr(&$oibc,$wbuf,$wlen=null,&$rbuf,$rlen=2,$mode=0){
	Ohsce_eng_serial_write($oibc,$wbuf,$wlen);
	usleep(3000);
    Ohsce_eng_serial_read($oibc,$rbuf,$rlen=2);
	return $rbuf;
}
function Ohsce_eng_serial_npcomwr(&$oibc,$wdata,&$rdata,$thex=false){
	if($oibc['open']){
		return false;
	}
	Ohsce_comwriteread_np($rdata,$res['com'],$wdata);
	return $rdata;
}
function Ohsce_eng_serial_close(&$oibc){
	Ohsce_comclose($oibc['comr'],$oibc['mode']);
	$oibc['open']=false;
	$oibc['comr']=null;
	return true;
}
function Ohsce_eng_url_c($surl,&$odata,$username=null,$password=null,$cookie=false,$short=true){
return Ohsce_url_c($surl,$odata,$username=null,$password=null,$cookie=false,$short=true);
}
function Ohsce_eng_sm_creat(&$res,$key=null,$flags="c",$size=1014,$mode=0644){
	if(null==$flags){
		$flags="w";
	}else{
	$flags=strtolower($flags);
	}
	if(null==$key){
		if(($flags=="w")or($flags=="a")){
			return false;
		}else{
			return false;
		}
	}else{
		$res['key']=intval($key);
		if($res['key']<=0){
			return false;
		}
	}
	$res['fs']=$flags;
	$res['l']=intval($size);
	$res['mode']=$mode;
	$res['oc']=0;
	$res['sm']=null;
	return $res;
}
function Ohsce_eng_sm_open(&$oibc){
	if(ohsce_smCreat($res,$oibc['key'],$oibc['fs'],$oibc['mode'],$oibc['l'])==false){
		if($oibc['oc']!=1){
			return false;
		}else{
			if(shmop_read($res,0,0)==false){
			return false;
			}else{
			$oibc['sm']=$res;
			return $oibc;
			}
		}
	}
	$oibc['sm']=$res;
	$oibc['oc']=1;
	return $oibc;
}
function Ohsce_eng_sm_write(&$oibc,$data,$start=0){
	if($oibc['oc']!="1"){
		return false;
	}
	if($data!=null){
		$data=ohsce_smEncode($data);
	}
	return ohsce_smWrite($oibc['sm'],$data,$start);
}
function Ohsce_eng_sm_read(&$oibc){
	if($oibc['oc']!="1"){
		return false;
	}
	ohsce_smRead($oibc['sm'],$data,false);
	if((null===$data)or($data=='')){
		return null;
	}
	return ohsce_smDecode($data);
}
function Ohsce_eng_sm_close(&$oibc){
	if($oibc['sm']==null) goto smclosejs;
	ohsce_smClose($oibc['sm']);
	$oibc['sm']=null;
	smclosejs:
	$oibc['oc']=0;
	return $oibc;
}