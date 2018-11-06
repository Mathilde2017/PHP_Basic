<?php 
/**
* 1-目录剪切操作
* @param $src
* @param $dest
* @return string 提示信息
**/
	function cut_dir($src,$dest)
	{
		if (is_dir($dest)) {
			$dest = $dest . '/' .$src;
			if (!file_exists($dest)) {
				if (rename($src, $dest)) {
					return '目录剪切成功';
				}else{
					return '目录剪切失败';
				}
			}else{
				return '该目录下已存在';
			}
		}else{
			return '不是目录';
		}
	}

/**
* 1-目录读取操作
* @param $path
* @return string 提示信息
**/
	function read_dir($path)
	{
		$arr = [];
		$dir = opendir($path);
		while ($item = readdir($dir)) {
			if ($item != '.' && $item !='..') {
				if (is_file($path . '/' .$item)) {
					$arr ['file'][] =$item;
				}
				if (is_dir($path . '/' .$item)) {
					$arr['dir'][] = $item;
				}
			}
		}
		closedir($dir);
		return $arr;
	}

	function dir_size($path)
	{
		$sum = 0;
		global $sum;//声明为全局变量，因为函数内部需要使用
		$dir = opendir($path);
		while ($item = readdir($path)) {
			if ($item != '.' && $item !='..') {
				if (is_file($path . '/' .$item)) {
					$sum += filesize($path . '/' .$item)
				}
				if (is_dir($path . '/' .$item)) {
					// dir_size($path . '/' .$item); 递归操作
					$func = __FUNCTION__;
					$func($path . '/' .$item);
				}
			}
		}
		return $sum;
	}

	// 测试
	echo trans_byte(dir_size('js'));

?>