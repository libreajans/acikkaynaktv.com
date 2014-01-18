<?php
if(!defined('yakusha')) die('...');

/**
Test sunucunun ayarları
Bu ayarları kurcalamaya gerek yok, 
localde çalıştırırken kullanıyorum
*/
	$dbCon = array(
		'host' => 'localhost',
		'name' => 'localdb',
		'user' => 'localuser',
		'pass' => 'localpass',
		'lang' => 'utf8',
	);

/**
Yayındaki sitenin sunucu ayarları
*/
if(ST_ONLINE == 1)
{
	$dbCon = array(
		'host' => 'uzakhost',
		'name' => 'uzakdb',
		'user' => 'uzakuser',
		'pass' => 'uzakpass',
		'lang' => 'utf8',
	);
}
//bir de class bir bağlantı oluşturalım
$vt->hataGoster(false);
$vt->baglan($dbCon) or die ('<center><img src="http://www.acikkaynaktv.com/_img/dberror.png"></center>');