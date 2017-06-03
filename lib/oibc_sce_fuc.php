<?php
/*
OHSCE_V0.2.0_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
该文件禁止改名!否则可能会无法运行！
*/
/* oibc_函数集 部分版 阉割版，必须手动指定准确参数. */
function ohsce_maketoken($key,$token){
	return md5(md5($key.sha1($token)));
}
function ohsce_mcrypt($string,$key="rand",$mode="e",$sf=MCRYPT_RIJNDAEL_128,$ms=MCRYPT_MODE_ECB){
	if(empty($mode)){
		$mode="e";
	}
	if(($mode!="e")&&($mode!="d")){
		return $string;
	}
	if(empty($sf)){
		$ccipher=MCRYPT_RIJNDAEL_128;
	}else{
		$ccipher=$sf;
	}
	if(empty($ms)){
		$cmode=MCRYPT_MODE_ECB;
	}else{
		$cmode=$ms;
	}
	if((empty($key))or($key=="rand")){
		if($mode=="e"){
		$key=oibc_make_rand(32);
		}else{
			return $string;
		}
	}
	 $civ = mcrypt_create_iv(mcrypt_get_iv_size($ccipher,$cmode),MCRYPT_RAND);
	 if($mode=="e"){
		 $res["string"] = mcrypt_encrypt($ccipher,$key,$string,$cmode,$civ);
		 $res["key"] = $key;
		 return $res;
	 }elseif($mode=="d"){
		 return mcrypt_decrypt($ccipher,$key,$string,$cmode,$civ);
	 }else{
		 return $string;
	 }
}
function Ohsce_getos(&$os){
	$Ohsce_osr=substr(php_uname(),0,5);
	switch($Ohsce_osr){
		case "Linux":
			$os='linux';
		goto js;
		case "FreeB":
			$os='FreeBSD';
		goto js;
		case "Windo":
			$os='Windows';
		goto js;
		case "Darwi":
			$os='OSX';
		goto js;
		default:
			$os='Unknow';
	}
	js:
		return $os;
}
function Ohsce_getos_64(){
	$cs=2147483649;
    if(intval($cs)>0){
		return true;
	}
    return false;
}
function Ohsce_base_iota_set($data,$type=null){
	if(($data===null)or($data=='')){
		return false;
	}
	global $ohsce_base_iota_type;
	if($type!=null){
		$ohsce_base_iota_type=$type;
	}
	if(empty($ohsce_base_iota_type)){
		return false;
	}
	$ohsce_base_iota_data_zdy_name='ohsce_base_iota_data_zdy_'.$ohsce_base_iota_type;
	global $$ohsce_base_iota_data_zdy_name;
	$$ohsce_base_iota_data_zdy_name=$data;
	return true;
}
function Ohsce_base_iota($type=null,$gl=false){
	global $ohsce_base_iota_type;
	if($type!=null){
		$ohsce_base_iota_type=$type;
	}
	if(empty($ohsce_base_iota_type)){
		$ohsce_base_iota_type='int';
	}else{
		$ohsce_base_iota_type=strtolower($ohsce_base_iota_type);
	}
	switch($ohsce_base_iota_type){
		case "dx":
		case "up":
		goto dchars;
		case "xx":
		case "low":
		goto xchars;
		case "int":
		case "int64":
		case "int32":
			goto iint;
		default:
	}
		$ohsce_base_iota_data_zdy_name='ohsce_base_iota_data_zdy_'.$ohsce_base_iota_type;
		global $$ohsce_base_iota_data_zdy_name;
		if((empty($$ohsce_base_iota_data_zdy_name)) or $gl){
			$$ohsce_base_iota_data_zdy_name=0;
		}
		$$ohsce_base_iota_data_zdy_name=intval($$ohsce_base_iota_data_zdy_name)+1;
		$res=$$ohsce_base_iota_data_zdy_name;
		goto js;
	iint:
		global $ohsce_base_iota_data_int;
		if((empty($ohsce_base_iota_data_int)) or $gl){
			$ohsce_base_iota_data_int=0;
		}
		$ohsce_base_iota_data_int=intval($ohsce_base_iota_data_int)+1;
		$res=$ohsce_base_iota_data_int;
		goto js;
	dchars:
		global $ohsce_base_iota_data_dc;
	    if((empty($ohsce_base_iota_data_dc)) or $gl){
			$ohsce_base_iota_data_dc=64;
		}else{
			$ohsce_base_iota_data_dc=intval($ohsce_base_iota_data_dc);
		}
		if($ohsce_base_iota_data_dc>=90){
			$ohsce_base_iota_data_dc=64;
		}
		$ohsce_base_iota_data_dc=$ohsce_base_iota_data_dc+1;
		$res=chr($ohsce_base_iota_data_dc);
		goto js;
	xchars:
		global $ohsce_base_iota_data_xc;
	    if((empty($ohsce_base_iota_data_xc)) or $gl){
			$ohsce_base_iota_data_xc=96;
		}else{
			$ohsce_base_iota_data_xc=intval($ohsce_base_iota_data_xc);
		}
		if($ohsce_base_iota_data_xc>=122){
			$ohsce_base_iota_data_xc=96;
		}
		$ohsce_base_iota_data_xc=$ohsce_base_iota_data_xc+1;
		$res=chr($ohsce_base_iota_data_xc);
		goto js;
	js:
		return $res;
}
function Ohsce_createSocket(&$socket=null,$protocol='TCP',$block=1,$reuse=null,$rtime=2,$stime=3,$rtimeu=0,$stimeu=0,$AF='ipv4'){
switch($protocol){
case "TCP":
case "tcp":
$protocol=SOL_TCP;
$type=SOCK_STREAM;
break;
case "UDP":
case "udp":
$protocol=SOL_UDP;
$type=SOCK_DGRAM;
break;
case "UNIX":
case "unix":
	$type=SOCK_STREAM;
goto unix;
case "LocFAST":
case "locfast":
	$type=SOCK_DGRAM;
goto unix;
case "ICMP":
case "icmp":
case "arp":
case "ARP":
	default:
$protocol=getprotobyname($protocol);
$type=SOCK_RAW;
}
switch($AF){
    case "IPV6":
	case "ipv6":
		goto ipv6;
	case "UNIX":
	case "unix":
		goto unix;
	case "ipv4":
	case "IPV4":
	default:
		goto ipv4;
}
ipv6:
$socket = socket_create(AF_INET6,$type,$protocol);
goto created;
ipv4:
$socket = socket_create(AF_INET,$type,$protocol);
goto created;
unix:
$socket = socket_create(AF_UNIX,$type,0);
created:
	if(false==$socket){
	$res[0]=false;
	$res[1]='OibcSecSocketCreateError';
	$res[2]=Ohsce_geterror($socket);
	goto js;
    }
if($protocol==SOL_TCP) Ohsce_fastpush($socket);
socket_set_option($socket,SOL_SOCKET,SO_RCVTIMEO,array("sec"=>$rtime, "usec"=>$rtimeu ) );
socket_set_option($socket,SOL_SOCKET,SO_SNDTIMEO,array("sec"=>$stime, "usec"=>$stimeu ) );
if(true==$reuse){
socket_set_option($socket,SOL_SOCKET,SO_REUSEADDR,TRUE);
}
if($block<1){
socket_set_nonblock($socket);
}
$res[0]=true;
$res[1]=$socket;
js:
	return $res;
}
function Ohsce_geterror($socket){
	return socket_strerror(socket_last_error($socket));
}
function Ohsce_socketconnect(&$socket,$ip,$port,$block=1,$fast=1){
	again:
	if(socket_connect($socket, $ip, $port )){
		return true;
	}else{
		if($block<1) return false;
		//socket_get_option($socket,SOL_SOCKET,MCAST_BLOCK_SOURCE); //5.4+
		if(isset($i)){
			$i++;
		}else{
			$i=1;
			goto again;
		}
		if($i>1){
			if($fast<1)
				sleep($i);
			if($i>2)
				return false;
		}
		goto again;
	}
}
function Ohsce_socketclose(&$socket,$stop=false){
	if($stop) Ohsce_socketstop($socket);
	socket_close($socket);
}
function Ohsce_socketstop(&$socket){
	socket_shutdown($socket);
}
function Ohsce_socketbind(&$socket,$port=null,$address=null){
	if(null==$address) $address=OHSCE_MYIP;
	if(!is_int($port)) socket_bind($socket,$address);
    socket_bind($socket,$address,$port);
	return $socket;
}
function Ohsce_socketsetbuff(&$socket,$send=16384,$recv=87380,$mode=null){
	if(!is_null($mode)){
		switch($mode){
			case "max":
			case "MAX":
				$recv=131072;
			    $send=174760;
				goto jmp2set;
			case "min":
			case "min":
				$recv=4096;
			    $send=4096;
				goto jmp2set;
			case "none":
			default:
		}
	}
	if(is_null($send)){
		$send=16384;
	}
	if(is_null($recv)){
		$recv=87380;
	}
	jmp2set:
	socket_set_option($socket, SOL_SOCKET, SO_SNDBUF, $send);
	socket_set_option($socket, SOL_SOCKET, SO_RCVBUF, $recv);
	return $socket;
}
function Ohsce_fastpush(&$socket,$of=1){
	socket_set_option($socket, SOL_SOCKET, TCP_NODELAY, $of);
	return $socket;
}
function Ohsce_socketlisten(&$socket,$max=null){
	if(!is_null($max)) socket_listen($socket,$max);
	socket_listen($socket);
	return $socket;
}
function Ohsce_getflags(&$flags){
	switch($flags){
		case MSG_OOB:
		case "1":
		case "obb":
		     $flags=MSG_OOB;
		break;
            case MSG_EOR:
				case "2":
				$flags=MSG_EOR;
			break;
            case MSG_EOF:
				case "3":
				$flags=MSG_EOF;
			break;
            case MSG_DONTROUTE:
				case "4":
				$flags=MSG_DONTROUTE;
			break;
		case MSG_PEEK:
		case "5":
		case "peek":
			$flags=MSG_PEEK;
		break;
		case MSG_WAITALL:
		case "6":
		case "waitall":
		case "all":
			$flags=MSG_WAITALL;
		break;
		case MSG_DONTWAIT:
		case "7":
		case "downwait":
		case "nb":
			$flags=MSG_DONTWAIT;
		break;
		default:
			$flags=0;
		}
		switch($flags){
		case MSG_OOB:
		case "1":
		case "oob":
			$flags=MSG_OOB;
		break;
		case MSG_PEEK:
		case "2":
		case "peek":
			$flags=MSG_PEEK;
		break;
		case MSG_WAITALL:
		case "3":
		case "waitall":
		case "all":
			$flags=MSG_WAITALL;
		break;
		case MSG_DONTWAIT:
		case "4":
		case "downwait":
		case "nb":
			$flags=MSG_DONTWAIT;
		break;
		case "0":
		default:
			$flags=0;
	}
	return $flags;
}
function Ohsce_socketsend(&$socket,$in,$inl=0,$flags=0,$address=0,$port=0,$fast=false){
	if(0!=$flags){
		Ohsce_getflags($flags);
	}
	if(is_array($in)){
		if(!isset($in['in'])){
			goto terror;
		}else{
			if(isset($in['bin'])){
				$in=hex2bin($in['in']);
			}else{
				$in=$in['in'];
			}
		}
	}
	if(($address!=0)and($port!=0)){
		if(0!=$inl){
			$inl=intval($inl);
			goto sendto;
		}
		$inl=strlen($in);
		goto sendto;
	}
	if(0!=$inl){
		$inl=intval($inl);
	}else{
		$inl=strlen($in);
	}
	send:
	if(false===($bytes=socket_send($socket, $in, $inl, $flags))) {
		if($fast) goto sendjs;
		if(isset($i)){
			$i++;
		}else{
				$i=0;
				goto send;
			}
		if($i<3){
			$sleep=pow(10,$i);
			sleep($sleep);
			goto send;
		}
	sendjs:
	$res[0] = FALSE;
    $res[1] = "OpenIBCSCE failed: reason: " . Ohsce_geterror($socket) . "\n";
	$res[2] = 0;
	return $res;
    }
	goto js;
	sendto:
	if(false===($bytes=socket_sendto($socket, $in, $inl, $flags, $address, $port))) {
		if($fast) goto sendtojs;
		if(isset($i)){
			$i++;
		}else{
				$i=0;
				goto sendto;
			}
		if($i<3){
			$sleep=pow(10,$i);
			sleep($sleep);
			goto sendto;
		}
	sendtojs:
	$res[0] = FALSE;
    $res[1] = "OpenIBCSCE failed: reason: " . Ohsce_geterror($socket) . "\n";
	$res[2] = 0;
	return $res;
    }
	js:
		$res[0] = true;
	    $res[1] = 'OpenIBCSCESuccess';
		$res[2] = $bytes;
	return $res;
	terror:
		$res[0] = false;
	    $res[1] = 'OpenIBCSCEFaild';
	return $res;
}
function Ohsce_socketwrite(&$socket,$in,$inl=0,$fast=false){
	if(0==$inl){
		starn:
		if(false===($bytes=socket_write($socket,$in))){
		   if($fast) goto starnjs;
			if(isset($i)){
				$i++;
			}else{
				$i=0;
				goto starn;
			}
			if($i<3){
			$sleep=pow(10,$i);
			sleep($sleep);
			goto starn;
		    }
		   starnjs:
		   $res[0] = FALSE;
           $res[1] = "OpenIBCSCE failed: reason: " . Ohsce_geterror($socket) . "\n";
		   $res[2] = 0;
	       return $res;
		}
		goto js;
	}
	$inl=intval($inl);
		star:
		if(false===($bytes=socket_write($socket,$in,$inl))){
		   if($fast) goto starjs;
			if(isset($i)){
				$i++;
			}else{
				$i=0;
				goto star;
			}
			if($i<3){
			$sleep=pow(10,$i);
			sleep($sleep);
			goto star;
		    }
		   starjs:
		   $res[0] = FALSE;
           $res[1] = "OpenIBCSCE failed: reason: " . Ohsce_geterror($socket) . "\n";
		   $res[2] = 0;
	       return $res;
		}
	js:
		$res[0] = true;
	    $res[1] = 'OpenIBCSCESuccess';
		$res[2] = $bytes;
	return $res;
}
function Ohsce_socketrecv(&$socket,&$buf,$len=0,$flags=0,$fast=true){
  if(0!=$flags){
	Ohsce_getflags($flags);
  }
  if(0==$len){
	  $len=1024;
  }
  again:
  if(false===($bytes=socket_recv($socket,$buf,$len,$flags))){
	  if($fast){
		  jmperror:
		  $res[0]=false;
		  $res[1]='OpenIBCSCEFailed';
		  $res[2]=0;
		  return $res;
	  }
	  if(!isset($i)){
		  $i=0;
		  goto again;
	  }else{
		  $i++;
	  }
	  if($i<3){
	      goto again;
	  }else{
		  goto jmperror;
	  }
  }
        $res[0] = true;
	    $res[1] = $buf;
		$res[2] = $bytes;
	return $res;
}
function Ohsce_socketrecvfrom(&$socket,&$buf,$len=0,$flags=0,&$from,&$port,$fast=true){
  if(0!=$flags){
	Ohsce_getflags($flags);
  }
  if(0==$len){
	  $len=1024;
  }
  again:
  if(false===($bytes=socket_recvfrom($socket,$buf,$len,$flags,$from,$port))){
	  if($fast){
		  jmperror:
		  $res[0]=false;
		  $res[1]='OpenIBCSCEFailed';
		  $res[2]=0;
		  return $res;
	  }
	  if(!isset($i)){
		  $i=0;
		  goto again;
	  }else{
		  $i++;
	  }
	  if($i<3){
	      goto again;
	  }else{
		  goto jmperror;
	  }
  }
        $res[0] = true;
	    $res[1] = $buf;
		$res[2] = $bytes;
		$res[3] = $from;
		$res[4] = $port;
	return $res;
}
function Ohsce_socketread(&$socket,$len=0,$binadry=true,$fast=true){
	if($binadry){
		again:
		$buf=socket_read($socket,$len);
		if(false==$buf){
			if(!isset($i)){
				$i=0;
				goto again;
			}
			if($i>3) goto errorjs;
			$i++;
			usleep(pow(10,$i));
			goto again;
		}
	}else{
		againb:
		$buf=socket_read($socket,$len,PHP_NORMAL_READ);
		if(false==$buf){
			if(!isset($i)){
				$i=0;
				goto againb;
			}
			if($i>3) goto errorjs;
			$i++;
			usleep(pow(10,$i));
			goto againb;
		}
	}
	$res[0]=true;
	$res[1]=$buf;
	$res[2]=strlen($buf);
	return $res;
	errorjs:
		   $res[0] = FALSE;
           $res[1] = "OpenIBCSCE failed: reason: " . Ohsce_geterror($socket) . "\n";
		   $res[2] = 0;
}
function Ohsce_casepr($pr,&$re){
	switch($pr){
		case "TCP":
			$re="1";
		return $re;
		case "UDP":
			default:
			$re=0;
		return $re;
	}
}
function Ohsce_url_c($surl,&$odata,$username=null,$password=null,$cookie=false,$short=true,$headers=null){
	if(is_array($surl)){
		if(isset($surl['postdata'])){
		$postdata=$surl['postdata'];
		}
		if(isset($surl['proxy'])){
		$proxy=$surl['proxy'];
		$proxyaddress=$surl['proxyaddress'];
		if(isset($surl['proxyuser'],$surl['proxypassword'])){
			$proxyuser=$surl['proxyuser'];
			$proxypassword=$surl['proxypassword'];
		}else{
			$proxyuser=null;
			$proxypassword=null;
		}
		}
		$url=$surl[0];
	}else{
		$url=$surl;
	}
	$cp=Ohsce_url_cp($url);
	if($cp!=false){
		Ohsce_url_seturl($url,$ohscecurl);
		switch(strtolower($cp[0])){
			case "ftp":
				if(!isset($surl["files"],$surl["ud"])){
				$cp[1]='error';
				goto ejs;
			    }
				$ohffile=$surl["files"];
			    $ohfud=$surl["ud"];
				$ohffp = fopen($ohffile, 'r+');
				if(($ohfud==true)or($ohfud=="upload")){
				Ohsce_url_setftp($ohscecurl,$username,$password,true,$ohffp,$ohffile,300);
				}else{
				Ohsce_url_setftp($ohscecurl,$username,$password,false,$ohffp,$ohffile,300);
				}
				break;
			case "https":
				Ohsce_url_setmode($ohscecurl,"httpsn");
				break;
			case "http":
			default:
				Ohsce_url_setmode($ohscecurl);
				break;
		}
	}else{
		goto ejs;
	}
	if(isset($postdata)){
		if(is_array($postdata)){
		Ohsce_url_setpost($ohscecurl,$postdata);
		}elseif(!bts_is_json($postdata)){
		Ohsce_url_setstr($ohscecurl,$postdata);
		}else{
		Ohsce_url_setjson($ohscecurl,$postdata);
		}
	}
	if($cookie!=false){
		if($cookie=true){
			$cookie='cookie.txt';
		}else{
			$cookie=trim($cookie);
		}
		$cookiedir = OHSCE_ROOTDIR.$cookie;
		$ocfp=@fopen($cookiedir,"a+");
		if($ocfp==false){
			goto ejs;
		}else{
			fclose($ocfp);
		}
		Ohsce_url_setcookie($ohscecur,$cookiedir);
	}
	if((!is_null($username))and(!is_null($password))){
		curl_setopt($ohscecurl, CURLOPT_USERPWD, $username.':'.$password);
	}
	if(isset($proxy,$proxyaddress)){
		Ohsce_url_setproxy($ohscecurl,$proxy,$proxyaddressr,$proxyuser,$proxypassword);
	}
	if(isset($surl['headers'])){
		$headers[0]=true;
		$headers[1]=$surl['headers'];
	}
	if(null!=$headers){
		if(is_array($headers)){
			if(isset($headers[0],$headers[1])){
				if(true==$headers[0]){
					Ohsce_url_setheader($ohscecurl,$headers[1]);
				}
			}
		}
	}
	curl_setopt($ohscecurl,CURLOPT_USERAGENT,'OpenHIRELSignalCommunicationEngine(MAIN)'.OIBC_VERSON); 
	$odata=Ohsce_url_exec($ohscecurl);
	if($short){
	Ohsce_url_close($ohscecurl);
	}
	$odata=trim($odata);
	if(strtolower($cp[1])=="ftp"){
		fclose($ohffp);
	}
	    return $odata;
	ejs:
	if(strtolower($cp[1])=="ftp"){
		fclose($ohffp);
    }
		return false;
}
function Ohsce_url_cp($url,$qz=true){
	$urlheadar=explode(':',$url);
    if(!isset($urlheadar[1])){
	    if($qz){
	      $url='http://'.$url;
          $urlhead='http';
	    }else{
		  return false;
	    }
    }else{
	$urlhead=$urlheadar[0];
    }
    switch(strtolower($urlhead)){
	case 'http':
		$res='http';
	break;
	case 'https':
		$res='https';
	break;
	case 'ftp':
		$res='ftp';
	    $port=21;
	break;
	case 'gopher':
		$res='gopher';
	break;
	case 'telnet':
		$res='telnet';
	break;
	case 'dict':
		$res='dict';
	    $port=2628;
	break;
	case 'file':
		$res='file';
	break;
	case 'ldap':
		$res='ldap';
	break;
	default:
		$res='http';
	    $url='http://'.$url;
	break;
    }
	$result[0]=$res;
	$result[1]=$url;
	if(isset($port)){
	$result[2]=$port;
	}
	return $result;
}
function Ohsce_url_seturl($url,&$curl=null){
	$curl = curl_init();
    // 设置你需要抓取的URL
    curl_setopt($curl, CURLOPT_URL, $url);
	return $curl;
}
function Ohsce_url_setmode(&$curl,$mode="nomal",$timeout=5){
	switch($mode){
		case "httpsn":
			curl_setopt($curl, CURLOPT_HEADER, 0);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        break;
		case "nomal":
		default:
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);//通常情况下工控场景动作请求不指望完全返回
		break;
	}
	return $curl;
}
function Ohsce_url_setheader(&$cr,$headers){
	curl_setopt($cr, CURLOPT_HEADER, true);
    curl_setopt($cr, CURLOPT_HTTPHEADER, $headers);
}
function Ohsce_url_setpost(&$cr,$data){
	curl_setopt($cr, CURLOPT_POST, 1);
	curl_setopt($cr, CURLOPT_POSTFIELDS , http_build_query($data));
	return $cr;
}
function Ohsce_url_setstr(&$cr,$data){
	curl_setopt($cr, CURLOPT_POST, 1);
	curl_setopt($cr, CURLOPT_POSTFIELDS , $data);
	return $cr;
}
function Ohsce_url_setjson(&$cr,$data){
	curl_setopt($cr, CURLOPT_POST, 1);
	curl_setopt($cr, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length:' . strlen($data)));
	curl_setopt($cr, CURLOPT_POSTFIELDS , $data);
	return $cr;
}
function Ohsce_url_setcookie(&$cr,$data){
	curl_setopt($cr, CURLOPT_COOKIEJAR, $data);
	curl_setopt($cr, CURLOPT_COOKIEFILE , $data);
	return $cr;
}
function Ohsce_url_setproxy(&$cr,$proxy,$proxyaddressr,$proxyuser=null,$proxypassword=null){
	switch(strtolower($proxy)){
		case "http":
			curl_setopt($cr, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);  
            curl_setopt($cr, CURLOPT_PROXY, $proxyaddressr);  
			curl_setopt($cr, CURLOPT_HTTPPROXYTUNNEL, 1);
			break;
		case "socks4":
			curl_setopt($cr, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);  
            curl_setopt($cr, CURLOPT_PROXY, $proxyaddressr);  
			break;
		case "socks4a":
			curl_setopt($cr, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4A);  
            curl_setopt($cr, CURLOPT_PROXY, $proxyaddressr);  
			break;
		case "socks5":
			curl_setopt($cr, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);  
            curl_setopt($cr, CURLOPT_PROXY, $proxyaddressr);  
			break;
		case "socks5_hostname":
			curl_setopt($cr, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5_HOSTNAME);  
            curl_setopt($cr, CURLOPT_PROXY, $proxyaddressr);  
			break;
		default:
			return false;
		    break;
	}
	if((!is_null($proxyuser))and(!is_null($proxypassword))){
		curl_setopt($cr,CURLOPT_PROXYUSERPWD, $proxyuser.":".$proxypassword);  
	}
	return $cr;
}
function Ohsce_url_setftp(&$cr,$username,$password,$upload=true,&$fp,$localfile,$timeout=300){
	curl_setopt($cr, CURLOPT_USERPWD, $username.':'.$password);
	if($upload){
		curl_setopt($cr, CURLOPT_UPLOAD, 1);
        curl_setopt($cr, CURLOPT_INFILE, $fp);
        curl_setopt($cr, CURLOPT_INFILESIZE, filesize($localfile));
	}else{
		curl_setopt($cr, CURLOPT_URL,$target_ftp_file);
        curl_setopt($cr, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cr, CURLOPT_VERBOSE, 1);
        curl_setopt($cr, CURLOPT_FTP_USE_EPSV, 0);
        curl_setopt($cr, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($cr, CURLOPT_FILE, $fp);
	}
	return $ct;
}
function Ohsce_url_exec(&$cr){
	return curl_exec($cr);
}
function Ohsce_url_close(&$cr){
	curl_close($cr);
	return true;
}
function Ohsce_makearp($mymac="B8-97-5A-24-E3-69",$myip,$farip){
	$mymac=bts_bas_array2bin(explode("-",$mymac));
	$myip=bts_bas_array2bind(explode(".",$myip));
	$farip=bts_bas_array2bind(explode(".",$farip));
	$qqyd="\x00\x01";
	$pack="\xff\xff\xff\xff\xff\xff".$mymac."\x08\x06\x00\x01\x08\x00\x06\x04".$qqyd.$mymac.$myip."\x00\x00\x00\x00\x00\x00".$farip;
	return $pack;
}
function Ohsce_comparity($parity){
	if(!in_array($parity,array("none","n","even","e","odd","o","mark","m","spave","s"))) return $parity;
	$Ohsce_paa_w=array("n"=>'n',"e"=>'e',"o"=>'o',"m"=>'m',"s"=>'s',"none"=>'n',"even"=>'e',"odd"=>'o',"mark"=>'m',"spave"=>'s');
	$Ohsce_paa_l=array("n"=>"-parenb","e"=>"parenb -parodd","o"=>"parenb parodd","none"=>"-parenb","even"=>"parenb -parodd","odd"=>"parenb parodd");
	$Ohsce_paa_o=array("n"=>"-parenb","e"=>"parenb -parodd","o"=>"parenb parodd","none"=>"-parenb","even"=>"parenb -parodd","odd"=>"parenb parodd");
	if(OHSCE_OS=="Windows") return $Ohsce_paa_w[$parity];
	if(!in_array($parity,array("none","n","even","e","odd","o"))) return $parity;
	if(OHSCE_OS=="Linux") return $Ohsce_paa_l[$parity];
	if(OHSCE_OS=="OSX") return $Ohsce_paa_o[$parity];
	return $parity;
}
function Ohsce_comfc($fc){
	if(!in_array($fc,array("none","rts/cts","xon/xoff"))) return $fc;
	$Ohsce_fc_l = array(
            "none"     => "clocal -crtscts -ixon -ixoff",
            "rts/cts"  => "-clocal crtscts -ixon -ixoff",
            "xon/xoff" => "-clocal -crtscts ixon ixoff"
        );
    $Ohsce_fc_w = array(
            "none"     => "xon=off octs=off rts=on",
            "rts/cts"  => "xon=off octs=on rts=hs",
            "xon/xoff" => "xon=on octs=off rts=on",
        );
	if(OHSCE_OS=="Windows") return $Ohsce_fc_w[$fc];
	if(OHSCE_OS=="Linux") return $Ohsce_fc_l[$fc];
	if(OHSCE_OS=="OSX") return $Ohsce_fc_l[$fc];
	return $fc;
}
/* fc for LINUX/OSX OTHER FOR WINDOWS*/
function Ohsce_comset($com,$baud=9600,$parity='n',$data=8,$stop=1,$fc='none',$xon='off',$to='un',$octs='off',$odsr='off',$idsr='off',$dtr='on',$rts='off'){
	switch(OHSCE_OS){
	case "Windows":
	if($fc=="none"){
	$exsms="mode ".$com.": baud=".$baud." parity=".Ohsce_comparity($parity)." data=".$data." stop=".$stop." xon=".$xon." odsr=".$odsr." octs=".$octs." idsr=".$idsr." dtr=".$dtr." rts=".$rts;
	if($to!="un"){
		 $exsms=$exsms."to=".$to;
	}
	}else{
	$exsms="mode ".$com.": baud=".$baud." parity=".Ohsce_comparity($parity)." data=".$data." stop=".$stop." odsr=".$odsr." to=".$to." idsr=".$idsr." dtr=".$dtr." ".Ohsce_comfc($fc);
	if($to!="un"){
		 $exsms=$exsms."to=".$to;
	}
	}
	break;
	case "Linux":
		if($stop == 1.5) return false;
		$exsms="stty -F ".$com." ".(int)$baud.' '.Ohsce_comparity($parity)." cs". $data.' '.(($stop == 1) ? "-" : "")."cstopb ".Ohsce_comfc($fc);
	break;
	case "OSX":
		$exsms="stty -f ".$com." ". (int)$baud.' '.Ohsce_comparity($parity)." cs". $data.' '.(($stop == 1) ? "-" : "")."cstopb ".Ohsce_comfc($fc);
		break;
	default:
		return false;
	break;
	}
	Ohsce_exec($exsms);
	return true;
}
function Ohsce_comset_timeout($com,$to='on'){
	if(($to==on)or(true==$to)){
	$exsms="mode ".$com.": to=on";
	}else{
	$exsms="mode ".$com.": to=off";
	}
	Ohsce_exec($exsms);
	return true;
}
function Ohsce_comecase($flags){
	if(0==$flags) return O_RDWR;
	$flagsa=array("1"=>"w+","2"=>"w","3"=>"r","4"=>"a","5"=>"a+","6"=>"x","7"=>"x+","8"=>"c","9"=>"c+");
	if(!isset($flagsa[$flags])) return $flags;
	return $flagsa[$flags];
}
function Ohsce_comopen(&$oibc,$com,$flags,$mode=0){
	switch($mode){
	case "1":
	$oibc=dio_open($com,Ohsce_comecase($flags),0);
	break;
	case "0":
		default:
	$oibc=fopen($com,Ohsce_comecase($flags));
	break;
	}
	return $oibc;
}
function Ohsce_comread(&$oibc,&$buf,$len=2,$mode=0){
	switch($mode){
	case "1":
	$buf=dio_read($oibc,$len);
	break;
	case "2":
		$i=1;
	$buf='';
	do{
		$i++;
		$buf=$buf.fgetc($oibc);
	}while($i<$len);
	break;
	case "0":
		default:
	$buf=fread($oibc,$len);
	break;
	}
	return $buf;
}
function Ohsce_comwrite(&$oibc,$buf,$len=null,$mode=0){
	switch($mode){
	case "1":
	if(is_null($len)){
	$size=dio_write($oibc,$buf);
	}else{
	$size=dio_write($oibc,$buf,intval($len));
	}
	break;
	case "2":
		$size=fwrite($oibc,hex2bin($buf));
		break;
	case "0":
		default:
	$size=fwrite($oibc,$buf);
	break;
	}
	return $size;
}
function Ohsce_comwriteread_np(&$oibc,$com,$write){
	again:
	$mk=rand(1000000,9999999);
	if(false==ohsce_smCreat($mkey,$mk,$flags="n",$mode=0644,$size=1014)){
		usleep(100);
		goto again;
	}
	$comp=OHSCE_PHPDIR.' '.OHSCE_ROOTDIR.'\OHSceRun.php -r engine -m comrw -p '.$com.' -w '.bin2hex($write).' -k '.$mk;
	$cfd=popen($comp,"r");
	sleep(1);
	$obuf='ohscebuf';
    do{
		ohsce_smRead($mkey,$buf);
		if($buf!=""){
			$obuf=$buf;
		}
	}while(($buf!="")and($obuf!=$buf));
	pclose($cfd);
	$oibc=$buf;
	return $oibc;
}
function Ohsce_ReadCom(&$oibc,&$read,$timeout=3){
$read=null;
$star=time();
Ohscereadcomstar:
Ohsce_comread($oibc,$ohsce_comrw_buf,2,0);
if($ohsce_comrw_buf!=""){
	$ohsce_comrw_buf_r=true;
	$read .=$ohsce_comrw_buf;
}
if($ohsce_comrw_buf==""){
	if(isset($ohsce_comrw_buf_r)){
		if($read=='') return false;
		return $read;
	}
}
if((time()-$star)>$timeout){
	return $read;
}
goto Ohscereadcomstar;
}
function Ohsce_ccom($com,$ccom){
	symlink($com,$ccom);
}
function Ohsce_fastflush(&$oibc){
	fflush($oibc);
}
function Ohsce_getbaud($baud){
	switch($baud){
		case "110":
		case "11":
			return "11";
		case "150":
		case "15":
			return "15";
		case "300":
		case "30":
			return "30";
		case "600":
		case "60":
			return "60";
		case "1200":
		case "12":
			return "12";
		case "2400":
		case "24":
			return "24";
		case "4800":
		case "48":
			return "48";
		case "19200":
		case "19":
			return "19";
		case "38400":
		    return "38400";
		case "57600":
			return "57600";
		case "115200":
			return "115200";
		case "9600":
		case "96":
			default:
		    return "96";
	}
	return "96";
}
function Ohsce_comclose(&$oibc,$mode=0){
	switch($mode){
	case "1":
	dio_close($oibc);
	break;
	case "0":
		default:
	fclose($oibc);
	break;
	}
	return true;
}
function ohsce_comflush(&$oibc){
	return fflush($oibc);
}
function Ohsce_exec($exec,$mode=0){
	switch($mode){
		case "1":
			$exc = array(
            1 => array("pipe", "w"),
            2 => array("pipe", "w")
        );
        $ep = proc_open($cmd, $exc, $pipes);
        $r = stream_get_contents($pipes[1]);
        $e = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $retVal = proc_close($ep);
		$out=null;
        if (func_num_args() == 2) $out = array($r, $e);
		return $out;
		case "2":
			system($exec);
		return true;
		case "0":
			default:
			exec($exec);
		return true;
	    }
		return false;
	}
function ohsce_smCreat(&$mkey,$key,$flags="c",$mode=0644,$size=1014){
	$size=$size+10;
	$mkey=shmop_open($key,$flags,$mode,$size);
	if(false==$mkey){
		return false;
	}
	return shmop_read($mkey,0,0);
}
function ohsce_smDecode(&$smdate,$array=true){
	if(is_array($smdate)){
		return $smdate;
	}
	$smdate=unpack("a*",$smdate);
	$smdate=json_decode($smdate[1],$array);
	return $smdate;
}
function ohsce_smEncode(&$smdate){
	$smdate=json_encode($smdate);
	$smdate=pack("a*",$smdate);
	return $smdate;
}
function ohsce_smWrite(&$mkey,$data,$offset=0){
	if(is_array($data)){
		return shmop_write($mkey,ohsce_smEncode($data).'[ohsceend]',$offset);
	}
	return shmop_write($mkey,$data.'[ohsceend]',$offset);
}
function ohsce_smRead(&$mkey,&$read,$smDe=false){
	if(false!==($read=shmop_read($mkey,0,0))){
			$end=strpos($read,'[ohsceend]');
			if(false!=$end){
				$read=substr($read,0,$end);
			}
	}else{
		return false;
	}
	if($smDe){
		return ohsce_smDecode($read);
	}else{
		return $read;
	}
	return $read;
}
function ohsce_smClose(&$mkey){
	shmop_close($mkey);
	return true;
}
function ohsce_smDelete(&$mkey){
	shmop_delete($mkey);
	return true;
}
function ohsce_smClean(&$mkey){
	ohsce_smDelete($mkey);
	ohsce_smClose($mkey);
	return true;
}
function ohsce_jsonrpc_client_creat($host, $port, $version="2.0"){
	$ohsce_jsonrpc_rq['host']=$host;
	$ohsce_jsonrpc_rq['port']=$port;
	$ohsce_jsonrpc_rq['version']=$version;
	return $ohsce_jsonrpc_rq;
}
function ohsce_jsonrpc_format_response($response)
{
	$ohsce_jsonrpc_re=json_decode($response,true);
	if($ohsce_jsonrpc_re==NULL){
		$ohsce_jsonrpc_re=false;
	}
	return $ohsce_jsonrpc_re;
}
function ohsce_jsonrpc_request($rq, $method, $params=array(), $timeout=1,$jrid="A1")
{
	    $gidvname="ohsce_jsonrpc_gid_".$jrid;
		global $$gidvname;
		if(empty($$gidvname)){
			$$gidvname=0;
		}
		$id=$$gidvname;
		$data = array();
		$data['jsonrpc'] = $rq['version'];
		$data['id'] = $id+1;
		$data['method'] = $method;
		$data['params'] = $params;
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $rq['host']);
		curl_setopt($ch, CURLOPT_PORT, $rq['port']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		if($timeout!=false){
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 
		}
		
		$ret = curl_exec($ch);
		
		if($ret !== FALSE)
		{
			$formatted = ohsce_jsonrpc_format_response($ret);
			
			if(isset($formatted["error"]))
			{
				return array('ohsceres'=>false,'error'=>true,'message'=>$formatted["error"]["message"],'code'=>$formatted["error"]["code"]);
			}
			else
			{
				$formatted['ohsceres']=true;
				$formatted['error']=false;
				return $formatted;
			}
		}
		else
		{
			return array('ohsceres'=>false,'error'=>false,'message'=>'Server did not respond!');
		}
}