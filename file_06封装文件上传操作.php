<?php 
/**
*文件上传
* @param $filename 需要剪切的文件名
* @param $dest 目标目录或文件夹
* @return string 提示信息
**/
	function upload_file($fileInfo,$uploadPath='./upload',$allowExt=['png','jpg','jpeg','gif','txt',],$maxSize=1000000)
	{
		if ($fileInfo['error']===0) {
			$ext = strtolower(pathinfo($fileInfo['name'],PATHINFO_EXTENSION));
			if (!in_array($ext, $allowExt)) {
				return '非法文件类型';
			}
			if ($fileInfo['size']>$maxSize) {
				return '超出文件上传的最大值';
			}
			// is_uploaded_file — 判断文件是否是通过 HTTP POST 上传的
			if (!is_uploaded_file($fileInfo['temp_name'])) {
				return '非法上传操作';
			}

			if (!is_dir($uploadPath)) {
				mkdir($uploadPath,0777,true);
			}
			$uniName = md5(uniqid(microtime(true),true)).'.'.$ext;
			$dest = $uploadPath.'/'.$uniName;
			if (!move_uploaded_file($fileInfo['temp_name'], $dest)) {
				return '文件上传失败';
			}
			return '文件上传成功';
			
		}else{
			// php文件上传错误信息，0-7，中间缺少5，详情请查看php.net手册
			switch ($fileInfo['error']) {
				case 1:
					$res = '文件过大，超过系统限制';
					break;
				case 2:
					$res = '文件超过HTML限制';
					break;
				case 3:
					$res = '文件只有部分被上传';
					break;
				case 4:
					$res = '没有文件被上传';
					break;
				case 6:
					$res = '找不到临时文件夹';
					break;
				case 7:
					$res = '文件写入失败';
					break;
			}
			return $res;
		}
	}

	// 测试
	$fileInfo = $_FILES['my_file']; //my_file是前端的name值
	var_dump(upload_file($fileInfo));



?>