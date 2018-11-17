<?php 
/****
一，MVC设计模式
****/
	call_user_func_array(function, param_arr)系统调用用户回调函数
	$_SERVER['QUERY_STRING']	URL中的查询字符串
	MVC路由机制：URL到控制器和操作的映射：User/add =>(new User) ->add()
	代码规范：
		类文件采用首字母大写的驼峰法命名：User.php,Index.php
		类中的属性和方法全部采用驼峰法，如：$userName,getConfig()
		公共函数采用下划线法命名：get_cate_name()
		目录全部采用小写字母或小写字母加下划线方式命名：app/,common_func/
	例：
	// http://frame.com/demo1.php?m=admin&c=user&a=add 动态地址
	// http://frame.com/demo1.php?admin/user/add
	echo '<pre>',print_r($_GET,true);
	echo $_SERVER['QUERY_STRING'],'<br>';
	// echo $_SERVER['QUERY_URI'],'<br>';

	// echo '<pre>',print_r($_SERVER,true);

	/***
	user/add/name/peter/age/30/sex/0
	user：控制器，User.php
	add：User.php中的一个add()方法
	name/peter/age/30/sex/0 参数：name=peter,age=30,sex=0
	***/
	$user = new User();
	$user ->add($name,$age,$sex);//这样写，比较麻烦，系统为我们提供了这么一个函数

	namespace demo;
	// call_user_func_array(function, param_arr) 执行一个用户回调函数
	public function hello($name = '小明同学')
	{
		return '<h2>Hello' . $name . '，请开始你的表演!</h2>';
	}
	// 如何执行这个函数？
	// 1 按函数名访问
	echo hello();
	echo hello('小李');

	// 2 用系统函数call_user_func_array()
	echo call_user_func_array('demo\hello', ['小君']);
	echo call_user_func_array(__NAMESPACE__.'\hello', ['小君']);

	// 3 如果将函数作为类中一个成员方法，能不能用call_user_func_array()
	/**
	* 
	*/
	class Demo
	{
		public function hello($name = '小明同学')
		{
			return '<h2>Hello' . $name . '，你又调皮了!</h2>';
		}
		public static function getSite($domain)
		{
			return '<h2>百度的域名是：' . $domain . '</h2>';
		}
		}
	}
	// 3.1 调用类中普通方法
	$demo = new Demo();
	// 第一个参数必须使用数组格式：['类或对象','方法名称']
	echo call_user_func_array(['$demo','hello'], ['二狗']);

	// 简化一下，PHP7.0支持，不实例化，直接在里面实例化
	echo call_user_func_array([(new Demo()),'hello'], ['二狗']);

	// 3.2 调用类中静态方法
	echo call_user_func_array([__NAMESPACE__ . '\Demo','getSite'], ['www.baidu.com']);

	/***
	总结：
	从URL地址中解析出：控制器类和对应的方法；$_SERVER['QUERY_STRING']
	再调用call_user_func_array(['控制器','操作'],['参数列表'])
	***/
/***
二，配置/路由/基类与第三方类库
***/
	// 1 框架的配置
		文件位置：ma/config.php
		主要有应用配置与数据库配置二部分组成；
		以PHP数组方式返回
		配置文件主要是提供给 路由类 和 基础类 使用；
		/***
		文件名：config.php
		配置文件：适用于整个应用
		采用数组方式返回数据
		***/
		return [
			// 应用配置
			'app' =>[
				// 调试开关
				'debug' =>'true',
			],

			// 路由配置
			'route' =>[
				// 默认模块
				'module' =>'admin',
				// 默认控制器
				'controller' =>'Index',
				// 默认操作
				'action' =>'Index',

			],
		]

	// 2 composer自动安装第三方依赖
		依赖管理工具：composer
		安装数据库操作框架：Medoo
		安装模板引擎：Plates


		return [
			// 应用配置
			'app' =>[
				// 调试开关
				'debug' =>'true',
			],

			// 路由配置
			'route' =>[
				// 默认模块
				'module' =>'admin',
				// 默认控制器
				'controller' =>'Index',
				// 默认操作
				'action' =>'Index',

			],
			// 数据库配置
			'db' =>[
				// 数据类型
				'database_type' =>'mysql',
				// 默认的数据库名称
				'database_name' =>'frame',
				// 默认主机名
				'server' =>'127.0.0.1',
				// 默认用户名
				'username' =>'root',
				// 用户密码
				'password' =>'root',
				// 默认客户端的字符码集
				'charset' =>'utf8',
				// 默认的服务器端口号
				'port' =>'3306',

			],
		]
	// 3 测试数据库框架与模板引擎(略，详见test.php/test2.php)
	// 4 路由解析原理及实现
		// 4.1 创建路由解析类
		1 文件：ma/Route.php;(详见该文件)
		2 功能：路由解析，请求分发;


/***
三，入口文件/模型/视图/控制器
***/
	// 1 创建基础类与控制文件
	// 基础类是框架运行的保障，主要用调试设置，类的自动加载和启动功能，几乎所有PHP框架都有一个基础类，
	// 可能有的框架叫做引导类，实质都是一样的
	1.1 调试模式
		开发模式，开启，抛出错误；上线后，关闭调试模式
	1.2 自动加载
		将自定义的类加载函数把它注册到系统中去，这样，我们在New一个类的时候，这个类就自动加载到当前脚本中。
	1.3 启动框架
		一般是设置一个run或者start方法，所谓启动，实际上就是一个请求路由分发的过程。

	// 2 创建框架模型基类
	做项目，一定要从模型开始做。
	2.1 ma/core/Model.php
	2.2 连接数据库[构造方法实现]
	2.3 设置一些受保护属性或方法，供子类继承[可选]

	// 2 创建框架视图基类
	ma/core/View.php
	目的：将第三方的模板引擎导入到我们的项目中
	2.1 主要是初始化模板引擎：Plates
	2.2 配置一些默认模板目录

	// 3 创建框架控制器基类
	功能：主要功能，实例化模板引擎对象，创建通用方法，供子类调用
	3.1 最重要的是对视图对象的创建
	3.2 如果有其他公共操作，也可以放这里
	ma/core/Controller.php

	// 4 创建应用项目
	4.1 约定都创建在app目录下面
	4.2 app下一模块进行划分，例如admin为后台管理模块，home为前提管理模块
	4.3 模块admin下创建目录controller，用来存放用户自定义的控制器类文件
	4.4 模块的视图也放在admin目录下，创建admin/view目录
	4.5 view目录下，根据不同的控制器再创建目录进行分类管理：index/edit.php
	4.6 根据模板的要求，全部模板文件默认后缀都是php，也可以修改为其他，如:tpl/html
	4.7 模型对应数据库操作，通常一个应用对应着一个数据库，表基本都是共用的
	4.8 所以不针对模块来创建模型，而是在app应用下创建model，为公共模型





?>