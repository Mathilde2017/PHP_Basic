<?php 
/****
一，字符串输出函数汇总
****/
	echo ：语言结构，输出 一个 或 多个 字符串，字面量或单值变量；
	print：功能与echo相同，输出一个或多个标量，但会有一个返回值，成功返回1
	print_r($var,$bool) 可以输出一个标量，数组，对象等任何类型，可以视为print的升级版
	var_dump($var1,$var2...) 可以详细的一个或多个任何类型的变量，可以视为echo的升级版
	var_export($var,$bool) 以纯字符串形式输出任何类型的数据，可用于Php语句，适合于结果拼装
	printf("格式字符串",数据)  输出格式化的字符串，变量使用占位符，主要用于标量数据

	// 例
	printf("来%找%合作建站",'Dima网站','Dima');

/****
二，字符串的过滤与填充
****/
	trim(str) , ltrim(str),rtrim(str) ：过滤掉字符串中指定的字符串
	str_pad($str1,$size,$str2,FLAG) 向指定方向，填充指定字符串到指定长度
	// 例1：(只举过滤左边的例，右边和两边一样的道理)
	$str1 = 'www.dima.com';
	echo ltrim($str1,'www.');//用途：过滤用户的非正常多余输入

	// 例2
	$str2 = 'dima';
	str_pad($str2, 5 ,'***','STR_PAD_LEFT');//默认是用 空格 往 右边 填充
	// STR_PAD_LEFT,STR_PAD_RIGHT,STR_PAD_BOTH
	// 填充的用处主要在于  数据加密
	// 密码一般用md5() 32位字符串，sha1() 40位字符串
	$code ='123456';
	$coded = str_pad($code, 40,'php',STR_PAD_BOTH);
	echo sha1($coded);

/****
三，字符串的大小写转换
****/
	strtolower(str)  将字符串转为小写
	strtoupper(str) 将字符串转为大写
	ucfirst(str1,str2...) 将字符串的首字母转为大写
	ucwords(str) 将字符串的每个单词的首字母转为大写

	// 应用1 将全部文件转为小写，实现跨平台(Linux下区分大小写的)
	$files = ['Model.php','View.php','Controller.php'];
	foreach ($files as $file) {
		$res[] = strtolower($file);
	}
	$files = $res;
	echo $files;

/****
四，对HTML标签的过滤与转换
****/
	nl2br(string) 在换行符\n 前插入HTML换行标签<br>
	htmlspecialchars(string) 将代码中的引号，&，标记标签<,>转为实体字符，不解析
	htmlspecialchars_decode(string)  htmlspecialchars(string)反操作
	htmlentities(string) 将所有的html标记全转为实体，包括了htmlspecialchars(string)中的标记
	html_entity_decode(string) htmlentities(string)的反操作
	strip_tags(str) 过滤掉所有的html或php标记，也可设置允许保留的标记，很实用

/****
五，最常用的字符串查询函数
****/
	字符串查询，主要是根据特征进行，要么根据目标字符串位置，要么根据目标字符串的值
	substr($str, $offset,$length) 根据位置查询，获取指定位置或区间内的字符串
	strstr($str1, $str2,$bool)  查询字符串首次出现的位置，返回字符串
	strpos($str1,$str2,$start) 查询字符串首次出现的位置，返回所在位置
/****
六，字符串的查找和替换
****/
	str_replace($str1, $str2, $str3) 将字符串中的部分内容，用目标字符串进行替换
	str_ireplace(search, replace, subject)  与上面这个函数功能相同，但是忽略大小写
	substr_replace($str1, $str2, $offset,$size)  功能与str_replace()类似，但指定了替换区间

/****
七，其他常用的字符串函数
****/
	urlencode($url) 对url地址字符串进行编码
	urldecode(str)   上面的函数，转码
	http_build_query(query_data) 生成url动态查询字符串
	json_encode(value) 将数据转为json格式
	json_decode(json) 将json格式的字符串解析还原为变量

	// 例
	// urlencode($url) 就是在特殊字符前面加 % ,防止服务器解析出现歧义
	$url = 'http://www.baidu.com';
	echo $url . '<br>';
	$url = urlencode($url);
	echo $url;

	// json相关函数
	// 两个约定 ：1 必须是utf-8编码 不能处理资源类型：resource
	// json_encode(value) 将数据转为json格式
	// json_decode(json) 将json格式的字符串解析还原为变量

	$main = '波波维奇';
	echo json_encode($main);

	// 数组格式
	$a = ['a'=>88,'b'=>85,'c'=>80];
	echo json_encode($a);

	//对象
	$obj = new stu();
	$obj ->name ='mathilde';
	$obj -age = 21;
	$obj ->grade=['math'=>100,'PE'=>98,'English'=>120];
	echo json_encode($obj);

	// json_decode(json) 默认返回的都是对象
	$json = '{"math":100,"PE":98,"English":120}';
	$res = json_decode($json);
	echo '英语成绩是：',$obj->English;

	// 以数组方式返回，再第二个参数 加 true
	$json = '{"math":100,"PE":98,"English":120}';
	$res = json_decode($json,true);
	echo '英语成绩是：',$obj['English'];

?>