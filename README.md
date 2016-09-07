# OHSCE高可靠性的PHP通信框架.
#Open HI-REL Signal Communication Engine
<br />PHP以太网（TCP/UDP/ICMP）、RS232、RS485通信，可广泛直接或桥接各种网络工程通信。
<br />特别合适于对可靠性要求较高、上位机与末端协同工作的场景。如物联网设备通信、智能化系统、工业与自动化系统、可靠网络服务器。
<br />可运行于Windows、Linux、OS X。对Windows提供了全项功能支持，与自动化生态亲和。
<br />不过分追求高性能，充分平衡了可靠性与高性能。
<br />天生支持分布式，可大规模部署。
<br />在保证您的网络可靠性的前提下能跑出强悍的性能。
<br />过程化函数风格的框架具备高效的特性。
<br />特别亲切于工业自动化工程师、硬件工程师、物联网工程师、追求效率的PHP工程师的写法风格。
<br />OHSCE开放源代码，在OHSCE授权协议框架下您可以免费使用！
#快速入门
一、检查环境是否支持OHSCE
系统：Windows / Linux / OSX
          建议:WINDOWSSERVER2008及以上 UbuntuServer14.04LTS及以上
PHP:5.3及以上
          建议PHP5.4及以上
PHP扩展:
           Socket,Shmop,Curl,dio(建议)
二、修改配置文件
           /config/oibc_sce_config.php
三、运行测试程序
3.1工作在以太网上
一个TCP服务端测试程序：

<?php
ini_set('memory_limit',"88M");//重置php可以使用的内存大小为64M
set_time_limit(0);
ob_implicit_flush(1);
i n c l u d e('loadohsce.php');
$trya='ohsce_server_Example ';
function example(&$socket,$buf,$len,$zv){  //收到数据时的回调函数
	global $trya;
	echo $buf;
	Ohsce_socketwrite($socket,$trya.'hi '.$buf);
	return true;
}
function exampleaccept(&$socket,$ip,$port,$zv){  //新客户端到访时的回调函数
	global $trya;
	Ohsce_socketwrite($socket,$trya.'Welcome'.$ip.':'.$port);
	return true;
}
Ohsce_eng_socket_server($ohsceserver,'tcp',7626,'127.0.0.1','example','exampleaccept');//创建一个TCP服务端资源 绑定127.0.0.1:7626 并传入回调函数
Ohsce_eng_socket_server_runtcp($ohsceserver); //开始运行
对应的客户端测试程序:


<?php
ini_set('memory_limit',"88M");//重置php可以使用的内存大小为64M
set_time_limit(0);
ob_implicit_flush(1);
i n c l u d e('loadohsce.php');
Ohsce_eng_socket_client($ohsceclient,'tcp',7626,'127.0.0.1'); //创建一个TCP客户端资源并连接27.0.0.1:7626
echo Ohsce_socketread($ohsceclient['socket'],1024)[1]; //收取欢迎信息
Ohsce_socketsend($ohsceclient['socket'],'hello');  //发送数据
echo Ohsce_socketread($ohsceclient['socket'],1024)[1]; //收取回复数据
sleep(30);

3.2工作在工业自动化现场控制网络上

操作串口RS232/485测试程序:
<?php
ini_set('memory_limit',"88M");//重置php可以使用的内存大小为64M
set_time_limit(0);
ob_implicit_flush(1);
i n c l u d e('loadohsce.php');
Ohsce_eng_serial_creat($hscecom,"com7"); //OHSCE会默认为你创建一个 9600,n,8,1 写读的串口资源
Ohsce_eng_serial_open($hscecom); //一旦通过该函数成功开启了串口，此串口就被OHSCE进程占用了 此时串口资源变为可用状态
Ohsce_eng_serial_write($hscecom,"01030001000415c9",true);//向串口设备发送数据 以16进制发送
Ohsce_eng_serial_read($hscecom,$data,null,true); // 读取串口数据 返回数据长度为未知 以16进制返回
echo $data; //输出数据
sleep(30);

3.3测试使用OHSCE的进程守护
我们先写一个无用的自杀进程
<?php
sleep(60);
exit;
编写OHSCE-PCENTER入口文件:
<?php
$ohsce_pcenter_pr_name='测试';
$ohsce_pcenter_pr_prun='\pexample.php';
$ohsce_pcenter_memmorykey=6901;//注意 不得与生成器冲突，否则请提前注册。
启动并守护这个进程

正式运行使用隐藏窗口模式。
不断完善中，感谢支持。
