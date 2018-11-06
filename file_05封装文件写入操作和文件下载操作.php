<?php 
/**
*文件写入操作
* @param $filename 
* @param $data 
* @param $clear 
* @return string 提示信息
**/
	function write_file($filename,$data,$clear = false)
	{
		// 获取目标目录
		$dirname = dirname($filename);
		// 判读目录是否存在
		if (!file_exists($dirname)) {
			mkdir($dirname,0777,true);
		}
		// 判断目录是否为数组或对象
		if (is_array($data) || is_object($data)) {
			$data = serialize($data);
		}
		// 判断是否清空源文件内容
		if ($clear ==false) {
			if (is_file($filename) && is_readable($filename)) {
				if (filesize($filename) >0) {
					$srcData = file_get_contents($filename);
					$data = $srcData.$data;
				}
			}
		}

		if (file_put_contents($filename, $data)) {
			return '文件写入成功';
		}
		return '文件写入失败';
	}


/**
*文件下载操作
* @param $filename 
* @return string
**/
	function download_file($filename)
	{
		// 告诉浏览器，返回文件的大小
		header('Accept-Length:'.filesize($filename));
		// 告知浏览器，文件作为附件处理，并告知浏览器下载完的文件名
		header('Content-Disposition:attachment;filename='.basename($filename));
		// 输出文件
		readfile($filename);
	}



?>