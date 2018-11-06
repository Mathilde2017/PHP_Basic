<?php 
/****
封装文件复制方法和文件重命名方法
****/

/**
* 1-文件复制操作
* @param $filename 需要复制的文件名
* @param $dest 目标目录或文件夹
* @return string 提示信息
**/
	function copy_file($filename)
	{
		if (!file_exists($filename,$dest) && !is_writable($filename) {
			return '此文件不可复制';
		}
		// 查询目录是否已经存在
		if (!is_dir($dest)) {
			mkdir($dest,0777,true);
		}
		// 拼接要拷贝到的目录和文件名
		$destname = $dest.'/'.basename($filename);
		if (!file_exists($destname)) {
			return '此目录下此文件已存在';
		}
		if (copy($filename, $destname)) {
			return '文件复制成功';
		}

		return '文件复制失败';
	}

/**
* 1-文件重命名操作
* @param $filename 需要重命名的文件名
* @param $oldname 目标原文件名
* @param $newname 目标文件名
* @return string 提示信息
**/
	function rename_file($oldname, $newname)
	{
		if (!file_exists($oldname) && !is_writable($oldname)) {
			return '此文件不可重命名';
		}
		$path = dirname($oldname);
		$destname = $path.'/'.$newname;
		if (!file_exists($destname)) {
			return '此文件名已存在';
		}
		if (rename($oldname, $newname)) {
			return '文件重命名成功';
		}
		return '文件重命名失败';
	}



?>