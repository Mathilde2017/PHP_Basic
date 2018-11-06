<?php 
/****
一，Redis简介及安装
****/
	// 1.1 Redis介绍
	Redis和memcache一样，也是一个key - value 内存存储系统。
	1.支持多种数据类型：
	string 、list(列表)、set(集合)、zset(sorted set 有序集合)和hash(哈希类型)，比memcache丰富；
	2 支持的操作：
	push/pop 、add/remove及取交集并集和差集及更丰富的操作，而且这些操作都是原子性的。
	3 特点：
	redis会周期性的把更新的数据写入磁盘或者把修改操作写入追加的记录文件，并且在此基础上实现了master-slave(主从)同步
	4 Redis是单线程的
	5 性能：
		测试完成了50个并发执行100000个请求。
		设置和获取的值是256字节字符串。
		Linux主机：X3320 Xeon 2.5 Ghz。
		文本执行使用 lookpack 接口(127.0.0.1).
		结果：读取速度是110000次/s,写的速度是 81000次/s。
	6 支持语言：Java php c ruby .....
	7 常用的可视化管理软件：
		Redis Desktop Manager(客户端软件)、treeNMS管理工具(Web).

	8 总结：
		- 相对于memcache只支持字符串类型，redis支持的数据类型要多的多。
		- redis 可以将数据保存到磁盘中，实现数据的持久化。
		- redis 是单进程的，所以可以避免一些由于并发产生的棘手问题。

	// 1.2 安装Redis
		// widows上安装
		1.2.1 下载redis的Windows版本zip版（redis官方并没有Windows版，社区有）。
		https://github.com/MicrosoftArchive/redis/releases

		1.2.2 启动redis：进入cmd，redis-server.exe redis.windows.conf
		注意：该方式启动后，cmd窗口不能关闭

		1.2.3 msi方式：
		下载Windows的msi安装包，双击安装，将redis作为Windows服务安装，安装后不会产生cmd窗口，
		redis在Windows中以服务形式存在

		总结：
		- redis的Windows版本需要到社区下载，分为zip版和msi版，在使用时注意选择合适的版本
		- 和memcache只能使用命令行安装不同，redis可通过双击方式打开安装
		- 和memcache一样，在Linux上安装redis的时候，也要注意一些依赖包的安装，如：GCC编译器等

	// 1.3 PHP中安装Redis扩展
		1.3.1 下载redis扩展的Windows版本
			https://windows.php.net/downloads/pecl/releases/redis/2.2.7
			- 将下载加压后的php_redis.dll 放入php的ext目录下。
			- 修改php.ini ,加入 extension =php_redis.dll
			- 重启Apache/nginx
			- 使用PHPinfo查看redis扩展是否安装成功
		1.3.2 Linux上安装
			- 下载：
			- 解压：
			- 

	总结：
		- 和memcache一样，PHP的redis扩展安装，只需要将php_redis.dll文件放到对应版本的php的ext目录中，
		然后修改php.ini文件，加入 extension = php_redis.dll就可以完成扩展的安装
		- 在Linux中需要先编译好redis.so文件，然后修改php.ini文件，加入extension = redis.so

/****
二，Redis常用命令
****/
	2.1 字符串：
		- keys：返回数据库中所有的key;用法：keys*
		- set：设置/更新缓存的值；用法：SET key value [EX seconds][PXmilinseconds]
			如：set user_name zhangsan EX60;
		- get：取值；用法：get key
			如：get user_name
		- incr ：自增；用法：incr key

	2.2 散列类型(每个hash可以存2的32次方 -1个 键值对，40多亿个)：
		- hset：为哈希表中的字段赋值；用法：hset hash表名 key value
			如：hset website baidu "www.baidu.com"
		- hget：从哈希表中取值；用法：hget hash表名 key
			如：hget website baidu
		- hgetall：返回hash表中所有值；用法：hgetall hash表名
			如：hgetall website

	2.3 队列类型
		- lpush：将一个或者多个值插入到列表头部；用法：lpush KEY value1...valueN
			如：lpush list1php,java,css
		- lrange：返回列表中指定区间内的元素；用法：lrange key 0 -1(返回所有)
			如：0代表第0个元素，1代表第1个元素，-1代表最后一个元素
		- lpop：移除并返回列表的第1个元素；用法：lpop key
			如：lpop list1
		- lrem：根据参数COUNT的值，移除列表中的参数VALUE相等的元素
				count >0：从表头开始向表尾搜索，移除与VALUE相等的元素，数量为COUNT
				count <0：从表尾开始向表头搜素，移除与VALUE相等的元素，数量为COUNT的绝对值
				count =0：移除表中所有与VALUE相等的值
			用法：lrem KEY COUNT VALUE
			如：lrem list1 1 java
		

/****
三， Redis与PHP集成
****/
	3.1 PHP操作redis
		- 连接redis
			// 实例化redis
			$redis = new Redis();
			// 连接
			$redis ->connect('127.0.0.1',6379);
			// 检测是否连接成功
			echo "Server is running" . $redis->ping();

		- 设置和取一个字符的值
			$redis ->set('name','php');	//设置
			echo $redis ->get('name');	//取值 php
			$redis ->set('name','js');	//重复设置
			echo $redis ->get('name');	//取值 js
		- 列表
			// 存储数据到列表中
			$redis ->lpush('list','php');
			$redis ->lpush('list','css');
			$redis ->lpush('list','js');
			// 获取列表中所有的值
			$list = $redis ->lrange('list',0,-1);
			print_r($list);echo '<hr>';
			// 从右侧加入一个
			$redis ->rpush('list','mysql');
			$list = $redis ->lrange('list',0,-1);
			print_r($list);echo '<hr>';
			// 从左侧弹出一个
			$redis ->lpop('list');
			$list = $redis ->lrange('list',0,-1);
			print_r($list);echo '<hr>';
			// 从右侧弹出一个
			$redis ->rpop('list');
			$list = $redis ->lrange('list',0,-1);
			print_r($list);echo '<hr>';

		- 字典
			echo $redis->hset('hash','animal','dog');echo '<br>';
			echo $redis->hset('hash','animal1','dog1');echo '<br>';
			echo $redis->hset('hash','animal2','dog2');echo '<br>';

			// 获取hash中某个key的值
			echo $redis->hget('hash','animal');echo '<br>'; //dog

			// 获取hash中所有key的值
			$arr = $redis ->hkeys('hash');
			print_r($arr);echo '<br>';

			// 获取hash表中所有的值，顺序是随机的
			$arr = $redis->hvals('hash');
			print_r($arr);echo '<br>';

			// 获取一个hash表中所有的key和value，顺序是随机的
			$arr = $redis->hgetall('hash');
			print_r($arr);echo '<br>';

			// 获取hash中的key的数量
			echo $redis ->hlen('hash');echo '<br>';

			// 删除hash中一个key，如果表不存在或key不存在则返回false
			echo $redis->hdel('hash','dog');echo '<br>';
			var_dump($redis->hdel('hash','animal'));echo '<br>';

	3.2 Thinkphp5集成Redis 
		修改配置文件config/cache.php，type改为redis
		总结：
			tp5.1 内置了 file、redis、memcache等常用的缓存系统；
			通过配置config/cache.php文件的type为redis可以将redis作为tp5.1的缓存来使用


一 redis是什么？
redis是一个NoSQL(Not Only SQL)数据库，即非关系型数据库。
redis是一个开源的，先进的键值(Key-Value)存储系统，它通常被称为数据结构服务器。因为键可以包含字符串，哈希，键表，集合和有序集合。同样的，它支持存储的Value类型很多，包括string 、list(列表)、set(集合)、zset(sorted set 有序集合)和hash(哈希类型)。这些数据都支持push/pop，add/remove及获取交集和并集及更丰富的操作，redis支持各种不同方式的排序。
为了保证效率，数据都是缓存在内存中，但也是因为它缓存在内存中，故存在诸如内存泄漏等风险性，为了防止这种风险及备份，它可以周期性的把更新的数据写入磁盘或者修改操作写入追加的记录文件。

二 为什么要使用redis？
传统的关系数据库在应付web2.0网站，特别是超大规模和高并发的SNS类型的web2.0纯动态网站已经显得力不从心。比如：对数据库高并发读写的需求，对海量数据的高效率存储和访问的需求等等。而NoSQL它可以作为关系型数据库的良好补充。

三 redis与MySQL的区别？
redis有数据库，没有表结构，没有字段列表，因为NoSQL和传统的关系型数据库不一样，不一定遵循传统数据库的一些基本要求，比如说遵循SQL标准/ACID属性、表结构等等，这类数据库主要存在以下特点：非关系型、分布式的、开源的、水平可扩展的。

四 redis的应用、数据模型及优劣？
典型应用：内容缓存，主要用于处理大量数据的高访问负载； 
数据模型：一系列键值对；
优势： 快速查询， 劣势：存储的数据缺少结构化，列存储数据库。
?>