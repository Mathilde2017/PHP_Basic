<?php 
/****
一，常用的SQL语句
****/
	常用的增删改查语句(CURD);Create Update Read Delete
	添加：insert into 表名(字段列表) values (值列表)
	查询：select 字段列表(*) from 表名 where 查询条件 order by 排序条件 limit 数量
	更新：update 表名 set 字段=值,...where 更新条件；
	删除：delete from 表名 where 删除条件；

/****
二，PDO简介与数据库连接
****/
	PDO 是PHP数据对象（PHP Data Object）的缩写;
	PDO 操作的3点约定
		- 全部采用预处理方式操作数据表
		- SQL语句全部采用流行的命名占位符，不再使用传统的问号(?)
		- 涉及的类主要是PDOStatement类，PDO只涉及prepare()方法

	// PDO连接数据库
		- 操作数据库三步曲：连接、操作、关闭；
		- PDO类：new PDO(数据源$dsn,用户名$user,密码$pass);
			数据源$dsn：数据库类型:host=主机名;dbname=数据库名;charset=编码;
		例：
			<?php
				//$dsn: mysql:host=>127.0.0.1;dbname=userDB;charset=utf8;
				$type = 'mysql';
				$host = '127.0.0.1';
				$dbname = 'userDB';
				$charset = 'utf8';

				$dsn = $type.':host='.$host.';dbname='.$dbname.';charset='.$charset;
				$user ='root';
				$pass = 'root';

				try {
					//连接
					$pdo = new PDO($dsn,$user,$pass);
					// 操作 CURD
					// 关闭:并非必须，因为脚本结束会自动关闭，不过还是推荐显示的关闭它
					$pdo =null;
					unset($pdo);
				}catch (PDOException $e){
					exit($e->getMessage());
				}

			?>

/****
三，PDO预处理查询与参数绑定
****/
	PDO 查询操作(一)
		- 1 数据连接，创建PDO对象
			$pdo = new ($dsn,$user,$pass);
		- 2 执行预处理方法，创建预处理对象
			$stmt = $pdo->prepare($sql);
		- 3 执行查询
			$stmt ->execute();
		- 4 解析结果集
			$stmt ->fetchAll();
		- 5 遍历结果集
			通常用 foreach() 结构
		例：
		<?php
			- 1 数据连接，创建PDO对象
			$pdo = new ($dsn,$user,$pass);
			// - 2 执行预处理方法，创建预处理对象
				// :user_id 命名占位符
				$sql = "SELECT 'user_id','name','eamil' FROM 'user'  WHERE 'user_id' >:user_id";
				$stmt = $pdo->prepare($sql);
				$stmt = setFetchModel(PDO::FETCH_ASSOC);
			// - 3 执行SQL语句，执行查询
				if ($stmt ->execute([':user_id' =>2])) {
					// - 4 解析结果集
					$rows = $stmt ->fetchAll();
				}else{
					print_r($stmt ->errorInfo());
					die;
				}
			// - 5 遍历结果集:通常用 foreach() 结构
				foreach ($rows as $row) {
					echo print_r($row,true),'<hr>';
				}
				echo '共有'.count($rows).'记录满足要求'.'<br>';
		?>

		PDO 查询操作(二) 
		<!-- PDO预处理之参数绑定与列绑定 -->
		操作步骤与之前的查询案例是相同的；
		PDO查询中，2个绑定操作：参数绑定与列绑定；
		参数绑定：bindParm() 和 bindValue();
			bindParm(':占位符',变量,类型常量) 类型常量默认为字符串
			bindValue(':占位符',值或变量,类型常量) 如果直接传值，可省略类型常量
			execute([':占位符'=>值/变量]) ：将参数以数组方式与SQL语句的占位符绑定
		列绑定：
		bindColumn('列名或索引',变量,变量类型,最大长度)，如果是字符串类型，应该指出最大长度进行预分配
		fetch() 与 while 解析遍历结果集
		MySQL对游标查询支持不够完善，如果想在结果集中巡航，请把结果集解析到数组中进行
		
		例：
		<?php
			// - 1 数据连接，创建PDO对象
			$pdo = new ('mysql:host=127.0.0.1;dbname=php_edu;','root','root');
			// - 2 执行预处理方法，创建预处理对象 STMT
				// :status 命名占位符
				$sql = "SELECT 'user_id','name','eamil' FROM 'user'  WHERE 'status' =:status";
				$stmt = $pdo->prepare($sql);
			// - 3 执行
				// 参数绑定
				$status = 1;
				// $stmt->bindParam(':status',$status,PDO::PARAM_INT);
				// bindParm() 和 bindValue()基本等价，区别在于第二个参数。
				// bindParm() 第二个参数只能传变量；
				// bindValue() 第二个参数 既能传变量也能传值，其余再无区别；
				$stmt->bindValue(':status',$status,PDO::PARAM_INT);
				$stmt->execute();
			// - 4 遍历结果集:通常用 foreach() 结构
				$stmt ->bindColumn(1,$user_id,PDO::PARAM_INT);
				$stmt ->bindColumn(2,$name,PDO::PARAM_STR,20);
				$stmt ->bindColumn(3,$eamil,PDO::PARAM_STR,100);


				$rows =[];
				while ($row=$stmt->fetch(PDO::FETCH_BOUND)) {
					echo $user_id,$eamil,$name,'<br>';
					// 将变量转为数组;compact传值：传变量名组成的字符串
					$rows [] = compact('user_id','name','eamil');

				}
			// - 5 释放结果集
				$stmt = null;
			// - 6 关闭连接
				$pdo = null;

		?>

/****
四，PDO预处理新增/更新/删除操作
****/
	<!-- PDO预处理之新增操作 -->
	- rowCound()方法：返回受影响的记录数量；
	- errorInfo()方法：返回出错信息(数组格式)
	例：
	<?php
		// - 1 数据连接，创建PDO对象
			$pdo = new ('mysql:host=127.0.0.1;dbname=php_edu;','root','root');
		// -2 创建SQL语句
			$sql = "INSERT INTO 'user' ('name','email','status') VALUES (:name,:email,:status) ";
		// -3 创建预处理对象
			$stmt = $pdo->prepare($sql);
		// -4 参数绑定
			$name = '东方不败';
			$eamil = '123@qq.com';
			$status = 1;

			$stmt->bindParam(':name',$name,PDO_PARAM_STR,20);
			$stmt->bindParam(':email',$email,PDO_PARAM_STR,20);
			$stmt->bindParam(':status',$status,PDO_PARAM_INT);
		// -5 执行添加
			if ($stmt->execute()) {
				($stmt->rowCound()>0) ? '成功添加了'.$stmt->rowCound().'条记录' :'没有记录被添加';
			}else{
				exit(print_r($stmt->errorInfo(),true));

			}
	?>

	<!-- PDO预处理之更新操作 -->
	- rowCound()方法：返回受影响的记录数量；
	- errorInfo()方法：返回出错信息(数组格式)
	跟增加操作基本一致，唯一不同点在于：更新操作时要基于条件的！！！
	例:
	<?php
		// - 1 数据连接，创建PDO对象
			$pdo = new ('mysql:host=127.0.0.1;dbname=php_edu;','root','root');
		// -2 创建SQL语句
			$sql = "UPDATE  'user'  SET 'email' = :email,'status' = :status WHERE 'user_id' = :user_id ";
		// -3 创建预处理对象
			$stmt = $pdo->prepare($sql);
		// -4 参数绑定
			$user_id = 2;
			$eamil = 'update@qq.com';
			$status = 0;

			$stmt->bindParam(':user_id',$user_id,PDO_PARAM_INT);
			$stmt->bindParam(':email',$email,PDO_PARAM_STR,20);
			$stmt->bindParam(':status',$status,PDO_PARAM_INT);
		// -5 执行添加
			if ($stmt->execute()) {
				($stmt->rowCound()>0) ? '成功更新了'.$stmt->rowCound().'条记录' :'没有记录更新';
			}else{
				exit(print_r($stmt->errorInfo(),true));

			}
	?>

	<!-- PDO预处理之删除操作 -->
	删除操作是 最危险的写操作；
	在实际开发中，我们都是使用软删除实现；
	软删除就是利用更新模拟删除操作，通过添加删除标记字段来解决；
	删除操作，必须基于条件，绝对禁止无条件删除；
	如果想清空表中数据，请使用:TRUNCATETABLE命令
	- rowCound()方法：返回受影响的记录数量；
	- errorInfo()方法：返回出错信息(数组格式)
	跟增加操作基本一致，唯一不同点在于：删除操作时要基于条件的！！！
	例:
	<?php
		// - 1 数据连接，创建PDO对象
			$pdo = new ('mysql:host=127.0.0.1;dbname=php_edu;','root','root');
		// -2 创建SQL语句
			$sql = "DELETE FROM 'user' WHERE 'user_id' = :user_id ";
		// -3 创建预处理对象
			$stmt = $pdo->prepare($sql);
		// -4 参数绑定
			$user_id = 2;
			$stmt->bindParam(':user_id',$user_id,PDO_PARAM_INT);

		// -5 执行添加
			if ($stmt->execute()) {
				($stmt->rowCound()>0) ? '成功删除了'.$stmt->rowCound().'条记录' :'没有记录删除';
			}else{
				exit(print_r($stmt->errorInfo(),true));

			}
	?>


?>