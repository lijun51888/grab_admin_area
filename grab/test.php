<?php
// header('content-type:text/html;charset=utf-8');
require_once 'function.php';
require_once 'Db.class.php';
// $url = 'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2016/';
$url = 'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2016/44/18/441821.html ';
// $url = 'http://0805345.com/';
$data = curlGet($url);
$data = iconv('gb2312', 'utf-8', preg_replace('/gb2312/', 'utf-8', $data));
// file_put_contents('src/index.html', $data);
// $data = file_get_contents('src/index.html');
// echo $data;
// <a href="44.html">广东省<br></a>[\x4e00-\x9fa5]
$pattern = '/<a\s+href=[\'"](.+?)[\'"]>(.+?)<br\/?><\/a>/'; // 省
$pattern = '/<a\s+href=[\'"](.+?)[\'"]>(.+?)<\/a>/'; // 市
$pattern = '/<tr\s+class=[\'"]citytr[\'"]><td><a\s+href=[\'"](.+?)[\'"]>(.+?)<\/a><\/td><td><a\s+href=[\'"].+?[\'"]>(.+?)<\/a><\/td><\/tr>/'; // 市 44.html
$pattern = '/<a\s+href=[\'"](.+?)[\'"]>(.+?)<\/a>/'; // 县
$pattern = '/<tr\s+class=[\'"]countytr[\'"]><td><a\s+href=[\'"](.+?)[\'"]>(.+?)<\/a><\/td><td><a\s+href=[\'"].+?[\'"]>(.+?)<\/a><\/td><\/tr>/'; // 县 44/4418.html
$pattern = '/<a\s+href=[\'"](.+?)[\'"]>(.+?)<\/a>/'; // 镇
$pattern = '/<tr\s+class=[\'"]towntr[\'"]><td><a\s+href=[\'"](.+?)[\'"]>(.+?)<\/a><\/td><td><a\s+href=[\'"].+?[\'"]>(.+?)<\/a><\/td><\/tr>/'; // 镇 44/18/441821.html
// $pattern = '/<tr\s+class=[\'"]villagetr[\'"]><td>(.+?)<\/td><td>(.+?)<\/td><td>(.+?)<\/td><\/tr>/'; // 村 44/18/21/441821100.html
// $pattern = '/<a\s+href=.+>.+<\/a>/';
echo preg_match_all($pattern, $data, $matches);
echo '<pre>';
print_r($matches);
echo '</pre>';



/*$str = '123admin@admin.com';
$pattern = '/\w+@\w+\.\w+\.?\w+/';
$str = 15989200001;
$pattern = '/^1[34578]\d{9}$/';
$str = 'http://www.bai--d-u.com.cn';
$pattern = '/^http(s?)\:\/\/\w+(\-*\w+)*\.\w+(\-*\w+)*\.\w+\.?\w+/';
$str = '/';
$pattern = '/[\w\W]/';
echo preg_match($pattern, $str, $matches);
echo '<br>';
print_r($matches);*/
