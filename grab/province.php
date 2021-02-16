<?php
require_once 'Db.class.php';
require_once 'function.php';

//$url = 'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2016/';

$config = require 'config.php';
$path = $config['path'];
$db = new Db($config['db']);
$tbl_province = 'province';
$tbl_grab = 'grab';
// 写入文件
//$data = curlGet($url);
//echo $data ;

// $data = iconv('gb2312', 'utf-8', preg_replace('/gb2312/', 'utf-8', $data));
//$data = iconv('gbk', 'utf-8', preg_replace('/gb2312/', 'utf-8', $data));
//file_put_contents($path .'src/index.html', $data);
 $data = file_get_contents($path .'src/index.html');

$pattern = '/<a\s+href=[\'"](.+?)[\'"]>(.+?)<br\/?><\/a>/'; // 省
$sum = preg_match_all($pattern, $data, $matches);

$province_sql = "insert into $tbl_province (province_code, name) values ";

$grab_sql = "insert into $tbl_grab (uri) values ";
for ($i=0; $i < $sum; $i++) {
  $uri = $matches[1][$i];
  $province_code = str_replace('.html', '0000000000', $uri);
  $name = $matches[2][$i];
  $grab_sql .= "('$uri'),";
  echo $grab_sql;
  $province_sql .= "('$province_code', '$name'),";
  echo $province_sql;
}
$grab_sql = rtrim($grab_sql, ',');
$province_sql = rtrim($province_sql, ',');
$db->strartTran();
$grab_bool = $db->execSql($grab_sql);
$province_bool = $db->execSql($province_sql);
if ($grab_bool === false || $province_bool === false) {
  $db->rollBackTran();
}else {
  $db->commitTran();
}
