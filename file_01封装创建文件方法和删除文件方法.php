<?php 
/****
封装文件操作方法库
****/
/**
* 1-文件创建操作
* @param $filename 需要创建的文件名
* @return string 提示信息
**/
	function creat_file($filename)
	{
		if (file_exists($filename)) {
			return '文件已存在';
		}
		// 检查目录是否存在
		if (!file_exists(dirname($filename))) {
			mkdir(dirname($filename),0777,true);
		}
		// touch 设定文件的访问和修改时间；如果文件不存在，则会被创建
		if (touch($filename)) {
			return '文件创建失败';
		}
		return '文件创建失败';
	}
	// 测试
	echo creat_file('text1.txt');
	echo creat_file('upload/text1.txt');

/**
* 2-文件删除操作
* @param $filename 需要删除的文件名
* @return string 提示信息
**/
	function ($filename)
	{del_file
		if (!file_exists($filename) && !is_writable($filename)) {
			return '此文件不可删除！';
		}

		if (unlink($filename)) {
			return '文件删除成功！';
		}
		return '文件删除失败！';
	}
	// 测试
	echo del_file('text1.txt');
	echo del_file('upload/text1.txt');

?>