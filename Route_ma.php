<?php 

// 1.导言：
// 路由解析类的主要功能：路由解析，请求分发;
// 实现原理：
// MVC路由机制：URL到控制器和操作的映射：User/add =>(new User) ->add()
// call_user_func_array(function, param_arr)系统调用用户回调函数
// $_SERVER['QUERY_STRING']	URL中的查询字符串

// 案例：
// 
// http://frame.com/demo1.php?m=admin&c=user&a=add 动态地址
// http://frame.com/demo1.php?admin/user/add
echo '<pre>',print_r($_GET,true);
echo $_SERVER['QUERY_STRING'],'<br>';
// echo $_SERVER['QUERY_URI'],'<br>';
// echo '<pre>',print_r($_SERVER,true);

/***
user/add/name/ma/age/18/sex/0
user：控制器，User.php
add：User.php中的一个add()方法
name/ma/age/18/sex/0 参数：name=ma,age=18,sex=0
***/
$user = new User();
$user ->add($name,$age,$sex);//这样写，比较麻烦，系统为我们提供了这么一个函数call_user_func_array

namespace demo;
	// call_user_func_array(function, param_arr) 执行一个用户回调函数
	public function hello($name = '小明同学')
	{
		return '<h2>Hello' . $name . '，别浪，猥琐发育！</h2>';
	}
	// 执行这个函数
	// 1 按函数名访问
	echo hello();
	echo hello('小李');
	// 2 用系统函数call_user_func_array()
	echo call_user_func_array('demo\hello', ['二狗']);
	echo call_user_func_array(__NAMESPACE__.'\hello', ['二狗']);

	// 3 如果将函数作为类中一个成员方法
	class Demo
	{
		public function hello($name = '小明同学')
		{
			return '<h2>Hello' . $name . '，别浪，猥琐发育！</h2>';
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
	案例总结：
	从URL地址中解析出：控制器类和对应的方法；$_SERVER['QUERY_STRING']
	再调用call_user_func_array(['控制器','操作'],['参数列表'])
	***/

// 2 以下是路由解析类 
// 文件位置：frame/ma/Route.php
/****
* 路由解析
* 请求分发
****/
namespace ma;

class Route
{
	
	// 路由的配置参数
	protected $route = [];

	// PATHINFO
	// ?m=moudle&c=controller&a=insert
	// /moudle/controller/insert
	protected $pathInfo = [];

	// URL参数
	// /moudle/controller/insert/name/ma/age/18
	// /name/ma/age/18 
	protected $params = [];

	// 2.1 构造函数，初始化
	public function __construct($route)
	{
		// 路由配置初始化
		// 从frame/ma/config.php 中的 route配置加载过来
		// $config = require 'config.php';
		// $route = new Route($config['route']);
		require __DIR__ .'/config.php';
		$this ->route = $route;
	}

	// 2.2 解析路由
	public function parse($queryStr='')
	{
		// 分析
		// /admin/user/add/name/ma/age/18 
		// $this ->pathInfo = ['module' =>'admin','controller' =>'user','action' =>'add']
		 
		// 参数数组，单独处理：$this ->params = ['name'=>'dima','age' =>18]
		// 2.2.1 将查询的字符串的前后的 / 去掉，再按分隔符 / 拆分
		$queryStr =trim(strtolower($queryStr,'/'));
		$queryArr = explode('/', $queryStr);
		$queryArr = array_filter($queryArr);//过滤空字符

		// 2.2.2 解析出 $queryArr 数组中的内容(模块，控制器，操作，参数)
		switch (count($queryArr)) {
			// 数组为空，说明：没有模块，控制器，操作，参数
			// PATHINFO 为路由配置初始化后的路由的配置参数
			case 0:
				$this ->pathInfo = $this ->route;
				break;
			// 只有一个参数：用户只提供了模块，控制器和操作为默认
			case 1:
				$this ->pathInfo['module'] = $queryArr[0];
				break;

			case 2:
				$this ->pathInfo['module'] = $queryArr[0];
				$this->pathInfo['controller'] = $queryArr[1];
				break;
			// 三个参数：控制器、模块，操作全部来自自定义，全部来自用户实际请求
			case 3:
				$this ->pathInfo['module'] = $queryArr[0];
				$this->pathInfo['controller'] = $queryArr[1];
				$this->pathInfo['action'] = $queryArr[2];

			// 进行参数处理
			default:
				$this->pathInfo['module'] = $queryArr[0];
				$this->pathInfo['controller'] = $queryArr[1];
				$this->pathInfo['action'] = $queryArr[2];
				// 从 从pathInfo的数组索引 3 开始，将剩余的元素全部作为参数进行处理
				$arr = array_slice($queryArr, 3);
				// 键值对必须是成对出现，所以每次递增为2
				for ($i=0; $i < count($arr); $i+=2) { 
					// 如果没有第二个参数，则放弃
					if (isset($arr[$i+1])) {
						$this ->params[$arr[$i]] = $arr[$i+1];
					}
				}
				break;
		}
		// 返回当前路由的实例对象，主要是方便链式调用：$route->parse()->print();
		// $route->parse();$route->print();上下两种表达方式是等价的
		return $this;
	}

	// 2.3 请求分发
	// 原理：将对应的模块、控制器、操作行程一个请求地址，找到对应的脚本来执行
	// 请求分发的本质：就是一个字符串的拼接过程
	
	public function dispatch()
	{
		// 生产的带有命名空间的控制器类名称：app\模块\控制器\控制器类
		// 类名称应该与类文件所在的绝对路径一一对应，这样才可以实现自动映射，方便自动加载
		
		// 模块名称
		$moudle = $this ->pathInfo['module'];
		// 控制器名称
		$controller = 'app\\' . $moudle . '\controller\\' .ucfirst($this ->pathInfo['controller']);
		// 操作
		$action = $this ->pathInfo['action'];

		if (!method_exists($controller,$action)) {
			$action = $this ->route['action'];
			header('location: /');
		}

		// 将用户请求，分发到指定的控制器和对应的操作方法上
		return call_user_func_array([new $controller,$action],$this->params);

	}
}

// 3.测试部分

// 3.1 测试2.2.1 
$queryStr = $_SERVER['QUERY_STRING'];
echo $queryStr;
// 访问时：在域名后面加 ?/admin/user/add/name/ma
// 即可显示：/admin/user/add/name/ma

// echo '<hr>';
echo '<pre>';
print_r(explode('/', $queryStr));
// 会打印出数组
// Array
// (
//     [0] => admin
//     [1] => user
//     [2] => add
//     [3] => name
//     [4] => ma
// )

// 3.2 测试路由解析
echo '<hr>';
//测试这个，类外访问，需要开启权限，将上面protected 改为 public

$config = require 'config.php';
$route = new Route($config['route']);

$route ->parse($queryStr);
print_r($route ->pathInfo);
print_r($route ->params);

// 3.3 测试请求分发
require __DIR__ .'/../app/admin/controller/Index.php';
// 此处测试，注意：Route.php?admin/index/index
// 因为我们只创建了index控制器的index方法
echo $route ->dispatch();


























 ?>