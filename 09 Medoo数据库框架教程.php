<?php 
/****
一，Medoo框架的配置
****/
// 数据库配置参数
	$config = [
		// 必填
		'database_type' =>'mysql',
		'database_name' =>'php_edu',
		'server' =>'127.0.0.1',
		'username' =>'root',
		'password' =>'root',
		// 可选
		'charset' =>'utf8',
		'port'=>3306,
	]

	require __DIR__ . '/vendor/autoload.php';
	use Medoo\Medool as Db;
	// 数据库配置
	$config =[
		// 必填
		'database_type' =>'mysql',
		'database_name' =>'php_edu',
		'server' =>'127.0.0.1',
		'username' =>'root',
		'password' =>'root',
	];
	// 实例化Medoo类，创建db对象
	$db = new Db($config);
	// var_dump($db);

	// 查询测试
	$rows = $db->select('user',['id','name'],['status' =>1]);
	// 遍历结果
	foreach ($rows as $row) {
		echo  print_r($row,true) . '<hr>';
	}


/****
二，Medoo实现数据库基本操作
****/
	// 2.1查询操作
	// select(表名，字段列表，查询条件)；
	// - 字段采用数组格式，单字段可使用字符串
	// - 查询条件必须采用数组格式
	// - 返回为数组格式
	// 查询年龄大于50的用户
	$table = 'user';
	$fields = ['id','name','sex'];
	$where = ['age[>]' =>50];
	$where = ['AND' =>['age[>]' =>50,'sex' =>1]];//联合查询

	$rows = $db->select($table,$fields,$where);
	foreach ($rows as $row) {
		echo  print_r($row,true) . '<hr>';
	}

	// 2.2 添加操作
	// insert(表名,要添加的数据);
	// 返回PDOstatment,预处理对象，可以用它执行更多的操作
	// Medoo中，只要是写操作(更新，添加，删除)，都是返回预处理对象
	// 单独获取新增记录的主键ID有单独的方法：$db ->id(),不需要参数

	$table ='user';
	$data['name'] = '张三';
	$data['sex'] = '女'；

	$stmt = $db->insert($table,$data);
	// var_dump($stmt);
	// 查看下生成的SQL语句
	echo 'SQL语句：' . $stmt ->queryString . '<hr>';
	// 查看下新增记录的主键
	echo '新增主键ID为'  . $db->id(), '<hr>';

	// 2.3 更新操作
	// update(表名，要更新的数据，条件)
	// 数据以数组方式提供
	// 返回PDO预处理对象

	$table ='user';
	$data['name'] = '张三';
	$data['sex'] = '男';
	$where['id'] = 1;
	$stmt = $db ->update($table,$data,$where);

	$num = $stmt ->rowCount();
	if ($num >0) {
		echo '成功更新了' .$sum .'条记录';
	}

	// 2.4 删除操作
	// delete(表名,条件)
	// 操作前，请再三确认；
	// 返回PDO预处理对象;

	$table ='user';
	$where['id'] = 1;
	$stmt = $db ->delete($table,$where);
	if ($stmt ->rowCount() >0) {
		echo '成功删除了' . $stmt ->rowCount() .'条记录';
	}

/****
三，Medoo实现原生PDO操作	
****/
	// get() 获取一条记录
	// has() 判断某值是否存在？
	// count() 统计满足条件的记录数量
	// query() 原生查询
	// 其他更多，请查询Medoo官网
	
	$stmt = $db->query($sql);
	$rows = $stmt->fetchAll();
	// 以上两行代码可以采用链式操作，框架中采用较多；即：
	// 前一步操作返回一个对象，后一个是前一个的一个操作方法。
	$rows = $db->query($sql)->fetchAll();
?>