<?php
if(!defined('yakusha')) die('...');

/************************************************
Güvenlik Tanımlamaları
*************************************************/
if(strlen($_SERVER['REQUEST_URI']) > 255 ||
stripos($_SERVER['REQUEST_URI'], "eval(") ||
stripos($_SERVER['REQUEST_URI'], "CONCAT") ||
stripos($_SERVER['REQUEST_URI'], "UNION SELECT") ||
stripos($_SERVER['REQUEST_URI'], "base64"))
{
	header('HTTP/1.1 414 Request-URI Too Long');
	header('Status: 414 Request-URI Too Long');
	header('Connection: Close');
	exit;
}

//sayfa saatini başlatıyoruz
$starttime = microtime(true);

#hata bastırma şekli
error_reporting(E_ALL);
error_reporting(E_ERROR);

//zaman dilimi de türkiye olsun
setlocale(LC_ALL,'tr_TR');
date_default_timezone_set('Europe/Istanbul');

//--- | --------------------------------------------------------
// Yayına almadan yapılacak ayarlar
define ('ST_ONLINE', 0); 		//yayına almak için 1 seçiniz
define ('ST_SUBDIR', 0); 		//alt dizin ise 1 yapınız
//--- | --------------------------------------------------------

//site yolu tanımlamaları
$siteyolu = realpath('./').'/';
$sitelink = 'http://'.$_SERVER['HTTP_HOST'];

//Yayındaki site için sunucu ayarları
if(ST_SUBDIR == 1)
{
	$sitelink = 'http://'.$_SERVER['HTTP_HOST'].'/'.basename(getcwd());
}

//SITELINK  tanımlıyoruz
define ('SITELINK', $sitelink);

//artık eburhan db classını kullanmaya başlıyoruz
require($siteyolu.'/_lib_class/eb.vt.php');

//nesne oluşturalım
$vt = new VT;

# define edilen değerler
include($siteyolu.'/_lib/lib_con.php');
include($siteyolu.'/_lib/lib_define.php');
include($siteyolu.'/_lib/lib_desc.php');
include($siteyolu.'/_lib/lib_sess.php');
include($siteyolu.'/_lib/lib_func.php');
include($siteyolu.'/_lib/lib_array.php');
