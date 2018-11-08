<?php
/***
一，操作系统安装
***/
// 1.1 安装虚拟机：VirtualBox
	软件下载：http：//www.virtualbox.org/
	一路点击一步下一步完成安装

// 1.2 安装Linux:Centos
	1.2.1 官方网站：https://www.centos.org/
		点击Get Centos Now,下载Minimal  ISO
		说明：也可以去你信任的速度较快的镜像源下载，如：
		Mirrors.aliyun.com
		Mirrors.sohu.com
		Mirrors.163.com
	1.2.2 打开VirtualBox软件，点击新建
		设置：名称、类型、版本
	1.2.3 完成上述设置，点击下一步，
		设置：内存大小，建议1G
	1.2.4 然后一路next，直到设置文件夹位置及大小
		位置：点击右侧文件夹，选择一个安全，空间足够的目录
		大小：不小于10G，建议20G
	1.2.5 设置完成完成后，点击创建，即可完成安装

// 1.3修改CentOS系统配置
	1.3.1 上述操作完成后会出现一个虚拟机
	1.3.2 点击设置按钮对其进行相关的设置
	存储：点击控制器后的第一个光盘图标，选择你的镜像文件（ISO）位置
	网络：链接方式选择桥接网卡
	1.3.3 点击确定完成即可完成配置

// 1.4 上述操作完成后点击启动
	1.4.1 选择默认的第一个启动模式
	1.4.2 我们的镜像是完整的，跳过验证选择Skip
	1.4.3 点击next,进入下一步，选择简体中文和美国英语式键盘
	1.4.4 点击下一步，选择：默认的基本存储设备
	1.4.5 点击下一步，选择：是，忽略所有数据（Y）
	1.4.6 设置主机名：此处本人设置为：Linux
	1.4.7 点击下一步：选择默认时区（亚洲/上海）
	1.4.8 点击下一步；设置根密码和确认密码（一定要牢记），本人的密码设置为123456
	1.4.9 点击下一步：选择：替换现有的Linux系统
	1.4.10 选择默认设置，直接点击下一步，
	1.4.11 然后点击格式化，再点击将修改写入磁盘
	1.4.12 点击下一步，耐心等待即可

// 说明：
	1 VirtualBox安装和使用可能会出错，原因可能是系统GHOST（需要纯净安装）
	2 安装虚拟机时只有32位的，原因可能是BIOS没有开启虚拟技术或软件不支持
	3 安装过程中的页面可能显示不完整可以拖拽右侧的滚动条
	4 VirtualBox和物理机（你的电脑）切换使用热键（右侧的Ctrl键）
	5 安装完成后，点击重新引导，系统会自行启动

/***
二，网卡基本配置
***/
	系统启动后需要输入用户名和密码
	用户名：root
	密码：之前安装过程设置的密码(123456)

	2.1 切换到/etc/sysconfig/network-scripts/
	cd /etc/sysconfig/network-scripts/

	2.2 将ifcfg-eth0被分成ifcfg-eth0.
	cp ifcfg-eth0   ifcfg-eth0.bak

	2.3 修改配置文件（ifcfg-eth0）
	修改内容ONBOOT=yes
	添加内容：DNS1=8.8.8.8

	2.4 操作网卡（启动/暂停/重启）
	启动：/etc/init.d/network start
	暂停：/etc/init.d/network stop
	重启: /etc/init.d/network  restart

	2.5 使用工具连接虚拟机
	推荐：putty,轻量，免费，方面（无需安装）

	2.6 开关机命令
	重启：shutdown –r  now 或 reboot
	关机：shutdown –h  now或poweroff或halt

	2.7 最常用命令及操作
	Ifconfig:查看或配置网卡信息（包括IP）
	cd: change directory,切换到工作目录
	ls: list ，列表显示当前目录的内容
	pwd: print  work  directory,打印工作目录
	tab:自动补全（命令/参数）
	clear:清空屏幕(ctrl+L)
	history：显示历史命令
	上/下：翻看历史命令
	Ping ip/域名：网络监测

	2.8 VI基本操作
	打开文件：vi  文件名
	开始编辑：a或者i
	退出编辑：ESC
	保存退出：shift+zz
?>