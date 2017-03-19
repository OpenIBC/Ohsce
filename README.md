# OHSCE高可靠性的PHP通信框架.
<BR />官方网站:WWW.OHSCE.ORG WWW.OHSCE.COM 最新版本V0.2.0 2017-02-10
<br />开发者QQ群：374756165（新2016-09） 捐助: http://www.ohsce.com/index.php/company/
<br /><img src="http://www.ohsce.com/data/upload/201611/f_d4f69a0cecf5298f56449166d0fe43c3.png"></img>
<br />官方源码源地址（获取最新发布的官方版）：
<br />GITHUB:https://github.com/OpenIBC/Ohsce
<br />GIT@OSC:https://git.oschina.net/SFXH/Ohsce
#Open HI-REL Signal Communication Engine
<br />PHP以太网（TCP/UDP/ICMP）、RS232、RS485通信，可广泛直接或桥接各种网络工程通信。
<br />特别合适于对可靠性要求较高、上位机与末端协同工作的场景。如物联网设备通信、智能化系统、工业与自动化系统、可靠网络服务器、中控&边控&驱动。
<br />可运行于Windows、Linux、OS X。对Windows提供了全项功能支持，与自动化生态亲和。
<br />不过分追求高性能，充分平衡了可靠性与高性能。
<br />天生支持分布式，可大规模部署。
<br />在保证您的网络可靠性的前提下能跑出强悍的性能。
<br />过程化函数风格的框架具备高效的特性。
<br />特别亲切于工业自动化工程师、硬件工程师、物联网工程师、追求效率的PHP工程师的写法风格。
<br />OHSCE开放源代码，在OHSCE授权协议框架下您可以免费使用！
<br />-----------------------------------------------------------------------------------
<br />PHP Ethernet (TCP/UDP/ICMP), RS232, RS485 communications, can be directly or directly bridged a variety of network engineering communications.
<br />It is especially suitable for the scene with high reliability, the upper computer and the end work together. Things such as equipment communications, intelligent systems, industrial and automation systems, reliable network server, central control & edge control & drive.
<br />Can run on Windows, Linux, OS X. Windows provides a full range of functional support, and automated ecological affinity.
Not too much pursuit of high performance, fully balanced reliability and high performance.
<br />Natural support for distributed, large-scale deployment.
<br />In the premise of ensuring the reliability of your network can run out of strong performance.
<br />The framework of the process function style has the characteristics of high efficiency.
<br />Special kind in industrial automation engineers, hardware engineers, network engineers, the pursuit of efficiency PHP engineer writing style.
<br />OHSCE open source code, under the OHSCE license agreement framework you can use free!
#快速入门
<br />http://www.ohsce.com/index.php/page/qstar.html
<br />一、检查环境是否支持OHSCE
<br />系统：Windows / Linux / OSX
 <br />         建议:WINDOWSSERVER2008及以上 UbuntuServer14.04LTS及以上
<br />PHP:5.4及以上
<br />          建议PHP5.4.9及以上
<br />PHP扩展:
 <br />          Socket,Shmop,Curl
<br />二、修改配置文件
<br />           /config/oibc_sce_config.php
<br />三、运行测试程序
<br />3.1工作在以太网上
<br />一个TCP服务端测试程序：

<br /><?php
<br />ini_set('memory_limit',"88M");//重置php可以使用的内存大小为64M
<br />set_time_limit(0);
<br />ob_implicit_flush(1);
<br />i n c l u d e('loadohsce.php');
<br />$trya='ohsce_server_Example ';
<br />function example(&$socket,$buf,$len,$zv){  //收到数据时的回调函数
<br />	global $trya;
<br />	echo $buf;
<br />	Ohsce_socketwrite($socket,$trya.'hi '.$buf);
<br />	return true;
<br />}
<br />function exampleaccept(&$socket,$ip,$port,$zv){  //新客户端到访时的回调函数
<br />	global $trya;
<br />	Ohsce_socketwrite($socket,$trya.'Welcome'.$ip.':'.$port);
<br />	return true;
<br />}
<br />Ohsce_eng_socket_server($ohsceserver,'tcp',7626,'127.0.0.1','example','exampleaccept');//创建一个TCP服务端资源 绑定127.0.0.1:7626 并传入回调函数
<br />Ohsce_eng_socket_server_runtcp($ohsceserver); //开始运行
<br />对应的客户端测试程序:

<br />
<br /><?php
<br />ini_set('memory_limit',"88M");//重置php可以使用的内存大小为64M
<br />set_time_limit(0);
<br />ob_implicit_flush(1);
<br />i n c l u d e('loadohsce.php');
<br />Ohsce_eng_socket_client($ohsceclient,'tcp',7626,'127.0.0.1'); //创建一个TCP客户端资源并连接27.0.0.1:7626
<br />echo Ohsce_socketread($ohsceclient['socket'],1024)[1]; //收取欢迎信息
<br />Ohsce_socketsend($ohsceclient['socket'],'hello');  //发送数据
<br />echo Ohsce_socketread($ohsceclient['socket'],1024)[1]; //收取回复数据
<br />sleep(30);
<br /><img src="http://www.ohsce.org/data/upload/201609/f_6a0f512daf19100c1ca24f040b5d53a0.gif"></img>
<br />
<br />3.2工作在工业自动化现场控制网络上
<br />
<br />操作串口RS232/485测试程序:
<br /><?php
<br />ini_set('memory_limit',"88M");//重置php可以使用的内存大小为64M
<br />set_time_limit(0);
<br />ob_implicit_flush(1);
<br />i n c l u d e('loadohsce.php');
<br />Ohsce_eng_serial_creat($hscecom,"com7"); //OHSCE会默认为你创建一个 9600,n,8,1 写读的串口资源
<br />Ohsce_eng_serial_open($hscecom); //一旦通过该函数成功开启了串口，此串口就被OHSCE进程占用了 此时串口资源变为可用状态
<br />Ohsce_eng_serial_write($hscecom,"01030001000415c9",true);//向串口设备发送数据 以16进制发送
<br />Ohsce_eng_serial_read($hscecom,$data,null,true); // 读取串口数据 返回数据长度为未知 以16进制返回
<br />echo $data; //输出数据
<br />sleep(30);
<br /><img src="http://www.ohsce.org/data/upload/201609/f_8f57eaa803acc6b137a5dcacf47a4995.gif"></img>
<br />
<br />3.2.2串口服务器
<br /><img src="http://www.ohsce.org/img/COMSERVER.gif"></i>
<br />3.3测试使用OHSCE的进程守护
<br />我们先写一个无用的自杀进程
<br /><?php
<br />sleep(60);
<br />exit;
<br />编写OHSCE-PCENTER入口文件:
<br /><?php
<br />$ohsce_pcenter_pr_name='测试';
<br />$ohsce_pcenter_pr_prun='\pexample.php';
<br />$ohsce_pcenter_memmorykey=6901;//注意 不得与生成器冲突，否则请提前注册。
<br />启动并守护这个进程
<br /><img src="http://www.ohsce.org/data/upload/201609/f_fb79fdc57845fe95cfa6a6812a471483.gif"></img>
<br />
<br />正式运行使用隐藏窗口模式。
<br />
<br />3.4.1CLOUD_API
<br />3.4.2 CLOUD_MODBUS_DRIVE
<BR />Ohsce_drive_cloud_modbus($comlink,"RTU","01",'01',"40001","0002");
<br />MODBUS-RTU的一个相对完整的示例:http://www.ohsce.com/index.php/article/27.html
<br />
<br />不断完善中，感谢支持。
#交流与捐助
开发者QQ群：
374756165（新2016-09）
<br />关于我们&合作:
<br />http://www.ohsce.com/index.php/company/
<br />捐助：
<br /><img src="http://www.ohsce.com/data/upload/201609/f_435f9ddd005975f43d6cd2559a63e138.jpg" height="150px" width="150px"><br /><img src="http://www.ohsce.com/data/upload/201609/f_5c89175114fe61466ad853795ee2c9cb.png" height="150px" width="150px"></img><br />ETH:0x42bCE0188534b27A156D6c80163d5248acb6a8EF
