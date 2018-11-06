<?php 
/****
一，Smarty简介下载与安装
****/
	- 1 Smarty 模板引擎概述
	什么是模板引擎以及它的优点？
	指利用某种模板语言将页面制成模板，再依据业务逻辑将该模板语言翻译成业务数据，从而最终展示页面。其目的就是要把页面(HTML)与业务数据(PHP)实现彻底分离。
	模板引擎可以将我们php项目的前端和后端的开发分离。

	模板引擎的工作原理？
	前端编写页面时，使用预置标签将PHP代码插入到页面，最终执行时，再由模板引擎进行
	HTML与PHP混编。

	为什么使用Smarty模板引擎？
	流行，简单，支持缓存，遇到问题有大量的网上资料可供查阅

	- 2 Smarty模板引擎下载与导入(Composer)
		官网下载或Composer导入
		导入：
			官网下载：将libs/Smarty.class.php 类文件加载到脚本即可
			Composer：require 'vendor/autoload.php';//根据实际目录确定

		检验是否导入成功：
			require 'vendor/autoload.php';
			$res = new Smarty();
			var_dump($res);//若能返回一个Smarty对象，即为安装导入成功


/****
二，Smarty配置与模板变量详解
****/
	- 1 Smarty模板引擎的配置
	// 配置目录：必选
		$smarty->setTemplateDir(__DIR__ . '/../temp');	//模板目录
		$smarty->setCompileDir(__DIR__ . '/../temp_c');	//编译目录
		$smarty->setCacheDir(__DIR__ . '/../cache');		//缓存目录
		$smarty->setConfigDir(__DIR__ . '/../config');		//配置目录
	// 配置定界符：可选
		$smarty -> setLeftDelimiter('{');	//变量左定界符
		$smarty -> setRightDelimiter('{');	//变量右定界符
	// 配置缓存：可选
		$smarty ->setCaching(false);				//关闭缓存
		$smarty ->setCacheLifetime(60*60*24);	//缓存有效时间
	例：
	<?php
		// 此为config.php文件
		require __DIR__ . '/../libs/Smarty.class.php';
		$smarty = new Smarty();
		// 配置4个目录：必选
		$smarty ->setTemplateDir(__DIR__ . '/../temp');	//模板文件所在目录
		$smarty->setCompileDir(__DIR__ . '/../temp_c');	//编译文件所在目录
		$smarty->setCacheDir(__DIR__ . '/../cache');		//缓存文件所在目录
		$smarty->setConfigDir(__DIR__ . '/../config');		//配置文件所在目录
		// 以上必选的配置完毕，其余可选暂略
		// 基本目录结构如下
		// smarty
		//  	cache
		//  	config
		//  		config.php文件
		//  	temp
		//  	temp_c
		//	libs
		//  	......
		//  	......
	?>

	- 2 Smarty模板变量
		$smarty->assign('模板变量名',变量);	//模板赋值
		$smarty ->display('模板文件');//模板渲染
		变量类型：字符串，布尔型，数值，数组，对象，常量，自定义函数，系统变量等

		在页面中输出：
			{$item.1} 等价于 {$item[1]}    点语法和方括号语法
			{$item.name.price}  === {$item['name']['price']}
			<!-- 访问对象中的成员 -->
			$item = new Stu();
			<p>{$item ->welcome()}</p> 
			<p>{$item ->price}</p>  
			<!-- 显示常量 -->
			<p>{$smarty.const.SITE_NAME}</p>
			<!-- 显示系统变量 -->
			<!-- $_POST['name'] = "admin"; -->
			<p>{$smarty.post.name}</p>	
			//凡是系统提供的，都是用 $smarty 打头，其后跟上类型和名就可以了
			//如：{$smarty.get.name}  {$smarty.session.name}

/****
三，Smarty流程控制与自定义函数
****/
	分支结构
		{if  条件} ... {else} ... {/if}
		{if  条件} ... {elseif  条件} ... {/if}
	循环结构
		{for  条件} ... {forelse} ... {/for}
		{while} ... {/while}
		{foreach $row  as  $k => $v} ... {foreachelse} ... {/foreach}
	自定义函数：
		声明：{function name='func_name' $var = 'value'} ... {/function}
		调用：{call name='func_name' $var = 'value'}

	//在模板中定义变量
	{assign var='sitename' value='博客'}
	<p>{sitename}</p>
	<!-- 简便写法 -->
	{$model ='acticle'}
	<p>{$model}</p>

	<!-- 自定义函数实例 -->
	{function name="welcome" site="博客"}
	<p>欢迎来到{$site}</p>
	{/function}

	调用自定义函数,注意：这个是单标签！！！
	{call name="welcome"}


/****
四，Smarty文件包含与模板继承
****/
	- 1 Smarty文件包含与变量设置
	文件包含：{include file = 'public/header'}
	将文件暂存到一个变量中：
	{include file='public/header.html' assign='header'}
	可以向指定文件发送变量
	{include file='public/header.html' name='peter'}

	- 2 Smarty模板继承
	步骤如下：
		a.布局模板：layout.html；需要一个父模板，也叫基础模板，布局文件，主要是
			供其他模板继承；
			布局文件本身并不对外提供访问；
		b.布局文件中需要实例化的区块：{block name='***'}{/block}
		c.模板中使用：{extends file='layout.html'}  指定要继承的布局模板
		d.模板中可以将布局模板中定义的{block}区块具体内容进行填充
?>