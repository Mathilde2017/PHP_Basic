<?php 
/****
一，Memcache简介及安装
****/
随着网站访问量的上升，系统会变得越来越卡，究其原因一般都是因为数据库在读取磁盘数据的时候效率太慢导致；
在实际的项目中，我们如何来缓解数据库读写的效率问题呢？
memcache作为一个非常流行的主句缓存解决方案，它有那些特点和使用限制呢？

	1.1 什么是Memcache？
	应用系统(网站)访问量上升带来的问题：
	 - 原先足够的10M(可能更小一些)的网络带宽现在开始捉襟见肘
	 - 应用程序频繁读取数据库，造成数据库负载上升响应变慢，甚至死机
	 数据库性能吃紧的根本原因：磁盘读写速度太慢！
	 解决方案：可以考虑将数据缓存到内存中区，应用程序直接到内存中读取数据，不再从数据库读。
	 内存读写速度比磁盘读写速度快几个数量级。

	 Memcache 就是这样的分布式内存对象缓存数据库(本身不具备分布式功能)，通过key-value的方式把数据
	 存储到内存中去。
	工作原理：通过内存中缓存数据和对象来减少读取数据库的次数，从而提高了系统访问的速度，系统架构上
	位于DB层与应用层之间。
	特点：Memcache 是衣蛾村村键值对的HashMap，在内存中对任意的数据(比如字符串、对象等)使用key-value存储，
	数据可以来自数据库的调用、API调用，或者页面渲染的结果。

	浏览器  --->应用服务器  --->传统关系型DB(以前)
	浏览器  --->应用服务器  --->Memcache  --->DB(现在)
	首次访问，数据从传统关系型DB中读取，返回给应用服务器，并保存到Memcache
	以后每次访问，数据都直接从 Memcache 中读取，不再访问传统关系型DB

	特性和限制：
	Memcache 中可以保存哦item数据量是没有限制的，只要内存足够
	Memcache支持单线程在32位机中最大使用内存为2G，64位机则没有限制
	Key最大为250个字节，超过该长度无法存储
	单个item最大数据是1MB，超过1MB的数据不予存储
	Memcache服务器端是不安全的，比如已知某个Memcache节点，可以直接Telnet过去，并通过flush_all 让已经存在的键值对立即失效
	不能遍历 Memcache 中所有的item，因为这个操作的速度相对缓慢且会阻塞其他的操作
	Memcache 的高性能源自于两阶段哈希结构：第一阶段在客户端，通过Hash算法根据key值算出一个节点；第二阶段在
	服务器端，通过一个内部的Hash算法，查找真正的item并返回给客户端。从实现角度看，Memcache 是一个
	非阻塞、基于时间的服务器程序
	Memcache 设置添加某一个key值的时候，传入expiry为0表示这个key值永久有效，但是这个key值也会爱30天之后失效

	小结：
	Memcache 是一个分布式的内存数据缓存服务器
	在应用系统中处于数据库层和应用层之间
	Memcache 本身是多线程的，读写速度非常快

	1.2 安装Memcache
	1.2.1 Windows上安装
		- 下载Memcache的Windows版本，32位系统选择32位版，64位系统选择64位版
		- 进入 Memcache.exe 所在目录，管理员身份打开cmd命令行(虽然是.exe文件，只能用命令行安装，不能通过双击安装！！！)
		- 输入命令：memcached -d install,将Memcache安装位系统服务
		- 验证安装：memcached -h
		- 启动服务：memcached -d start
		- 连接Memcache：telnet localhost 11211
	1.2. linux上安装
		- 安装libevent-devel(Memcache依赖 libevent-devel)
			yum -y install libevent-devel
		- 官网下载 Memcache 的Linux版本：http://memcached.org/
			wget http://memcached.org/files/memcached 1.4.35.tar.gz
		- 解压：tar -zxvf memcached-1.4.35.tar.gz
		- 进入memcache目录：cd memcached-1.4.35.tar.gz
		- 编译安装：./configure && make && sudo make install (如果安装成功，可以在/usr/local/bin 找到Memcache)
		- 启动Memcache：/usr/local/bin/memcached -d -m 100 -u root -l 127.0.0.1 -p 12000 -c 256 -P /tmp/memcached.pid
		- 检测是否启动成功：ps aux lgrep memcached

		linux中 Memcache 启动参数说明
		/usr/local/bin/memcached -d -m 100 -u root -l 127.0.0.1 -p 11211 -c 256 -P /tmp/memcached.pid
		-d 选项是启动一个守护进程
		-m 是分配给 Memcache使用的内存数量，单位是MB，这里是100MB
		-u 是运行Memcache的用户，这里是root
		-l 是监听的服务器IP地址，治理制定了服务器的IP地址 127.0.0.1
		-p 是设置了Memcache监听的断开，这里设置了11211，最好是1024以上的端口
		-c 选项是最大运行的并发连接数，默认是1024，这里设置了256，按照你服务器的负载量来设定
		-P 是设置了保存 Memcache 的pid文件，这里是保存在 /tmp/memcached.pid

	1.3 PHP中安装Memcache扩展
	1.3.1 Windows上安装
		- 下载Memcache的Windows版本
			https://windows.php.net/downloads/pecl/releases/memcache/3.0.8
		- 找到php_memcache.dll，复制到对应的php/ext目录中
		- 打开php.ini文件，添加一行，extension=php_memcache.dll
		- 重启服务器
		- 使用phpinfo查看memcache扩展是否安装成功
	1.3.2 linux上安装
		- 安装zlib,zlib-devel
			yum install zlib
			yum install zlib-devel
		- 下载memcached的源码
			wget http://pecl.php.net/get/memcached-2.2.0.tgz
		- 解压
			tar -zxvf memcached-2.2.0.tgz
		- 生成configure
			./configure --with-php-config=/usr/local/php/bin/php-config --enable-memcached --disable-memcached-sasl
			./configure --with-php-config=/usr/local/php/bin/php-config --enable-memcached
			make && make install
		- 添加模块到php：vim/etc/php.ini，添加：extension=memcached.so
		- 重启服务器
		- 使用phpinfo查看memcache扩展是否安装成功


/****
二，Memcache常用命令
****/
	使用Telnet可以连接到memcache，对memcache进行管理
	set：用于向缓存添加新的键值对。如果键已经存在，则之前的值将被替换。
	set     userId       0         0	            5	  \n 	12345
		key      flag     expiretime        byte                  value

	get ：用于检索与键值对相关的值，如果键存在于缓存中，则返回相应的值。如果不存在，则不返回任何内容
	get userId
	       key 

	delete：用于删除memcached中的任何现有值
		delete userId
			key

	flush_all：用于清空换窜种的所有键/值对(设置所有键/值对过期)

/****
三，Memcache与php集成
****/
	3.1 PHP使用api操作memcache
	set：将数据存储到memcache
		$mem = new Memcache();
		// 连接Memcache
		if (!$mem->connect("127.0.0.1")) {
			echo "连接Memcache服务器失败！";
		}
		// 设置，'myword' 参数代表key,'hello world' 代表存放的值，MEMCACHE_COMPRESSED代表压缩
		// 内容，50代表存放时间，单位秒
		if ($mem->set('myword','hello world',MEMCACHE_COMPRESSED,50)) {
			echo "设置值成功";
		}

	get：从memcache中取值
		$mem = new Memcache();
		// 连接Memcache
		if (!$mem->connect("127.0.0.1")) {
			echo "连接Memcache服务器失败！";
		}
		
		$value = $mem->get('myword');
		if (!$value) {
			echo "读取失败";
		}else{
			echo "值是：". $value;
		}

	delete：删除memcache的值
		$mem = new Memcache();
		// 连接Memcache
		if (!$mem->connect("127.0.0.1")) {
			echo "连接Memcache服务器失败！";
		}

		$mem->delete('myword');

		$value = $mem->get('myword');
		if (!$value) {
			echo "读取失败";
		}else{
			echo "值是：". $value;
		}
	flush：清空所有键/值
		$mem ->flush();

	总结：
		PHP的memcache扩展提供的操作memcache的方法与memcache客户端命令相似
		向memcache添加、修改数据，使用set方法
		获取memcache中一个数据元素，使用get方法
		删除memcache中一个数据项元素，使用delete方法
		清空memcache中所有的数据，使用flush方法

	3.2 Tp5集成memcache
		- 缓存配置
			将 cache.php 中的 驱动方式改为 'type' =>'memcache',即可
		总结：
			Tp5内置了file，redis，memcache等常用的缓存系统
			通过配置config/cache.php文件的type为memcache可以将memcache作为Tp5的缓存来使用
			使用Tp5的Cache类的get和set方法可以方便的管理memcache；

?>