<?php 
/****
一，类的声明与实例化
****/
	class Star
	{
		public name = '小龙女';//属性初始化必须使用字面量或者构造方法
		public sex = '女';
		public age; //默认为null,抽象属性

		public function xxx()
		{
			// $this 是一个伪变量，始终指向当前类的实例对象
			// $this 只能用在类的内部，引用类的成员
			$name = $this->name;
			$name = $this->$sex;
			return $name . '是一个'.$sex;
		}
	}

	$obj = new Star();
	echo $obj ->xxx();

/****
二，类属性与类常量
****/
	类属性：只能用字面量或构造方法来初始化，不能使用表达式
	类常量：
		类常量必须要用 const 关键字声明
		类常量声明时必须初始化
		类常量推荐全部采用大写字母
		类常量可以被所有类实例共享
		类常量必须使用类名来访问
			类内：self/static ::CONTST
			类外：类名::CONTST 
/****
三，类的自动加载
****/
	__autoload()：已经淘汰
	spl_autoload_register() 注册自动加载器
	s: standard 标准
	p:php
	l: Library 库
	即：标准PHP库函数
	// 自动加载器：最重要的一个参数就是一个回调
	spl_autoload_register(function($className){
		// 推荐使用绝对路劲
		require __DIR__.'\public\\'.$className.'.php';
		// require 与include 功能一样，但是出错时的处理机制不同；
		// include 没有导入成功，只给出警告，脚本仍然执行
		// require 直接退出 require = include+die/exit;
	});
/****
四，类的访问限制
****/
	public protected private 

/****
五，类的继承 extends
****/
	类的继承，终极目标：代码复用；
	php只支持单继承；
	继承必须发生在2个或2个以上的类；
	父类也叫超类，基类，子类也叫派生类
	类的继承下：
	public protected 可以在子类中访问
	访问限制符 是受到类作用域限制的
	类常量，不受限制，可以在子类中直接使用；

/****
六，范围解析符的使用    ::
****/
	使用场景：
		- 访问类中的静态成员
		- 访问类中的常量
	使用主体：
		- 在类中使用关键字 self parent static
		- 在类外可直接使用类名 className

/****
七，static 关键字
****/
	// 定义与访问类中的静态成员
	// 访问类常量
	// 后期静态绑定(延迟静态绑定)
	class MyClass
	{
		const DOMAIN = 'www.baidu.com';
		// static 定义静态属性：被类中所有实例共享
		public static $dec = '百度一下';

		// 静态方法
		public static function getDec()
		{
			// 类常量
			$domain = self::DOMAIN;
			$domain = static::DOMAIN;

			// 静态属性
			$desc = self::$desc;
			$desc = static::$desc;

			return '('.$domain.')'.$desc;
		}
	}

	// 外部访问类中静态属性
	echo MyClass::$desc,'<br>';
	echo MyClass::getDec();

/****
八，后期静态绑定(延迟静态绑定)
****/
	使用在静态继承的上下文环境中；
	动态匹配静态成员的调用者，而不是声明者；
	静态方法与调用者的绑定在运行阶段才可以确定；
	例：
	class Father
	{
		// 静态属性
		public static $money = 5000;
		// 静态方法
		public static function getClass()
		{
			// 返回当前类名
			return __CLASS__;
		}
		public static function getMoney()
		{
			// return self::getClass(). '=' . self::$money;
			// static 用在静态继承的上下文中，动态设置静态成员的调用者(主体)
			return static::getClass(). '=' . static::$money;
		}
	}
	// 定义子类，继承自Father
	class Son extends Father
	{
		// 覆写父类的静态属性
		public static $money = 30000;
		// 覆写父类中的方法
		public static function getClass()
		{
			return __CLASS__;
		}
	}
	// 调用Father中的静态方法
	echo Father::getClass(),'<br>';
	echo Father::getMoney(),'<br>';
	//调用子类Son类中的静态成员
	echo Son::$money,'<br>';
	echo Son::getClass(),'<br>';
	echo '<hr>';
	echo Son::getMoney(),'<br>';//此处，如果使用注释的 self::....,则返回父类的东西，即Father=5000,
	// 而我本意是想要返回子类中的信息 即将self 改为 static;结果为：Son=30000;

/****
九，类中属性和方法的重载技术
****/
	重载：动态的创建类属性和方法；
	重载在 php语言 中的含义：是指，当对一个对象或类使用其未定义的属性或方法的时候，其中的一些"处理机制”
	"；
	php中的重载技术，就是来应对上述"出错"的情况，使代码不出错，而且还能"优雅处理"；
	实现方式：通过魔术方法实现
	属性重载：就是对一个对象的不存在的属性进行使用的时候，这个类中预先设定好的应对办法（处理机制）；
		- __set($name,$value)
		- __get($name)
		- __isset($name)
		- __unset($name)
	// 方法重载 当对一个对象的不存在的实例方法进行"调用"的时候，会触发以下函数
		- __call($method,array,$args) 用户调用一个不存在的方法，会自动触发
		- __callStatic($method,array,$args) 用户调用一个不存在获取无权访问的静态方法

/****
十，Trait 特性
****/
	- Trait ：代码复用机制
	- Trait可以给当前类添加一些新的特征(功能)
	-  如果一个类中要添加的新功能，不具备形成一个类，或不方便使用类进行整理，使用Trait更方便；
	- Trait 类似于类中的插件，可以在不修改父类的情况下，矿长当前类的功能
	- Trait 工作在父类(如果有)与当前类之间，可以重载父类同名成员
	- 尽管类中的语法适用于声明 Trait 类，但是Trait 并非常规类，不允许直接实例化
	- Trait 使用use关键字将自身代码插入到当前的宿主类中
	- 当Trait 类中的成员与用户类中的成员命名冲突时，可以通过替换或别名解决；
	例：
	<?php
		// Trait 代码复用
		// Trait 工作在继承的上下文环境中，它是位于父类与子类之间的
		// Trait 的优先级是：高于父类，但是低于子类的
		trait Func1
		{
			public function drive()
			{
				return '支持无人驾驶';
			}

			// 保养
			public function care()
			{
				return '保养很简单，及时充电就可以了...';
			}

			// 事故处理
			public function accident()
			{
				return '坐等交警';
			}
		}

		trait Func2
		{
			public function fuel()
			{
				return '新能源汽车';
			}
			// 事故处理
			public function accident()
			{
				return '自求多福';
			}
		}

		/**
		* 父类Auto
		*/
		class Auto
		{
			public $brand;
			public $purpose;
			
			function __construct($brand,$purpose)
			{
				$this ->brand = $brand;
				$this ->purpose = $purpose;
			}
			// 保养
			public function care()
			{
				return '请到专业4S店保养';
			}
		}

		/**
		* 定义一个子类 工作类 Bus
		*/
		class Bus extends Auto
		{
			// 子类Bus除了可以继承Auto中的成员，还可以导入trait类中的方法，使用use 导入；
			// use Func1;
			// use Func2;

			use Func1,Func2{
				Func1::accident insteadof Func2;//不科学，Func 2中的方法将永远不被执行到
				Func2::accident as Fun2Acc; //取别名字
			}
			// 定义个care方法，这个方法是 Auto 中存在的
			public function care()
			{
				return '我们是生产商，保养请致电当地经销商';
			}
		}

		// 实例化Bus
		$bus = new Bus('安凯','客运');
		// 访问
		echo $bus->brand,'<br>';
		echo $bus->purpose,'<br>';

		// 访问trait类中的方法
		echo $bus->drive,'<br>';
		echo $bus->fuel,'<br>';

		// 访问care()
		echo $bus->care(),'<br>';// ！！！此处，注释不同位置的care方法，结果各不相同

		// 访问 trait中的accident()
		echo $bus->accident(),'<br>';// ！！！此处,命名冲突
		//解决方法：一，替换，二 别名
		echo $bus->Fun2Acc(),'<br>';

/****
十一，命名空间的基本知识
****/
	定义命名空间：namespace 空间名称;
	命名空间主体(适用对象)：类/接口/，函数，常量
		- 为什么命名空间的适用对象是：类/接口/，函数，常量？？？
		- 因为这三个的作用域是全局，不允许重名，很容易命名冲突
	命名空间魔术常量：__NAMESPACE__;
	引用命名空间：namespace /空间;(类似于self)

	PHP 命名空间可以解决以下两类问题：
		- 用户编写的代码与PHP内部的类/函数/常量或第三方类/函数/常量之间的名字冲突。
		- 为很长的标识符名称(通常是为了缓解第一类问题而定义的)创建一个别名（或简短）的名称，提高源代码的可读性。
		<?php
			// 命名空间必须是程序脚本的第一条语句
			// 在声明命名空间之前唯一合法的代码是用于定义源文件编码方式的 declare 语句。
			// 所有非 PHP 代码包括空白符都不能出现在命名空间的声明之前。
			declare(encoding='UTF-8'); //定义多个命名空间和不包含在命名空间中的代码
			namespace MyProject1;  
			// MyProject1 命名空间中的PHP代码  
			 
			namespace MyProject2;  
			// MyProject2 命名空间中的PHP代码    
			 
			// 另一种语法
			namespace MyProject3 {  
			 // MyProject3 命名空间中的PHP代码    
			}  
		?>

/****
十二，在一个脚本中声明多个命名空间
****/
	在当前命名空间，访问其他命名空间，用完全限定名称即可；
	在一个脚本中可以声明多个命名空间，并与全局命名空间并存；
	使用大括号语法：namespace 空间名称{空间代码}
	全局空间的名称为空：namespace{全局空间代码}
	例：
	<?php
	declare(encoding='UTF-8'); //定义多个命名空间和不包含在命名空间中的代码
	// 声明MyProject1 命名空间
	namespace MyProject1 {// MyProject1 命名空间
	const CONNECT_OK = 1;
	class Connection { /* ... */ }
	function connect() { /* ... */  }
	}
	// 声明MyProject2 命名空间
	namespace MyProject2 {// MyProject2 命名空间
	const CONNECT = 1;
	class Connection { /* ... */ }
	function connect() { /* ... */  }
	}
	// 声明一个全局空间
	namespace { // 全局命名空间代码
	session_start();
	$a = MyProject\connect();
	echo MyProject\Connection::start();
	}
	?>

	PHP 命名空间中的类名可以通过三种方式引用：
	非限定名称，或不包含前缀的类名称，例如 $a=new foo(); 或 foo::staticmethod();。如果当前命名空间是 currentnamespace，foo 将被解析为 currentnamespace\foo。如果使用 foo 的代码是全局的，不包含在任何命名空间中的代码，则 foo 会被解析为foo。 警告：如果命名空间中的函数或常量未定义，则该非限定的函数名称或常量名称会被解析为全局函数名称或常量名称。
	限定名称,或包含前缀的名称，例如 $a = new subnamespace\foo(); 或 subnamespace\foo::staticmethod();。如果当前的命名空间是 currentnamespace，则 foo 会被解析为 currentnamespace\subnamespace\foo。如果使用 foo 的代码是全局的，不包含在任何命名空间中的代码，foo 会被解析为subnamespace\foo。
	完全限定名称，或包含了全局前缀操作符的名称，例如， $a = new \currentnamespace\foo(); 或 \currentnamespace\foo::staticmethod();。在这种情况下，foo 总是被解析为代码中的文字名(literal name)currentnamespace\foo。
	总结：命名空间和文件系统的组织结构非常相似，
	非限定名称，类似于当前路径
	限定名称，类似于相对路径
	完全限定名称，类似于绝对路径

/****
十三，如何导入外部命名空间
****/
	非限定名称：适用于当前空间成员，类似于当前路径
	限定名称：与当前空间关联的空间，类似于相对路径
	完全限定名称：导入外部空间，类似于绝对路径
	导入空间使用关键字：use
	如果与当前命名冲突，可以通过设置替换规则或别名解决；
	导入空间名称并不会自动加载类文件，需要手工加载或自定义加载机制；
	例：
	<?php
		namespace foo;
		use My\Full\Classname as Another;//导入空间别名，use默认就是从全局开始，不用在其后加 \
		// 下面的例子与 use My\Full\NSname as NSname 相同
		use My\Full\NSname;
		// 导入一个全局类
		use \ArrayObject;
		$obj = new namespace\Another; // 实例化 foo\Another 对象
		$obj = new Another; // 实例化 My\Full\Classname　对象
		NSname\subns\func(); // 调用函数 My\Full\NSname\subns\func
		$a = new ArrayObject(array(1)); // 实例化 ArrayObject 对象
		// 如果不使用 "use \ArrayObject" ，则实例化一个 foo\ArrayObject 对象
	?>


?>