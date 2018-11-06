<?php 
/**
* 1-目录复制操作
* @param $src 
* @param $dest  
* @return string 提示信息
**/
	function copy_dir($src,$dest)
	{
		if (!file_exists($dest)) {
			mkdir($dest,0777,true);
		}else{
			return '该目录下已存在相同文件';
		}
		$dir = opendir($src);
		while ($item = readdir($dir)) {
			if ($item !='.' && $item !='..') {
				if (is_file($src.'/'.$item)) {
					copy($src.'/'.$item, $dest.'/'.$item);
				}
				if (is_dir($src.'/'.$item)) {
					// copy_dir($src.'/'.$item, $dest.'/'.$item);
					$func = __FUNCTION__;
					$func($src.'/'.$item, $dest.'/'.$item);
				}
			}
		}
		closedir($dir);
		return '目录复制成功';
	}

/**
* 1-目录重命名操作
* @param $oldname
* @param $newname
* @return string 提示信息
**/
	function rename_dir($oldname,$newname)
	{
		if (!file_exists($newname)) {
			if (rename($oldname, $newname)) {
				return '修改成功';
			}else{
				return '修改失败';
			}
		}
		return '已存在该文件或目录';
	}
	echo rename_dir('upload/css','upload/js');


?>