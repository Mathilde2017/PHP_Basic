<?php 
/****
一，数组的遍历技巧
****/
$arr = ['中国','美国','加拿大','澳大利亚','俄罗斯'];
	// list() 将索引数组的值绑定到指定的变量名上
	list($key,$value) = each($arr);
	echo $key,'---',$value,'<hr>';//0---中国
	list($key,$value) = each($arr);
	echo $key,'---',$value,'<hr>';//1---美国

	// 用list() 和 each() 实现索引元素与变量的绑定
	while (list($k,$v) = each($arr)) {
		echo $k,'---',$v,'<br>';
	}

	// 用内部指针来进行遍历
	// current()     当前指针指向元素的值；
	// key()           返回的当前指针指向元素的键名：关联/索引
	// next()         下移指针
	// prev()         前移指针
	// end()          将指针移到最后的一个元素上
	// reset()        指针复位，将指针指向第一个元素
	例：
	reset($arr);
	echo  key($arr),'--->',current($arr),'<br>';
	next($arr);
	echo  key($arr),'--->',current($arr),'<br>';
	.........
	// 奇葩面试：用for() 遍历关联数组
	for ($i=0; $i < count($arr); $i++) { 
		echo key($arr),'----',current($arr),'<br/>';
		next($arr);
	}
	// while() 入口判断型，会造成第一个元素缺失；
	reset($arr);
	while (next($arr)) {
		echo key($arr),'----',current($arr),'<br/>';
	}
	// while() 出口判断型，可行！
	reset($arr);
	do {
		echo key($arr),'----',current($arr),'<br/>';
	} while ( next($arr));

/****
二，PHP中常用的键值操作函数
****/
	array_values($arr) 返回元素的值组成的新数组；
	array_column($arr,$col,$index) 返回多维数组中的一列；
	array_keys($arr,$val,$bool) 返回元素的键名组成的新数组；
	in_array($val,$arr,$bool)     判断元素中是否存在某个值
	array_search($val,$arr, $bool) 查找指定值并返回该值的键名；
	array_key_exists($key, $arr)  判断数组中是否存在指定的键名；
	array_flip($arr)  键值对调
	array_reverse($arr) 数组翻转，第一个变成最后一个，倒序排列
	print_r($arr,$bool) 格式化的输出变量，数组；
	var_dump($arr1,$arr2...) 输出一个或多个变量的详细信息
	var_export($arr,$bool)  输出变量的字符串表示，其实就是一个PHP语句

/****
三，数组与变量和字符串之间的转换
****/
	list($arr1,$arr2...) 将数组中的索引元素转为变量
		$my_array = array("Dog","Cat","Horse");
		list($a, $b, $c) = $my_array;
		echo "I have several animals, a $a, a $b and a $c.";
	extract($arr,$flag) 将数组中的关联元素转为变量
	compact($str1,$str2...) 将变量转为数组（与extract()功能相反）
	explode(delimiter, string) 将字符串转为数组
	implode(glue, pieces) 将数组转为字符串
/****
四，数组元素的删除更新与填充
****/
	1 数组的切割与填充
	array_slice(array, offset,length,bool) 从数组中返回指定元素
	array_splice(&$arr, offset,length)  从数组中删除或替换指定的元素
	array_chunk($arr, size,bool)   将大数组切割成若干个小数组,bool 为是否保留键名
	array_pad($arr, size, value)   将数组填充到指定的长度

/****
五，用数组实现堆栈与队列
****/
	数组其实就是一张线性表，堆栈是 后进先出 的线性表，而队列是 先进先出 的线性表
	array_push(array, value) 从数组尾部添加(入栈/入队)
	array_pop(array)    从数组尾部删除元素(出栈/出队)
	array_unshift(array, value) 从数组头部添加(入栈/入队)
	array_shift(array)    从数组头部删除元素(出栈/出队)

	array_pop(array) 和 array_unshift(array, value)  可以实现队列操作

/****
六，数组元素的回调处理
****/
	匿名函数 最重要的用途，就是作为 回调参数的值 来使用，所以数组的回调处理函数中会大量用到匿名函数，其实就是 闭包；
	array_filter($arr,$callback) 用回调过度数组元素
	array_walk(&$arr, $callback,$var) 遍历元素并回调处理
	例：
	$arr1 = [5,0,' ',20 ,null,88,false,'php'];
	// 1 不传入回调：array_filter($arr) 过滤掉数组中为false的元素
	$arr2 = array_filter($arr1);
	echo '新数组：<pre>',var_export($arr2,true),'<hr>';

	// 2 传入回调，会将数组中的每一个值传入到匿名函数中进行处理
	$arr3 = ['html','css','js'];
	$arr4 = array_filter($arr3,function ($value){
		// 如果值等于css，就把css过滤掉
		return ($value !=='css');
	});
	echo '新数组：<pre>',var_export($arr4,true),'<hr>';

	// 3 array_walk(&$arr, $callback,$var)
	$arr = ['name'=>'admin','email' => '123@123.com'];
	array_walk($arr, function($val,$key,$name){
		// 如果用户想要查看用户名为 admin，直接拒绝
		if ($val ==$name) {
			exit('无权查看管理员的信息');
		}else{
			echo $key,'：',$val,'<br/>';
		}
	},'admin');

/****
七，数组的排序操作
****/
	// 数组主要是由键名和值二部分组成，所以排序主体就是 键名和值；
	// 顺序只有 升序 和 降序 二种；
	// 根据值排序：
		忽略键名：sort() 升序，rsort()  降序      usort()  回调
			// 记忆tips: r reverse 翻转 的简写
		保留键名：asort() 升序，arsort() 降序   uasort()  回调
	// 根据键名排序：
		ksort() 升序 ， krsort() 降序  uksort(array, cmp_function) 回调

/****
八，其他常用数组函数
****/
	range($start, $end,$step)    生成指定范围与步长的数组
	array_unique(array)    删除数组中值重复的元素(键名不存在重复的)
	array_fill($arr,$value)  用来指定元素初始化的一个数组
	array_rand($arr,$size) 从数组中随机取出部分元素
	shuffle(&$arr)  将数组元素随机打乱显示，直接更新原数组(引用传参)，非常适合验证码
	array_merge($arr1,$arr2) 将多个数组进行合并，键名相同则覆盖

?> 