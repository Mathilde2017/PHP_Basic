<?php 
/**
*文件大小转换操作
* @param $byte 文件字节大小
* @param $precision 保留小数
* @return string 提示信息
**/
	function trans_byte($byte,$precision=2)
	{
		$KB = 1024;
		$MB = 1024*$KB;
		$GB = 1024*$MB;
		$TB = 1024*$GB;
		if ($byte<$KB) {
			return $byte.'B';
		}elseif ($byte<$MB) {
			return round($byte/$KB,$precision).'KB';
		}elseif ($byte<$GB) {
			return round($byte/$MB,$precision).'MB';
		}elseif ($byte<$TB) {
			return round($byte/$GB,$precision).'GB';
		}else{
			return round($byte/$TB,$precision).'TB';
		}
	}

/**
*文件读取操作一
* @param $filename 文件名
* @return string 提示信息
**/
	function read_file($filename)
	{
		if (is_file($filename) && is_readable($filename)) {
			return file_get_contents($filename);
		}
		return '该文件无法读取';
	}

	function read_file_array($filename,$skip_empty_line = false)
	{
		if ($skip_empty_line ==true) {
			return file($filename,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
		}else{
			return file($filename);
		}
		return '该文件无法读取';
	}
?>