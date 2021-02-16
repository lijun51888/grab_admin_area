<?php
/**
* @param url string url
*/
function curlGet($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); // 设置连接超时时间为3秒
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

/**
* @param url       string url
* @param post_data array  post数据
*/
function curlPost($url, $post_data) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

/**
* 打印数组
* @param arr array 数组
*/
function printArr($arr) {
  echo '<pre>';
  print_r($arr);
  echo '</pre>';
}

/**
* 获取文件名，没有目录创建之
* @param uri string URI
* @return filename string 带路径的文件名
*/
/*function getFile($uri) {
  $uri_arr = explode('/', $uri);
  $count = count($uri_arr);
  $last = array_pop($uri_arr);
  $dir = $count > 1 ? 'src/'. implode('/', $uri_arr) .'/' : 'src/';
  !is_dir($dir) && mkdir($dir, 0777, true); // 创建文件夹
  $filename = $dir . $last;
  // echo $filename;
  return $filename;
}*/

/*function getFile($uri) {
  $config = require 'config.php';
  $uri_arr = explode('/', $uri);
  $filename = $uri;
  if (count($uri_arr) == 2) {
    $u_arr = explode($uri_arr[0], $uri_arr[1]);
    if (strlen($u_arr[0]) == 4) {
      $filename = substr($u_arr[0], 0, 2) .'/'. substr($u_arr[0], 2, 2) .'/'. $uri;
    }elseif (strlen($u_arr[0]) == 2) {
      $filename = $u_arr[0] .'/'. $uri;
    }
    if ($filename == $uri && strlen($uri_arr[1]) == 11) {
      $filename = substr($uri_arr[1], 0, 2) .'/'. $uri;
    }
    if ($filename == $uri && strlen($uri_arr[1]) == 14) {
      $filename = substr($uri_arr[1], 0, 2) .'/'. substr($uri_arr[1], 2, 2) .'/'. $uri;
    }
    $dir = $config['path'] .'src/'. dirname($filename);
    !is_dir($dir) && mkdir($dir, 0777, true); // 创建文件夹，第三个参数为true则递归创建
  }
  // echo $filename;
  return $filename;
}*/
function getFile($uri) {
  $config = require 'config.php';
  $uri_arr = explode('/', $uri);
  $filename = $uri;
  if (count($uri_arr) == 2) {
    $length = strlen($uri_arr[1]);
    if ($length == 11) {
      $filename = substr($uri_arr[1], 0, 2) .'/'. $uri;
    }
    if ($length == 14) {
      if (substr($uri_arr[1], 4, 2) != '00') {
        $filename = substr($uri_arr[1], 0, 2) .'/'. substr($uri_arr[1], 2, 2) .'/'. $uri;
      }else {
        $filename = substr($uri_arr[1], 0, 2) .'/'. $uri;
      }
    }
    $dir = $config['path'] .'src/'. dirname($filename);
    !is_dir($dir) && mkdir($dir, 0777, true); // 创建文件夹，第三个参数为true则递归创建
  }
  // echo $filename;
  return $filename;
}

// echo curlGet("https://www.baidu.com");
// echo curlPost('http://127.0.0.1/test.php', ['name' => 'bill']);
// echo curlGet('http://www.stats.gov.cn/tjsj/tjbz/xzqhdm/201703/t20170310_1471429.html');
