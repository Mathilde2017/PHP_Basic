<?php 
/****
一，目录
****/
	- 文件信息相关函数
	- 文件路径相关函数
	- 文件内容操作函数
	- 封装文件操作方法

/****
二，文件相关的PHP函数
****/
	// 设置时间域
	date_default_timezone_set('Asia/Shanghai');

	- 1 文件信息相关函数
		filetype(filename)	       获取文件类型
		filesize(filename)	       获取文件大小(返回字节数)
		filectime(filename)		获取文件创建时间(返回时间戳) c : Create
		filemtime(filename)	获取文件修改时间 m:Modify
		fileatime(filename)		取得文件的上次访问时间

		查询文件的权限 
		-is_readable(filename) 	判断给定文件名是否存在并且可读
		is_writable(filename) 		判断给定的文件名是否存在且可写
		is_executable(filename)	判断给定文件名是否存在且可执行

	- 2 文件路径相关函数
		pathinfo(path,options)		返回文件路径的信息,
			// 如果指定了options，将会返回指定元素；它们包括：PATHINFO_DIRNAME，
			// PATHINFO_BASENAME 和 PATHINFO_EXTENSION 或 PATHINFO_FILENAME。
			// 如果没有指定 options 默认是返回全部的单元

		dirname(path)		返回路径中的目录部分,
		// 返回 path 的父目录。 如果在 path 中没有斜线，则返回一个点（'.'），表示当前目录
		// 给出一个包含有指向一个文件的全路径的字符串，本函数返回去掉文件名后的目录名。
		// 在 Windows 中，斜线（/）和反斜线（\）都可以用作目录分隔符。在其它环境下是斜线（/）

		basename(path)	返回路径中的文件名部分,注意：后面的扩展名是去掉的
		file_exists(filename)	检查文件或目录是否存在

	- 3 文件操作相关函数
		touch ( string $filename [, int $time = time() [, int $atime ]] )
		// 设定文件的访问和修改时间,如果文件不存在，则会被创建。

		unlink(filename)	删除文件

		rename ( string $oldname , string $newname [, resource $context ] )
		// 重命名一个文件或目录,尝试把 oldname 重命名为 newname。也可以进行剪切操作
		// 实现剪切功能
			var_dump(rename('text.txt', 'newDir/text1.txt'));

		copy ( string $source , string $dest [, resource $context ] )
		// 将文件从 source 拷贝到 dest。
		// 这个方法可以拷贝远程文件，但需要在配置项中：allow_url_open = On;

	- 4 文件内容相关函数
		fopen(filename, mode)	打开文件或者 URL(返回资源类型); mode: rb b指的是可以打开二进制文件，建议都加上
		fread(handle, length)		 读取文件（可安全用于二进制文件）
		ftell(handle)	返回由 handle 指定的文件指针的位置，也就是文件流中的偏移量。
		rewind(handle)	倒回文件指针的位置,将 handle 的文件位置指针设为文件流的开头
		fclose(handle)		关闭一个已打开的文件指针

	- 5 文件写入相关函数
		fwrite(handle, string)	写入文件（可安全用于二进制文件）
			// 把 string 的内容写入 文件指针 handle 处,写入时，会覆盖对应字符数量的字符
			// 文件系统指针，是典型地由 fopen() 创建的 resource(资源)。

	- 6 文件读取其他函数
		fgetc ( resource $handle )		从文件指针中读取字符，从文件句柄中获取一个字符。
		fgets(handle)	从文件指针中读取一行
		fgetss(handle)	从文件指针中读取一行并过滤掉 HTML 标记,基本同fgets()
		feof(handle)	测试文件指针是否到了文件结束的位置
		ftruncate(handle, size)	将文件截断到给定的长度,接受文件指针 handle 作为参数，并将文件大小截取为 size。

	- 7 csv格式文件相关函数
		// CSV文件由任意数目的记录组成，记录间以某种换行符分隔；
		// 每条记录由字段组成，字段间的分隔符是其它字符或字符串，最常见的是逗号或制表符。

		// CSV文件样子：
		// ohn,Doe,120 jefferson st.,Riverside, NJ, 08075
		// Jack,McGinnis,220 hobo Av.,Phila, PA,0911

		fgetcsv(handle)	从文件指针中读入一行并解析 CSV 字段

		fputcsv(handle, fields)		将行格式化为 CSV 并写入文件指针,
		// 将一行（用 fields 数组传递）格式化为 CSV 格式并写入由 handle 指定的文件。

	- 8 文件内容相关其他函数
		file_get_contents(filename)	将整个文件读入一个字符串,不需要打开文件，直接读取
		file_put_contents(filename, data)	将一个字符串写入文件，不需要打开文件，直接写入
			// 和依次调用 fopen()，fwrite() 以及 fclose() 功能一样
			如果存入数组或对象，必须要将他们进行数据的转换：
				- 使用序列化进行转换 serialize(value) 存取，读取的时候，需反序列化 unserialize(str)
				- 使用json来进行转换 json_encode(value),读取时，需解码 json_decode(json)

/****
三，目录相关的PHP函数
****/
	opendir(path)	打开目录句柄

	readdir([ resource $dir_handle ])	从目录句柄中读取条目,返回目录中下一个文件的文件名。
		目录句柄的 resource，之前由 opendir() 打开,文件名以在文件系统中的排序返回

	mkdir(pathname)	新建目录
	rmdir(dirname)	删除目录,尝试删除 dirname 所指定的目录。 
		该目录必须是空的，而且要有相应的权限。
	closedir([ resource $dir_handle ])	 关闭目录句柄,关闭由 dir_handle 指定的目录流。流必须之前被 opendir() 所打开










?>