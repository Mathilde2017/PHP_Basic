<?php 
/**
* 1-目录创建操作
* @param $dirName 需要创建的目录
* @return string 提示信息
**/
	function create_folder($dirName)
	{
		if (file_exists($dirName)) {
			return '存在相同文件';
		}
		if (mkdir($dirName,0777,true)) {
			return '目录创建成功';
		}
		return '目录创建失败';
	}

/**
* 2-目录删除操作
* @param $path 需要删除的目录
* @return string 提示信息
**/
	function del_folder($path)
	{
		$dir = opendir($path);
		while ($item = readdir($dir)) {
			if ($item !='.'&& $item!='..') {	//排除当前目录与父级目录  
				if (is_file($path.'/'.$item)) {
					unlink($path.'/'.$item);
				}
				if (is_dir($path.'/'.$item)) {
					// 此处实际用到递归删除，相当于：del_folder($path.'/'.$item);
					$func = __FUNCTION__;
					$func($path.'/'.$item);
				}
			}
		}
		closedir($path);
		if (rmdir($path)) {
			return '目录删除成功';
		}else{
			return '目录删除失败';
		}
		
		
	}

?>