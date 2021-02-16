1.创建好数据库；
2.设置好数据库连接信息；
3.运行province.php；
4.后台运行grab.php

要做特殊处理：
1.东莞和中山无县级区划
2.市辖区

town：中关村国家自主创新示范区大兴生物医药产业基地 22个字符
village：中关村国家自主创新示范区生物医药产业基地虚拟社区 24个字符
village：南昌高新技术产业开发区艾溪湖管理处孺子社区居委会（南昌高新开发区） 33个字符
http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2016/52/03/520324.html 桴㯊镇要做特殊处理
http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2016/42/06/84/420684103.html 湖北省襄阳市宜城市小河镇高康社区居委会要做特殊处理
http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2016/50/01/53/500153108.html 大青村委会要做特殊处理

程序源码还有一些说明和地址库都打包好了，打包文件包括：
1.	数据库结构：structure.sql
2.	完整的数据库：db_address.sql
3.	Readme文件，在使用程序之前最好先阅读此文件：readme.txt
4.	日志文件，在运行grab.php文件时会不断写入日志到此文件，可以tail –f动态读取文件内容：log.txt
5.	记录所有抓取到的页面：src.tar.gz
6.	记录抓取的页面的目录：src
7.	配置文件：config.php
8.	数据库操作类：Db.class.php
9.	自定义函数：function.php
10.	抓取省脚本文件：province.php
11.	抓取市县镇村脚本文件：grab.php
12.	测试文件：test.php
13.     PHP抓取地址库.docx
