<?php 
/****
一，MySQL数据表
****/
	- 1 MySQL数据类型
		命名规范：数据库、表、字段可用字符：A-Z，a-z，0-9 和_下划线；
		不可使用中文和特殊字符！

		数值数据类型
			整数类型：
				tinyint:最长3位数，常用于状态
				smallint:最长5位数，常用于知道的长度，如：省市区表
				mediumint：最长8位数，千万位，常用于小公司的用户
				int:最长10位，例如订单号，即使长度不够，宁愿分表都不用bigint
				bigint:最长19位，很少用，几乎不用，因为太占用内存
						如果大于10位的，例如：手机电话；就用varchar类型
			浮点小数类型：常用于存储单价，评分等
				float:单精度，最长6位，小数前4位，后面2位，
				double:双精度，经纬度等
			定点小数类型：
				decimal
		时间/日期 类型：
			year time date datetime timestamp 
			一般用整型int时间戳存储时间
		字符串类型
			文本类型：
			char      常用于存储加密后的32位密码
			varchar 变长，常用于图片地址，标题等比较简短的文本
			 text  一般用于存储富文本数据
			tinytext mediutext longtext enum set 
			二进制字符串
			bit binary varbinary tinyblob blog mediumblob longblob 
	- 2 


/****
二，MySQLi函数库
	使用php中的mysqli函数对数据库进行增删改查
****/
	-1 连接/关闭数据库
	mysqli_connect('127.0.0.1','root','root','database','3306'); //如果错误，会将错误显示，不安全
	$db = @mysqli_connect('127.0.0.1','root','root','database','3306'); // @屏蔽错误
	为了方便调试，PHP提供了错误信息提示函数：mysqli_connect_error()
	if (!$db) {
		exit('数据库连接失败' . mysqli_connect_error());
	}

	mysqli_close($db);//关闭数据库，不关闭的话，会占用数据库的资源

	- 2 mysqli函数插入数据
	$addtime = time();
	$sql = "INSERT INTO 'user' (id,name,phone,addtime) VALUES (null,'张三','18677778888','{$addtime}')";
	// 执行插入
	$res = mysqli_query($db,$sql);//执行成功，返回1，失败0
	if ($res) {
		$res = mysqli_insert_id($db);//获取自增ID
		return $res;
	}
	print_r($res);

	// 封装方法
	function Insert($db,$sql)
	{
		// 执行插入
		$return = mysqli_query($db,$sql);
		if ($return) {
			$return = mysqli_insert_id($db);
		}
		return $return;
	}

	- 2 mysqli函数修改、删除数据
	$sql = "UPDATE  'user' set name='lisi' WHERE id=1";
	mysqli_query($db,$sql);

	$sql = "DELETE FROM 'user' WHERE id<10 ";
	mysqli_query($db,$sql);//增删改执行成功都是返回1，失败返回0，但是查询不是，因为查询返回的是一个资源的集合

	- 3 mysqli函数查询数据
	$sql = "SELECT * FROM 'user' ORDER BY id DESC LIMIT 0,5";
	$return mysqli_query($db,$sql);
	// mysqli_fetch_assoc($return) //只能获取结果集中的一条数据
	if ($return) {
		while ($row = mysqli_fetch_assoc($return) ) {
			$rows[] = $row;
		}
	}
	var_dump($rows);

	// 封装方法
	function Select($db,$sql)
	{
		$return = mysqli_query($db,$sql);
		if ($return) {
			while ($row = mysqli_fetch_assoc($return) ) {
				$rows[] = $row;
			}
			mysqli_free_result($return);//释放资源
		}
		return $return;
	}
	
?>