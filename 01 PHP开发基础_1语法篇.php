<?php 
/****
一，如何在函数中访问全局变量？
****/
// 1.关键字：global

// 2.预定义变量：$GLOBALS

// 3.参数传参数：function($args){}

// 创建一个全局变量
	$thief =  '小偷' ;
	function catching()
	{
		// 1.global
		global $thief;

		// 2 声明全局变量时，系统会自动将一个全局变量注册到系统的
		// 预定义变量 $GLOBALS 中，$GLOBALS是一个多维数组，
		$thief = $GLOBALS['thief'];

		return isset($thief) ? '抓住了'.$thief  : '没抓住';
	}
	echo catching();

	echo  '<hr>';

// 3.参数传参数：function($args){}
	function catch($thief)
	{
		return isset($thief) ? '抓住了'.$thief  : '没抓住';
	}
	echo catch($thief);

/****
二，PHP的变量类型有哪些？共分为3大类，8种
****/
// 1 标准类型：整数(inter) ,浮点数(float),布尔(bool),字符串(string)
	// 也叫单值类型，一个变量名对应一个值
// 2 复合类型：数组(array)   对象(object)
// 3 特殊类型：NULL(null)  资源(resource)-文件资源，图片资源等等
  $a;
  $a = NULL;//这两个等价，如果只声明变量，不赋值，系统会自动分配一个NULL；

/****
三，作用域
****/
// 1 全局作用域：从脚本开始，到脚本结束都有效；
// 2 函数作用域：仅仅在函数内有效，也称为 局部作用域；
// 3 不受作用域影响的变量： 系统预定义变量(超全局变量)，常量

/****
四，常量
****/
// 1 本质：只读变量，一旦定义，不能更新，不能删除；
// 2 定义：函数定义(define)   和 关键字定义(const)；
// 3 作用域：不受作用域影响；
// 4 规则：行业内一般用全大写，务必遵守；
	define('NAME', 'mathilde');
	const USER_NAME = 'lisi';
	echo NAME;
// 5 define() 和 const 的区别？
	// define 可以使用表达式，可定义是否区分大小写；
	// const 声明的常量，值只允许是标准变量，即单值变量；整数，布尔，字符串，浮点，必须是字面量
	// const 可以声明类常量；

/****
四，函数
****/
// 4.1 函数分类
	// 01 普通函数：也叫标准函数，使用function 在全局中声明
	// 02 匿名函数：将函数定义以值的方式赋给一个常量，常用作 回调函数 或 闭包；
		// 匿名：并非无名，而指名称可以任意指定，非常适合用变量来引用
		// 匿名函数的本质就是一个值，只不过这个值里面保存的是一个函数的定义罢了
	// 03 自调用函数：定义和执行同步完成；

	// 匿名函数：并非无名，而指名称可以任意指定，非常适合用变量来引用
		$mult = function ($m,$n)
		{
			return "$m * $n = " . ($m*$n); 
		}；//此处分号，千万别省

		// 用变量方式来调用匿名函数,
		echo $mult(15,5);

		// 自调用函数：它也不需要函数名称，也应该算是匿名函数的一种变种
		echo (function ($m,$n){
			return "$m - $n = " . ($m-$n); 
		})(100,30);

// 4.2 回调函数
	// 回调函数：简单说来，就是在一个函数中调用另一个函数；
		// 通俗的解释就是把函数作为参数传入进另一个函数中使用；
		// 回调函数的使用就是传入的参数是你想要回调的函数名称
	支持的类型：普通类型，匿名函数
	执行回调函数：call_user_func() / call_user_func_array()
	例：
	// 普通函数
	function bigger($a,$b)
	{
		return $a . '和' . $b .'中较大的是：' . (($a>$b) ? $a : $b);
	}
	// main()
	function main($biggner)
	{
		// 这叫回调，函数注入
		return $biggner(100,80);
	}
	// 调用
	echo main('bigger'); //回调函数的使用就是传入的参数是你想要回调的函数名称

	// 匿名函数完成回调

	$bigger = function($a,$b)
	{
		return $a . '和' . $b .'中较大的是：' . (($a>$b) ? $a : $b);
	};
	function main1($bigger)
	{
		return $bigger(40,50);
	}
	main1($bigger);

 
	/*
		*PHP内置了两个双胞胎函数，用于执行回调
		* call_user_func(), call_user_func_array()	
	*/
	echo call_user_func('bigger',200,500);
	echo call_user_func($bigger,50,300);//回调匿名函数

	echo call_user_func_array($bigger, [30,27]);

	// 4.3 匿名函数与闭包
	// 1 在函数中调用一个匿名函数时；
	// 2 当匿名函数当作参数传递给函数时
	// 3 当匿名函数当做函数返回值的时候
	// 闭包的两个生效条件：一是 必须先定义一个匿名函数，二是 必须执行一次匿名函数；

	// 匿名函数产其实就是一个 普通 变量， 局部变量  ，函数的参数 ，函数的返回值
	// 1 匿名函数当做局部变量使用（闭包）
		$func1 = function ()
		{
			$name = '张';
			$test = function () use ($name)
			{
				return $name . '，我被另外一个函数包住了';
			};
			// 调用，必须要执行一下，才能形成闭包
			return $test();
		};

	// 2 将匿名函数当做函数参数使用
		$name = '张';
		$test = function () use ($name)
		{
			return $name . '，我被当做变量了';
		};
		$func2 = function (callable $test)
		{
			return $test();
		};
		echo $func2($test);

	// 3 将匿名函数当做函数返回值
		$func3 = function ()
		{
			$name = '张';
			$test = function () use ($name)
			{
				return $name . '，我被当作函数返回值了';
			};
			// 调用，当作函数返回值，返回的是一个匿名函数的声明；
			return $test;
		};

		// echo $func3();  不会执行的；返回的是一个匿名函数的定义 : $test
		echo $func3()();



?>