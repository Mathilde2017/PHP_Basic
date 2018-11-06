<?php
/****
一，CentOS Linux系统安装
****/
	1.1 virtualBox虚拟机
		https://www.virtualbox.org
	1.2 CentOS 7
		https://www.centos.org/
	1.3 XShell
		http://www.xshellcn.com/




/****
二，linux常用命令
****/
	// 2.1 查看目录文件
	ls [-选项][参数]
	ls 显示当前目录列表
	选项：
		-a 显示所有文件，包括隐藏文件
		-l  显示详细信息
		-d 查看当前目录属性
		-h 人性化显示
	参数：文件夹或路径
	ls -al 代表：显示所有文件 的 详细信息，且包括隐藏文件
	故：以上的选项可以联合使用！！！

	Linux一共三种权限：
	所有者：指该文件的创建者
	所属组：
	其他人：

	名称解析：如：drwxr-xr-x.
	分为三部分：
	d 	文件类型；
		- 文件
		d 目录
		l 软连接
		b 块设备
		p 管道
		c 字符串设备
		s 接口文件

	rwx 	所有者
	r-x 		所属组
	r-x 		其他人
	r 	可读
	w 	可写
	x 	可执行；相当于Windows里面，双击执行软件的功能


	// 2.2 切换目录
	ll [-选项] [参数]    是 ls 的别名，使用 ll，是因为它比ls 方便。但是有些不支持该命令
	ll 和ls 功能是一样的；
	ll 显示当前目录列表
		选项：
		-a 显示所有文件，包括隐藏文件
		-d 查看当前目录属性
		-h 人性化显示
		参数：文件夹或路径

	cd [参数]  切换目录
	参数：文件夹或路径
	cd / 进入根目录
	cd . 打开当前目录
	cd .. 返回上一级目录
	cd /var/rpm/ 直接进入 rpm目录，
	Tips：操作时候，如果记不完整目录名，可按tab补齐功能查找

	// 2.3 创建和删除目录
	mkdir 创建新目录
	mkdir [-选项] [参数]
		选项：
			-p 递归创建
		参数：文件夹或路径
	mkdir -p /master/php 创建一个目录，如果上级目录不存在，则需要加上参数 -p,递归创建
	mkdir -p /a/b/c /x/y/z 同时创建多个目录，中间用空格隔开

	rmdir [参数] 删除空目录
	参数：文件夹或路径

	// 2.4 复制、剪切和重命名

	cp [-选项] [原文件或目录] [目标目录]
	cp	复制目录和文件
		选项：
		-r	复制目录
		-p 	保留文件属性

	mv [-选项] [原文件或目录] [目标目录]
	mv	剪切文件或目录、重命名
		选项：
		-b	覆盖前，创建一个备份
		-f 	直接覆盖

	// 2.5 创建和删除目录
	rm [-选项] [参数]
	rm	删除文件或目录
		选项：
		-f 	强制删除
		-r 	删除目录
		参数：文件夹或路径

	rm /a/b  会提示询问是否删除，键入 y，即可
	rm -f /a/b 直接强制删除，不提示
	rm -fr 强制删除所有目录，即使目录中还有目录，也会强制一起删除，尽量少用删除操作！！！


	touch [参数]
	touch	创建空文件
	参数：文件夹或路径
	touch math pe 创建多个文件，中间用空格隔开
	如果创建，带空格的文件，怎么办呢？用双引号
	touch "ma th"
	少用空格命名，找目录时候，比较麻烦，尽量少用！！！

	// 2.6 查看文件内容
	less [参数]
	less	查看文件内容
	参数：文件名
		快捷键：
			空格	翻页
			pageup	往上翻页
			回车	换行
			上键	往上换行
			q	退出
			/	搜索
			n	查找下一个

	// 2.7 权限管理
	chmod [-选项] [权限] [参数]
	chmod	改变文件或目录权限
	选项：
		R	递归
	权限：
		R	可读
		W	可写
		X	可执行
	参数：文件名或目录
	+	增加权限
	-	减少权限
	=	赋予当前权限












?>