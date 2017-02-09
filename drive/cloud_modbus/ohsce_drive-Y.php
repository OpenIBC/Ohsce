<?php
function ohsce_drive_cloud_modbus(&$a=null,$b="RTU",$c="read",$d=null,$e="40001",$f="0001",$g=null){
	$h='An error!';
	$i=array('modbus'=>$b,'do'=>$c,'address'=>$d,'start'=>$e,'len'=>$f,'data'=>$g);
	$i_re=ohsce_ext_ohscecloudapi('modbus',$i);
	if(!isset($i_re['apires'])){
		goto terror;
	}
	if(!$i_re['apires']){
		goto terror;
	}
	$res['msg']=$i_re['msg'];
	$res['res']=true;
	if($c=="check"){
		$res['data']=$i_re['data'];
		goto js;
	}
	$res['data']=hex2bin(trim($i_re['data']));
	if($a==null){
		goto js;
	}
	if(isset($a['comr'])){
		Ohsce_eng_serial_write($a,$res['data']);
		usleep(1);
		Ohsce_eng_serial_read($a,$j);
		$k=ohsce_ext_ohscecloudapi('modbus',array('modbus'=>$b,'do'=>'check','data'=>bin2hex($j)));
		if(!isset($k['apires'])){
	    $res['msg']='成功写入数据，但是读取时与OHSCE_CLOUD_API连接丢失，无法进行解析';
		$res['data']=false;
	    $res['res']=true;
		goto js;
	    }
	    if(!$k['apires']){
		$res['msg']='成功写入数据，但是读取的设备CRC校验未通过或连接被破坏/干扰，无法进行解析';
		$res['data']=false;
	    $res['res']=true;
		goto js;
	    }
		$res['msg']=$k['msg'];
		$res['data']=$k['data'];
	    $res['res']=true;
		goto js;
	}
	if(isset($a['socket'])){
		oibc_sce_socket_send($a,$res['data']);
		usleep(1);
		$j=oibc_sce_socket_recv($a);
		$k=ohsce_ext_ohscecloudapi('modbus',array('modbus'=>$b,'do'=>'check','data'=>bin2hex($j)));
		if(!isset($k['apires'])){
	    $res['msg']='成功写入数据，但是读取时与OHSCE_CLOUD_API连接丢失，无法进行解析';
		$res['data']=false;
	    $res['res']=true;
		goto js;
	    }
	    if(!$k['apires']){
		$res['msg']='成功写入数据，但是读取的设备CRC校验未通过或连接被破坏/干扰，无法进行解析';
		$res['data']=false;
	    $res['res']=true;
		goto js;
	    }
		$res['msg']=$k['msg'];
		$res['data']=$k['data'];
	    $res['res']=true;
		goto js;
	}
	goto terror;
	terror:
		$res['msg']=$h;
		$res['res']=false;
	return $res;
	js:
		return $res;
}