<?php 
/**
*文件剪切
* @param $filename 需要剪切的文件名
* @param $dest 目标目录或文件夹
* @return string 提示信息
**/
	function cut_file($filename,$dest)
	{
		if (!is_file($filename)) {
			return '该文件不可剪切';
		}
		if (!is_dir($dest)) {
			mkdir($dest,0777,true);
		}
		$destName = $dest.'/'.basename($filename);
		if (is_file($destName)) {
			return '该文件夹下已存在此文件';
		}
		if (rename($filename, $destName)) {
			return '文件剪切成功';
		}else{
			return '文件剪切失败';
		}
	}

/**
*获取文件信息
* @param $filename 文件名
* @return array/string 提示信息
**/
	function get_file_info($filename)
	{
		if (!is_file($filename) && !is_readable($filename)) {
			return '该文件不可读取数据';
		}
		return [
			'type'=>filetype($filename),
			'ctime'=>date("Y-m-d H:i:s",filectime($filename)),
			'mtime'=>date("Y-m-d H:i:s",filemtime($filename)),
			'atime'=>date("Y-m-d H:i:s",fileatime($filename)),
			'size'=>filesize($filename);

		];
	}
	var_dump(get_file_info('txet.txt'));

?>