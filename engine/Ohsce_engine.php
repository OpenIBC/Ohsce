<?php
/*
OHSCE_V0.2.0.2_B
高可靠性的PHP通信框架。
HTTP://WWW.OHSCE.ORG
@作者:林友哲 393562235@QQ.COM
作者保留全部权利，请依照授权协议使用。
该文件禁止改名!否则可能会无法运行！
*/
reload:
$errmsg='An error has been happened!';
switch($mode){
	case "olmd":
		goto olmd;
	case "olmdbackups":
		goto olmdbackups;
	case "pcenter":
		goto pcenter;
	case "pdefend":
		goto pdefend;
	case "comrw":
		goto comrw;
	case "newprocess":
	case "callnp":
		goto callnp;
	case "comserver":
		goto comserver;
	case "wext":
		goto wext;
	default:
		$errmsg='Mode not definded!';
		goto terror;
}
jmpback:
if(!isset($jmpback)){
	$errmsg='JMPERROR!';
	goto terror;
}
switch(strtolower($jmpback)){
	case "reload":
		goto reload;
	default:
		$errmsg='JMPERROR!';
		goto terror;
}
olmdbackups:
$ohsce_olmd_now=ohsce_smCreat($ohsce_olmd_main,OHSCE_OLMD_MADDRESSBACKUPS);
$ohsce_olmd_id_in=intval(OHSCE_OLMD_MADDRESSBACKUPS)+1;
olmd:
if(!isset($ohsce_olmd_now)) $ohsce_olmd_now=ohsce_smCreat($ohsce_olmd_main,OHSCE_OLMD_MADDRESS);
if(strlen($ohsce_olmd_now)>0){
	if(isset(ohsce_smDecode($ohsce_olmd_now)["heart"])){
	if((time()-$ohsce_olmd_now["heart"])<120){
		$errmsg='If you sure olmd has be stoped,please wait 120s!';
		goto terror;
	}
	}
}
$ohsce_olmd_now['heart']=time();
$ohsce_olmd_now['star']=time();

ohsce_smWrite($ohsce_olmd_main,$ohsce_olmd_now);

if(!isset($ohsce_olmd_id_in)) $ohsce_olmd_id_in=intval(OHSCE_OLMD_MADDRESS)+1;
if(false==ohsce_smCreat($ohsce_olmd_in,$ohsce_olmd_id_in,"n")){
	$errmsg='Share memmory key '.$ohsce_olmd_id_in.' must be free!';
	goto terror;
}
$ohsce_olmd_id_out=$ohsce_olmd_id_in+3;
if(false==ohsce_smCreat($ohsce_olmd_out,$ohsce_olmd_id_out,"n")){
	$errmsg='Share memmory key '.$ohsce_olmd_id_out.' must be free!';
	goto terror;
}
if(!ohsce_channel_server_creat($ohsce_olmd_channe,array('mode'=>'fastsocket','cport'=>intval(OHSCE_OLMD_MADDRESSPORT),'cip'=>OHSCE_MYIP_SYSTEM))){
	$errmsg='Channel creat error!';
	goto terror;
}
Ohsce_eng_socket_server($ohsceolmdudpserver,'udp',OHSCE_OLMD_MADDRESSPORTY,OHSCE_MYIP,'none');
$ohsce_olmd_mhold[OHSCE_OLMD_MADDRESS]=$ohsce_olmd_main;
$ohsce_olmd_mhold_canwritey['OLMDZAMB']='2';
Ohsce_socketsetbuff($ohsceolmdudpserver['socket'],0,0,"MAX");
olmdnext:
olmdstarloop:
    $ohsce_olmd_serverudp_ls=@Ohsce_socketrecvfrom($ohsceolmdudpserver['socket'],$ohsce_olmd_lsu_buf,0,0,$ohsce_olmd_lsu_from,$ohsce_olmd_lsu_port);
	if($ohsce_olmd_serverudp_ls[0]!=false){
		$ohsce_olmd_channe_date_b=ohsce_mcrypt($ohsce_olmd_lsu_buf,OHSCE_OLMD_MADDRESSPASSY,"d");
		$ohsce_olmd_cread_data_b=ohsce_smDecode($ohsce_olmd_channe_date_b);
		$ohsce_olmd_lsu_k=intval($ohsce_olmd_cread_data_b['key']);
		if(isset($ohsce_olmd_cread_data_b['do'])){
			$ohsce_olmd_lsu_do=strtolower(trim($ohsce_olmd_cread_data_b['do']));
		}else{
			$ohsce_olmd_lsu_do='read';
		}
		switch($ohsce_olmd_lsu_do){
			case "read":
		    if(isset($ohsce_olmd_mhold[$ohsce_olmd_lsu_k])){
			if(false!=ohsce_smRead($ohsce_olmd_mhold[$ohsce_olmd_lsu_k],$ohsce_olmd_reslu_k_read)){
			Ohsce_socketsend($ohsceolmdudpserver['socket'],$ohsce_olmd_reslu_k_read,0,0,$ohsce_olmd_lsu_from,$ohsce_olmd_lsu_port);
			unset($ohsce_olmd_reslu_k_read);
			}
			}
			break;
			case "write":
				if(isset($ohsce_olmd_mhold_canwritey[$ohsce_olmd_lsu_k],$ohsce_olmd_cread_data_b['data'])){
				if($ohsce_olmd_mhold_canwritey[$ohsce_olmd_lsu_k]=="1"){
					ohsce_smWrite($ohsce_olmd_mhold[$ohsce_olmd_lsu_k],$ohsce_olmd_cread_data_b['data']);
					Ohsce_socketsend($ohsceolmdudpserver['socket'],'success',0,0,$ohsce_olmd_lsu_from,$ohsce_olmd_lsu_port);
					break;
				}
			    }
				Ohsce_socketsend($ohsceolmdudpserver['socket'],'Forbidden!',0,0,$ohsce_olmd_lsu_from,$ohsce_olmd_lsu_port);
			break;
			default:
				Ohsce_socketsend($ohsceolmdudpserver['socket'],'Forbidden!',0,0,$ohsce_olmd_lsu_from,$ohsce_olmd_lsu_port);
			break;
		}
			unset($ohsce_olmd_lsu_buf,$ohsce_olmd_lsu_from,$ohsce_olmd_lsu_port);
	}

	if(false!=ohsce_channel_read($ohsce_olmd_channe,$ohsce_olmd_channe_date,$ohsce_olmd_channe_from,$ohsce_olmd_channe_port)){
		$ohsce_olmd_channe_date=ohsce_mcrypt($ohsce_olmd_channe_date,OHSCE_OLMD_MADDRESSPASS,"d");
		$ohsce_olmd_cnew_data=ohsce_smDecode($ohsce_olmd_channe_date);
		if(isset($ohsce_olmd_cnew_data['ad'])){
			$ohsce_olmd_cnew_ad=strtolower($ohsce_olmd_cnew_data['ad']);
		if($ohsce_olmd_cnew_ad=="add"){
			if(!isset($ohsce_olmd_cnew_data['key'])){
				$ohsce_olmd_channe_rdate['res']=false;
			    $ohsce_olmd_channe_rdate['key']=false;
				ohsce_reChannel($ohsce_olmd_channe,$ohsce_olmd_channe_rdate,$ohsce_olmd_channe_from,$ohsce_olmd_channe_port);
				usleep(100);
				goto olmdnext;
			}
			$ohsce_olmd_cnew_k=intval($ohsce_olmd_cnew_data['key']);
			if(isset($ohsce_olmd_mhold[$ohsce_olmd_cnew_k])){
				if(false!=ohsce_smRead($ohsce_olmd_mhold[$ohsce_olmd_cnew_k],$ohsce_olmd_cnew_k_read)){
					$ohsce_olmd_channe_rdate['res']=true;
			        $ohsce_olmd_channe_rdate['key']=$ohsce_olmd_cnew_k;
					$ohsce_olmd_channe_rdate['msg']='warning!';
					ohsce_reChannel($ohsce_olmd_channe,$ohsce_olmd_channe_rdate,$ohsce_olmd_channe_from,$ohsce_olmd_channe_port);
					usleep(100);
					unset($ohsce_olmd_cnew_k_read);
					unset($ohsce_olmd_channe_rdate['msg']);
				    goto olmdnext;
				}
			}
			ohsce_smCreat($ohsce_olmd_cnew_a,$ohsce_olmd_cnew_k);
			$ohsce_olmd_mhold[$ohsce_olmd_cnew_k]=$ohsce_olmd_cnew_a;
			if(isset($ohsce_olmd_cnew_data['canwritey'])){
				if($ohsce_olmd_cnew_data['canwritey']==1){
					$ohsce_olmd_mhold_canwritey[$ohsce_olmd_cnew_k]=1;
				}
			}
			$ohsce_olmd_channe_rdate['res']=true;
			$ohsce_olmd_channe_rdate['key']=$ohsce_olmd_cnew_k;
			ohsce_reChannel($ohsce_olmd_channe,$ohsce_olmd_channe_rdate,$ohsce_olmd_channe_from,$ohsce_olmd_channe_port);
		}elseif($ohsce_olmd_cnew_ad=="del"){
			if(!isset($ohsce_olmd_cnew_data['key'])){
				olmdjmpdelle:
				$ohsce_olmd_channe_rdate['res']=false;
			    $ohsce_olmd_channe_rdate['key']=false;
			    ohsce_reChannel($ohsce_olmd_channe,$ohsce_olmd_channe_rdate,$ohsce_olmd_channe_from,$ohsce_olmd_channe_port);
				usleep(100);
				goto olmdnext;
			}
			$ohsce_olmd_cnew_dk=intval($ohsce_olmd_cnew_data['key']);
			if(!isset($ohsce_olmd_mhold[$ohsce_olmd_cnew_dk])) goto olmdjmpdelle;
			$ohsce_olmd_cnew_da=$ohsce_olmd_mhold[$ohsce_olmd_cnew_dk];
			echo $ohsce_olmd_cnew_dk;
			ohsce_smClose($ohsce_olmd_cnew_da);
			unset($ohsce_olmd_mhold[$ohsce_olmd_cnew_dk]);
			if(isset($ohsce_olmd_mhold_canwritey[$ohsce_olmd_cnew_dk])){
				unset($ohsce_olmd_mhold_canwritey[$ohsce_olmd_cnew_dk]);
			}
			$ohsce_olmd_channe_rdate['res']=true;
			$ohsce_olmd_channe_rdate['key']=$ohsce_olmd_cnew_dk;
			ohsce_reChannel($ohsce_olmd_channe,$ohsce_olmd_channe_rdate,$ohsce_olmd_channe_from,$ohsce_olmd_channe_port);
		}elseif($ohsce_olmd_cnew_ad=="write"){
			if(isset($ohsce_olmd_mhold_canwritey[$ohsce_olmd_cnew_data['key']],$ohsce_olmd_cnew_data['data'])){
				if($ohsce_olmd_mhold_canwritey[$ohsce_olmd_cnew_data['key']]=="1"){
					ohsce_smWrite($ohsce_olmd_mhold[$ohsce_olmd_cnew_data['key']],$ohsce_olmd_cnew_data['data']);
					ohsce_reChannel($ohsce_olmd_channe,'success',$ohsce_olmd_channe_from,$ohsce_olmd_channe_port);
				}else{
					goto olmdwft;
				}
			    }else{
				olmdwft:
				ohsce_reChannel($ohsce_olmd_channe,'faild',$ohsce_olmd_channe_from,$ohsce_olmd_channe_port);
				}
		}elseif($ohsce_olmd_cnew_ad=="read"){
			if(isset($ohsce_olmd_mhold[$ohsce_olmd_cnew_data['key']])){
					$ohsce_olmd_channe_rdate['res']=true;
					$ohsce_olmd_channe_rdate['data']=ohsce_smRead($ohsce_olmd_mhold[$ohsce_olmd_cnew_data['key']],$ohsce_olmd_readdata);
			    }else{
					$ohsce_olmd_channe_rdate['res']=false;
				}
			    $ohsce_olmd_channe_rdate['key']=$ohsce_olmd_cnew_data['key'];
				ohsce_reChannel($ohsce_olmd_channe,$ohsce_olmd_channe_rdate,$ohsce_olmd_channe_from,$ohsce_olmd_channe_port);
				unset($ohsce_olmd_readdata);
		}else{
			usleep(100);
			goto olmdnext;
		}
		}
	}
	usleep(100);
goto olmdstarloop;
goto terror;
pcenter:
$ohsce_pcenter_needrun=scandir($OHSCE_PLdir);
$ohsce_pcenter_i=0;
$ohsce_pcenter_mkey=array();
foreach($ohsce_pcenter_needrun as &$ohsce_pcenter_needrun_hp){
	if(substr($ohsce_pcenter_needrun_hp,-4)!=".php"){
		continue;
	}
	$ohsce_pcenter_i++;
	include($OHSCE_PLdir.$ohsce_pcenter_needrun_hp);
	if(!isset($ohsce_pcenter_pr_prun,$ohsce_pcenter_pr_name,$ohsce_pcenter_memmorykey)){
		echo 'The ['.$OHSCE_PLdir.$ohsce_pcenter_needrun_hp.'] is an error ohscepcenterfile!'.PHP_EOL;
		continue;
	}
	$ohsce_pcenter_willrun_v=OHSCE_PHPDIR.' '.OHSCE_ROOTDIR.$ohsce_pcenter_pr_prun;
	$ohsce_pcenter_willrun[$ohsce_pcenter_i]=$ohsce_pcenter_willrun_v;
	$ohsce_pcenter_willrun_name[$ohsce_pcenter_i]=$ohsce_pcenter_pr_name;
	$ohsce_pcenter_runlx[$ohsce_pcenter_i]='HARD';
	if(!isset($ohsce_pcenter_memmorykey)) goto pcentermfnull;
	if(in_array($ohsce_pcenter_memmorykey,$ohsce_pcenter_mkey)){
		pcentermfnull:
		$ohsce_pcenter_memmorykey=null;
		$ohsce_pcenter_mkey[$ohsce_pcenter_i]=null;
		$ohsce_pcenter_mf[$ohsce_pcenter_i]=null;
		goto pcentermfjs;
	}
	$ohsce_pcenter_mkey[$ohsce_pcenter_i]=$ohsce_pcenter_memmorykey;
	if(false==ohsce_smCreat($ohsce_pcenter_nmf,$ohsce_pcenter_memmorykey,"n")){
		ohsce_smCreat($ohsce_pcenter_nmf,$ohsce_pcenter_memmorykey,"c");
		pcentermfragain:
		ohsce_smRead($ohsce_pcenter_nmf,$ohsce_pcenter_nmf_w,true);
		if(isset($ohsce_pcenter_nmf_w_i)) goto pcentermfrb;
		if(!isset($ohsce_pcenter_nmf_w['onwer'])){
			$ohsce_pcenter_mf[$ohsce_pcenter_i]=null;
			unset($ohsce_pcenter_nmf_w);
			goto pcentermfjs;
		}
		if("pcenter"!=$ohsce_pcenter_nmf_w['onwer']){
			$ohsce_pcenter_mf[$ohsce_pcenter_i]=null;
			unset($ohsce_pcenter_nmf_w);
			goto pcentermfjs;
		}
		pcentermfrb:
		if(!isset($ohsce_pcenter_nmf_w['onwer'],$ohsce_pcenter_nmf_w['startime'],$ohsce_pcenter_nmf_w['heart'],$ohsce_pcenter_nmf_w['zt'])){
			$ohsce_pcenter_mf[$ohsce_pcenter_i]=null;
			if(!isset($ohsce_pcenter_nmf_w_i)){
				$ohsce_pcenter_nmf_w_i=true;
				usleep(100);
				goto pcentermfragain;
			}
			unset($ohsce_pcenter_nmf_w,$ohsce_pcenter_nmf_w_i);
			goto pcentermfjs;
		}
		$ohsce_pcenter_mf[$ohsce_pcenter_i]=$ohsce_pcenter_nmf;
		goto pcentermfjs;
	}
	$ohsce_pcenter_mf_data=array('onwer'=>"pcenter",'startime'=>time(),'heart'=>time(),'zt'=>"wait");
	ohsce_smWrite($ohsce_pcenter_nmf,$ohsce_pcenter_mf_data);
	$ohsce_pcenter_mf[$ohsce_pcenter_i]=$ohsce_pcenter_nmf;
	pcentermfjs:
		unset($ohsce_pcenter_pr_prun,$ohsce_pcenter_pr_name,$ohsce_pcenter_memmorykey);
}
unset($ohsce_pcenter_willrun_v);
if(!isset($ohsce_pcenter_willrun,$ohsce_pcenter_willrun_name,$ohsce_pcenter_runlx)){
	$ohsce_pcenter_willrun=array();
	$ohsce_pcenter_willrun_name=array();
	$ohsce_pcenter_runlx=array();
	$ohsce_pcenter_mf=array();
	goto pcenterstarlisten;
}
foreach($ohsce_pcenter_willrun as $ohsce_pcenter_willrun_id => &$ohsce_pcenter_willrun_v){
	if($ohsce_pcenter_mf[$ohsce_pcenter_willrun_id]!=null){
		pcenterrunagain:
		if(false==ohsce_smRead($ohsce_pcenter_mf[$ohsce_pcenter_willrun_id],$ohsce_pcenter_callrm_w)){
			if(isset($ohsce_pcenter_nmf_again_i)){
				unset($ohsce_pcenter_nmf_again_i);
				$ohsce_pcenter_mf[$ohsce_pcenter_willrun_id]=null;
				$ohsce_pcenter_mkey[$ohsce_pcenter_willrun_id]=null;
				goto pcenterrungoona;
			}
			ohsce_smCreat($ohsce_pcenter_nmf_again,$ohsce_pcenter_mkey[$ohsce_pcenter_willrun_id],"c");
			$ohsce_pcenter_mf[$ohsce_pcenter_willrun_id]=$ohsce_pcenter_nmf_again;
			$ohsce_pcenter_nmf_again_i=true;
			usleep(100);
			goto pcenterrunagain;
		}
		ohsce_smDecode($ohsce_pcenter_callrm_w);
		$ohsce_pcenter_callrm_data=array('onwer'=>"pcenter",'startime'=>$ohsce_pcenter_callrm_w['startime'],'heart'=>time(),'zt'=>'run');
		ohsce_smWrite($ohsce_pcenter_mf[$ohsce_pcenter_willrun_id],$ohsce_pcenter_callrm_data);
		unset($ohsce_pcenter_callrm_w,$ohsce_pcenter_callrm_data);
	}
	pcenterrungoona:
	if(OHSCE_OS=="Windows"){
	$ohsce_pcenter_system_p=$OHSCE_pdefend_vbs.' '.OHSCE_PHPDIR.' '.$OHSCE_pdefend.' '.base64_encode($ohsce_pcenter_willrun_v);
	if($ohsce_pcenter_mkey[$ohsce_pcenter_willrun_id]!=null){
		$ohsce_pcenter_system_p=$ohsce_pcenter_system_p.' -k '.$ohsce_pcenter_mkey[$ohsce_pcenter_willrun_id];
	}
	system($ohsce_pcenter_system_p);
	$ohsce_pcenter_recall_system[$ohsce_pcenter_willrun_id]=$ohsce_pcenter_system_p;
	}else{
		$ohsce_pcenter_system_p=OHSCE_PHPDIR.' '.$OHSCE_pdefend.' '.base64_encode($ohsce_pcenter_willrun_v);
	    if($ohsce_pcenter_mkey[$ohsce_pcenter_willrun_id]!=null){
		$ohsce_pcenter_system_p=$ohsce_pcenter_system_p.' -k '.$ohsce_pcenter_mkey[$ohsce_pcenter_willrun_id];
	}
	$ohsce_pcenter_xinux_pf[$ohsce_pcenter_willrun_id]=popen($ohsce_pcenter_system_p,"w");
	$ohsce_pcenter_recall_system[$ohsce_pcenter_willrun_id]=$ohsce_pcenter_system_p;
	}
}
if(OHSCE_OS!="Windows") goto pcenterlinuxstarlisten;
pcenterstarlisten:
pcenterlinuxstarlisten:
foreach($ohsce_pcenter_willrun as $ohsce_pcenter_willrun_id => &$ohsce_pcenter_willrun_v){
	if($ohsce_pcenter_mf[$ohsce_pcenter_willrun_id]!=null){
		pcenterrunagainb:
		if(false==ohsce_smRead($ohsce_pcenter_mf[$ohsce_pcenter_willrun_id],$ohsce_pcenter_callrm_w)){
			if(isset($ohsce_pcenter_nmf_again_i)){
				unset($ohsce_pcenter_nmf_again_i);
				$ohsce_pcenter_mf[$ohsce_pcenter_willrun_id]=null;
				$ohsce_pcenter_mkey[$ohsce_pcenter_willrun_id]=null;
				continue;
			}
			ohsce_smCreat($ohsce_pcenter_nmf_again,$ohsce_pcenter_mkey[$ohsce_pcenter_willrun_id],"c");
			$ohsce_pcenter_mf[$ohsce_pcenter_willrun_id]=$ohsce_pcenter_nmf_again;
			$ohsce_pcenter_nmf_again_i=true;
			usleep(100);
			goto pcenterrunagainb;
		}
		ohsce_smDecode($ohsce_pcenter_callrm_w);
		if($ohsce_pcenter_callrm_w['zt']=="running"){
			$ohsce_pcenter_forkrun_heart=intval($ohsce_pcenter_callrm_w['heart']);
			if((time()-$ohsce_pcenter_forkrun_heart)>$OHSCE_pdefend_recalltime){
				if(OHSCE_OS=="Windows"){
				system($ohsce_pcenter_recall_system[$ohsce_pcenter_willrun_id]);
				}else{
				$ohsce_pcenter_xinux_pf[$ohsce_pcenter_willrun_id]=popen($ohsce_pcenter_recall_system[$ohsce_pcenter_willrun_id],"w");
				}
			}
		}
		if($ohsce_pcenter_callrm_w['zt']=="run"){
			$ohsce_pcenter_forkrun_heart=intval($ohsce_pcenter_callrm_w['heart']);
			if((time()-$ohsce_pcenter_forkrun_heart)>$OHSCE_pdefend_recalltime){
				if(OHSCE_OS=="Windows"){
				system($ohsce_pcenter_recall_system[$ohsce_pcenter_willrun_id]);
				}else{
				$ohsce_pcenter_xinux_pf[$ohsce_pcenter_willrun_id]=popen($ohsce_pcenter_recall_system[$ohsce_pcenter_willrun_id],"w");
				}
				$ohsce_pcenter_forkrun_rrdata=array('onwer'=>"pcenter",'startime'=>$ohsce_pcenter_callrm_w['startime'],'heart'=>time(),'zt'=>'run2');
		        ohsce_smWrite($ohsce_pcenter_mf[$ohsce_pcenter_willrun_id],$ohsce_pcenter_forkrun_rrdata);
			}
		}
		if($ohsce_pcenter_callrm_w['zt']=="run2"){
			$ohsce_pcenter_forkrun_heart=intval($ohsce_pcenter_callrm_w['heart']);
			if((time()-$ohsce_pcenter_forkrun_heart)>$OHSCE_pdefend_recalltime){
				if(isset($ohsce_pcenter_recall_system[$ohsce_pcenter_willrun_id])){
				unset($ohsce_pcenter_recall_system[$ohsce_pcenter_willrun_id]);
				}
				if(isset($ohsce_pcenter_mf[$ohsce_pcenter_willrun_id])){
				unset($ohsce_pcenter_mf[$ohsce_pcenter_willrun_id]);
				}
				if(isset($ohsce_pcenter_mkey[$ohsce_pcenter_willrun_id])){
				unset($ohsce_pcenter_mkey[$ohsce_pcenter_willrun_id]);
				}
				if(isset($ohsce_pcenter_willrun_name[$ohsce_pcenter_willrun_id])){
				unset($ohsce_pcenter_willrun_name[$ohsce_pcenter_willrun_id]);
				}
				if(isset($ohsce_pcenter_runlx[$ohsce_pcenter_willrun_id])){
				unset($ohsce_pcenter_runlx[$ohsce_pcenter_willrun_id]);
				}
				if(isset($ohsce_pcenter_willrun[$ohsce_pcenter_willrun_id])){
				unset($ohsce_pcenter_willrun[$ohsce_pcenter_willrun_id]);
				}
				echo 'System has removed ohsce_pcpid:#'.$ohsce_pcenter_willrun_id.' Please try again'.PHP_EOL;
			}
		}
		unset($ohsce_pcenter_callrm_w);
	}else{
		continue;
	}
}
sleep(3);
goto pcenterstarlisten;
goto terror;
pdefend:
$oibc_pdefend_csa=getopt('r:m:p:k:');
$oibc_pdefend_pkey=trim($oibc_pdefend_csa['p']);
if(isset($oibc_pdefend_csa['k'])){
$oibc_pdefend_mkey=trim($oibc_pdefend_csa['k']);
ohsce_smCreat($oibc_pdefend_mfd,$oibc_pdefend_mkey,"w");
ohsce_smRead($oibc_pdefend_mfd,$oibc_pdefend_mdata,true);
//ohsce_smDecode($oibc_pdefend_mdata);
if(isset($oibc_pdefend_mdata['zt'])){
$ohsce__pdefend_ndata=array('onwer'=>"pcenter",'startime'=>$oibc_pdefend_mdata['startime'],'heart'=>time(),'zt'=>'running');
ohsce_smWrite($oibc_pdefend_mfd,$ohsce__pdefend_ndata);
}else{
	$oibc_pdefend_mkey=null;
}
}else{
$oibc_pdefend_mkey=null;
}
if(($oibc_pdefend_pkey=="")or($oibc_pdefend_pkey==null)){
	$errmsg='P is empty!';
	goto terror;
}
$ohsce_pdefend_ml=trim(base64_decode($oibc_pdefend_pkey));
echo $ohsce_pdefend_ml;
$ohsce_pdefend_descriptorspec = array(
   0 => array("pipe", "r"),  
   1 => array("pipe", "w"),  
   //2 => array("file", "./log/pdefend_error-output.txt", "a")
);

$ohsce_pdefend_cwd = NULL;
$ohsce_pdefend_env = NULL;
$ohsce_pdefend_fp=proc_open($ohsce_pdefend_ml, $ohsce_pdefend_descriptorspec, $ohsce_pdefend_pipes, $ohsce_pdefend_cwd, $ohsce_pdefend_env);
$odcs=0;
pdefendstardefend:
if($oibc_pdefend_mkey!=null){
$ohsce__pdefend_ndata=array('onwer'=>"pcenter",'startime'=>$oibc_pdefend_mdata['startime'],'heart'=>time(),'zt'=>'running');
if(false==ohsce_smWrite($oibc_pdefend_mfd,$ohsce__pdefend_ndata)){
	ohsce_smCreat($oibc_pdefend_mfd,$oibc_pdefend_mkey,"c");
	if(!isset($oibc_pdefend_mfd_tagain)){
		$oibc_pdefend_mfd_tagain=true;
	goto pdefendstardefend;
	}else{
	goto pdefendgoonmdefend;
	}
  }
  if(isset($oibc_pdefend_mfd_tagain)) unset($oibc_pdefend_mfd_tagain);
}
pdefendgoonmdefend:
if(proc_get_status($ohsce_pdefend_fp)['running']){
	$ohsce_pdefend_pid=proc_get_status($ohsce_pdefend_fp)['pid'];
}else{
	//echo 'die';
	fclose($ohsce_pdefend_pipes[0]);
	fclose($ohsce_pdefend_pipes[1]);
	proc_close($ohsce_pdefend_fp);
	$ohsce_pdefend_fp=proc_open($ohsce_pdefend_ml, $ohsce_pdefend_descriptorspec, $ohsce_pdefend_pipes, $ohsce_pdefend_cwd, $ohsce_pdefend_env);
	/*
	if(is_resource($ohsce_pdefend_fp)){
	echo 'reliving';
    }
	*/
}
sleep(1);
goto pdefendstardefend;
sleep(30);
goto terror;
comrw:
set_time_limit(5);
$oibc_comrw_csa=getopt('r:m:p:w:k:');
if(false==ohsce_smCreat($oibc_comrw_mfd,$oibc_comrw_csa['k'],"w")){
	$errmsg='false';
	goto terror;
}
Ohsce_comopen($ohsce_comrw_rs,"com7",1);
Ohsce_comwrite($ohsce_comrw_rs,hex2bin(trim($oibc_comrw_csa['w'])));
$ohsce_comrw_rdlen=0;
comrwstarloop:
Ohsce_comread($ohsce_comrw_rs,$ohsce_comrw_buf,2,0);
$ohsce_comrw_rdata=bin2hex($ohsce_comrw_buf);
$ohsce_comrw_rdlen=strlen($ohsce_comrw_rdata)+$ohsce_comrw_rdlen;
ohsce_smWrite($oibc_comrw_mfd,bin2hex($ohsce_comrw_buf),$ohsce_comrw_rdlen-strlen($ohsce_comrw_rdata));
if($ohsce_comrw_buf!=""){
	$ohsce_comrw_buf_r=true;
}
if($ohsce_comrw_buf==""){
	if(isset($ohsce_comrw_buf_r)){
		Ohsce_comclose($ohsce_comrw_rs);
		exit;
	}
}
goto comrwstarloop;
goto terror;
callnp:
$oibc_cnp_csa=getopt('r:m:p:f:');
$ohsce_cnp_np=$oibc_cnp_csa['p'];
if((base64_decode($ohsce_cnp_np)!=false)and(base64_decode($ohsce_cnp_np)!="")and(base64_decode($ohsce_cnp_np)!=null)){
	$ohsce_cnp_np=base64_decode($ohsce_cnp_np);
}
$ohsce_cnp_system_np=$OHSCE_pdefendC_vbs.' '.$ohsce_cnp_np;
if($oibc_cnp_csa['f']=="system"){
system($ohsce_cnp_system_np);
}elseif($oibc_cnp_csa['f']=="exec"){
exec($ohsce_cnp_system_np);
}else{
popen($ohsce_cnp_system_np,'r');
}
sleep(30);
goto terror;
comserver:
$oibc_cnp_csa=getopt('r:m:p:c:');
Ohsce_eng_serial_setspace(3);
Ohsce_eng_serial_creat($hscecom,trim($oibc_cnp_csa['c'])); 
Ohsce_eng_serial_open($hscecom,false);
function comserveraccept(&$socket,$ip,$port,$zv){ 
	global $hscecom;
	$ohsce_cs_data=Ohsce_socketread($socket,1024);
	if(($ohsce_cs_data!=null)or($ohsce_cs_data[0]!=false)){
	Ohsce_eng_serial_write($hscecom,$ohsce_cs_data[1],false);
    Ohsce_eng_serial_read($hscecom,$data,null,true);
	Ohsce_socketwrite($socket,$data);
	}
	return true;
}
function comservera(&$socket,$buf,$len,$zv){  
    global $hscecom;
	Ohsce_eng_serial_write($hscecom,$buf,false);
    Ohsce_eng_serial_read($hscecom,$data,null,true);
	Ohsce_socketwrite($socket,$data);
	return true;
}
function comserveralways(&$oibc_clients_zv){
	global $hscecom;
	Ohsce_eng_serial_read($hscecom,$data,null,true);
	if((!is_null($data))and(strlen($data)>0)){
		foreach($oibc_clients_zv['clients'] as $okey => $osclient){
			if($okey=="0"){
				continue;
			}
			Ohsce_socketwrite($osclient,$data);
		}
	}
	return true;
}
Ohsce_eng_socket_server($ohsceserver,'tcp',intval(trim($oibc_cnp_csa['p'])),OHSCE_MYIP_SYSTEM,array('callback'=>'comservera','accept'=>'comserveraccept','fap'=>'comserveralways'),'comserveraccept');
Ohsce_eng_socket_server_runtcp($ohsceserver); //开始运行
goto terror;
wext:
$oibc_wext_csa=getopt('r:m:n:e:');
$oibc_wext_dir='../ext/'.trim($oibc_wext_csa['e']).'/ext.php';
if(file_exists($oibc_wext_dir)){
	include($oibc_wext_dir);
}
$mode=trim($oibc_wext_csa['n']);
goto reload;
goto terror;
terror:
exit($errmsg);
sleep(30);
