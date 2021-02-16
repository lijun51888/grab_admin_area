<?php
require_once 'Db.class.php';
require_once 'function.php';

$url = 'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2016/';
$config = require 'config.php';
$path = $config['path']; // 写入文件的路径，包括抓取的页面和日志文件
$db = new Db($config['db']);
$tbl_grab = 'grab';
$tbl_city = 'city'; // 市
$tbl_county = 'county'; // 县
$tbl_town = 'town'; // 镇
$tbl_village = 'village'; // 村
// $k = 0;
do {
  $sql = "select count(id) from $tbl_grab where is_grabbed=0";
  $count = $db->queryCol($sql);
  $sql = "select id, uri, type from $tbl_grab where is_grabbed=0 limit 1";
  $grab_data = $db->queryOne($sql);
  if ($grab_data === false) exit('已抓取所有链接');
  // $grab_data = ['id' => 48, 'uri' => '44/4419.html', 'type' => 1]; // 测试
  $filename = getFile($grab_data['uri']); // 获取文件名，没有目录创建之
  /*
  $data = curlGet($url . $filename); // 抓取页面
  if ($grab_data['uri'] == '03/520324.html') {
    $data = preg_replace('/<td><a\s+href=[\'"]24\/520324116\.html[\'"]>520324116000<\/a><\/td><td><a\s+href=[\'"]24\/520324116\.html[\'"]>(.+?)<\/a><\/td>/', '<td><a href="24/520324116.html">520324116000</a></td><td><a href="24/520324116.html">fuhenzhen</a></td>', $data);
  }
  if ($grab_data['uri'] == '84/420684103.html') {
    $data = preg_replace('/<tr\s+class=[\'"]villagetr[\'"]><td>420684103005<\/td><td>220<\/td><td>(.+?)<\/td><\/tr>/', '<tr class="villagetr"><td>420684103005</td><td>220</td><td>gaokang</td></tr>', $data);
  }
  if ($grab_data['uri'] == '53/500153108.html') {
    $data = preg_replace('/<tr\s+class=[\'"]villagetr[\'"]><td>500153108200<\/td><td>220<\/td><td>(.+?)<\/td><\/tr>/', '<tr class="villagetr"><td>500153108200</td><td>220</td><td>daqinggang</td></tr>', $data);
  }
  // $data = iconv('gb2312', 'utf-8', preg_replace('/gb2312/', 'utf-8', $data)); // gb2312替换为utf-8，会出现：Notice: iconv(): Detected an illegal character in input string in
  $data = iconv('gbk', 'utf-8', preg_replace('/gb2312/', 'utf-8', $data)); // gbk替换为utf-8
  file_put_contents($path .'src/'. $filename, $data); // 写入文件
  */
   $data = file_get_contents($path .'src/'. $filename);
  // echo $data;
  $grab_data_type = $grab_data['type'];
  $grab_type = $grab_data_type +1;
  $grab_sql = "insert into $tbl_grab (uri, type) values "; // grab表
  $town_sql = '';
  if ($grab_data_type == 0) { // 市
    $pattern = '/<tr\s+class=[\'"]citytr[\'"]><td><a\s+href=[\'"](.+?)[\'"]>(.+?)<\/a><\/td><td><a\s+href=[\'"].+?[\'"]>(.+?)<\/a><\/td><\/tr>/'; // 市
    $match_sum = preg_match_all($pattern, $data, $matches);
    $sql = "insert into $tbl_city (province_code, city_code, name) values ";
    $province_code = str_replace('.html', '0000000000', $grab_data['uri']);
    for ($i=0; $i < $match_sum; $i++) {
      $grab_uri = $matches[1][$i];
      $city_code = $matches[2][$i];
      $name = $matches[3][$i];
      $grab_sql .= "('$grab_uri', $grab_type),";
      $sql .= "('$province_code', '$city_code', '$name'),";
    }
  }elseif ($grab_data_type == 1) { // 县
    $pattern = '/<tr\s+class=[\'"]countytr[\'"]><td><a\s+href=[\'"](.+?)[\'"]>(.+?)<\/a><\/td><td><a\s+href=[\'"].+?[\'"]>(.+?)<\/a><\/td><\/tr>/'; // 县
    $match_sum = preg_match_all($pattern, $data, $matches);
    $sql = "insert into $tbl_county (city_code, county_code, name) values ";
    $uri_arr = explode('/', $grab_data['uri']);
    $city_code = str_replace('.html', '00000000', end($uri_arr));
    if ($match_sum == 0) {
      $pattern = '/<tr\s+class=[\'"]towntr[\'"]><td><a\s+href=[\'"](.+?)[\'"]>(.+?)<\/a><\/td><td><a\s+href=[\'"].+?[\'"]>(.+?)<\/a><\/td><\/tr>/';
      $match_sum = preg_match_all($pattern, $data, $matches);
      $city_name = $db->queryCol("select name from city where city_code='$city_code'");
      $city_name .= '无县级区划';
      $sql .= "('$city_code', '$city_code', '$city_name'),";
      $town_sql = "insert into $tbl_town (county_code, town_code, name) values ";
      $grab_type = $grab_type +1;
      for ($i=0; $i < $match_sum; $i++) {
        $grab_uri = $matches[1][$i];
        $town_code = $matches[2][$i];
        $name = $matches[3][$i];
        $grab_sql .= "('$grab_uri', $grab_type),";
        $town_sql .= "('$city_code', '$town_code', '$name'),";
      }
    }else{
      $pattern = '/<tr\s+class=[\'"]countytr[\'"]><td>(.+?)<\/td><td>(.+?)<\/td><\/tr>/';
      $match_extra_sum = preg_match_all($pattern, $data, $matches_extra);
      $j = 0;
      if ($match_extra_sum > 0) {
        for ($i=0; $i < $match_extra_sum; $i++) {
          if (substr($matches_extra[1][$i], -4) != '</a>') {
            $county_code = $matches_extra[1][$i];
            $name = $matches_extra[2][$i];
            $sql .= "('$city_code', '$county_code', '$name'),";
            $j++;
          }
        }
      }
      for ($i=0; $i < $match_sum; $i++) {
        $grab_uri = $matches[1][$i];
        $county_code = $matches[2][$i];
        $name = $matches[3][$i];
        $grab_sql .= "('$grab_uri', $grab_type),";
        $sql .= "('$city_code', '$county_code', '$name'),";
        $j++;
      }
      $i = $j;
    }
  }elseif ($grab_data_type == 2) { // 镇
    $pattern = '/<tr\s+class=[\'"]towntr[\'"]><td><a\s+href=[\'"](.+?)[\'"]>(.+?)<\/a><\/td><td><a\s+href=[\'"].+?[\'"]>(.+?)<\/a><\/td><\/tr>/'; // 镇
    $match_sum = preg_match_all($pattern, $data, $matches);
    $sql = "insert into $tbl_town (county_code, town_code, name) values ";
    $uri_arr = explode('/', $grab_data['uri']);
    $county_code = str_replace('.html', '000000', end($uri_arr));
    $pattern = '/<tr\s+class=[\'"]towntr[\'"]><td>(.+?)<\/td><td>(.+?)<\/td><\/tr>/';
    $match_extra_sum = preg_match_all($pattern, $data, $matches_extra);
    $j = 0;
    if ($match_extra_sum > 0) {
      for ($i=0; $i < $match_extra_sum; $i++) {
        if (substr($matches_extra[1][$i], -4) != '</a>') {
          $county_code = $matches_extra[1][$i];
          $name = $matches_extra[2][$i];
          $sql .= "('$county_code', '$town_code', '$name'),";
          $j++;
        }
      }
    }
    for ($i=0; $i < $match_sum; $i++) {
      $grab_uri = $matches[1][$i];
      $town_code = $matches[2][$i];
      $name = $matches[3][$i];
      if ($grab_data['uri'] == '03/520324.html' && $name == 'fuhenzhen') {
        $name = '桴㯊镇';
      }
      $grab_sql .= "('$grab_uri', $grab_type),";
      $sql .= "('$county_code', '$town_code', '$name'),";
      $j++;
    }
    $i = $j;
  }else { // 村
    $pattern = '/<tr\s+class=[\'"]villagetr[\'"]><td>(.+?)<\/td><td>(.+?)<\/td><td>(.+?)<\/td><\/tr>/'; // 村
    $match_sum = preg_match_all($pattern, $data, $matches);
    $sql = "insert into $tbl_village (town_code, village_code, type, name) values ";
    $uri_arr = explode('/', $grab_data['uri']);
    $town_code = str_replace('.html', '000', end($uri_arr));
    for ($i=0; $i < $match_sum; $i++) {
      $village_code = $matches[1][$i];
      $village_type = $matches[2][$i];
      $name = $matches[3][$i];
      if ($grab_data['uri'] == '84/420684103.html' && $name == 'gaokang') {
        $name = '高康社区居委会';
      }
      if ($grab_data['uri'] == '53/500153108.html' && $name == 'daqinggang') {
        $name = '大青㭎村委会';
      }
      $sql .= "('$town_code', '$village_code', $village_type, '$name'),";
    }
  }
  $v = $grab_count = $town_count = 0;
  $db->strartTran(); // 开启事务
  if (substr($grab_sql, -1, 1) == ',') {
    $v = 1;
    $grab_sql = rtrim($grab_sql, ',');
    $grab_count = $db->execSql($grab_sql);
    // echo $grab_sql ,'<br>';
  }
  if ($town_sql) {
    $town_sql = rtrim($town_sql, ',');
    $town_count = $db->execSql($town_sql);
    // echo $town_sql ,'<br>';
  }
  $sql = rtrim($sql, ',');
  // echo $sql ,'<br>';
  $addr_count = $db->execSql($sql);
  $grab_upt = $db->execSql("update $tbl_grab set is_grabbed=1 where id=". $grab_data['id']);
  $is_success = 0;
  if (($v == 1 && $grab_count === false) || ($town_sql && $town_count === false) || $addr_count === false || $grab_upt === false) {
    /*var_dump(($v == 1 && $grab_count === false));
    var_dump(($town_sql && $town_count === false));
    var_dump(($addr_count === false));
    var_dump(($grab_upt === false));*/
    $db->rollBackTran(); // 回滚事务
  }else {
    $is_success = 1;
    $db->commitTran(); // 提交事务
  }
  file_put_contents($path .'log.txt', date('Y-m-d H:i:s') .' '. $filename .' '. $i .' '. $is_success ."\r\n", FILE_APPEND); // 写入日志
  usleep(2000000); // 2秒
} while ($count > 0);
//   $k++;
// } while ($k < 5);
