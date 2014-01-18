<?php
// $sitelink = 'http://'.$_SERVER['HTTP_HOST']; $sitelink = trim($sitelink);
// $sitelink = 'http://'.$_SERVER['HTTP_HOST'].'/acikkaynaktv.com'; 

//istisnai durumlar için dosya adları oluşturuluyor
define('URUNDETAY','urundetay.php');

//ANA SAYFA
// define('SITELINK',$sitelink);
define('ANASAYFALINK',			SITELINK.'/index.php');
define('URUNDETAYLINK',			SITELINK.'/urundetay.php');
define('YAZILIMLISTESILINK',	SITELINK.'/yazilimlistesi.php');
define('HABERLERLINK',			SITELINK.'/haberler.php');
define('YOLHARITASILINK',		SITELINK.'/yolharitasi.php');
define('KURALLARLINK',			SITELINK.'/kurallar.php');
define('HAKKIMIZDALINK',		SITELINK.'/hakkimizda.php');

//ACP & UCP
define('PROFILELINK',			SITELINK.'/ucp.php');
define('GIRISLINK',				SITELINK.'/giris.php');
define('CIKISLINK',				SITELINK.'/giris.php?fsignout=1');
define('ADMINLINK',				SITELINK.'/admin.php');
define('PHOTOLINK',				SITELINK.'/caticon/');

//seo değerleri tanımlanıyor
define('SEO','.html');
define('SEO_OPEN',1);

//ana sayfada görülen en yeni x yazılım 
define('ENYENILER',5);

//SECURTY
//platform bağımsız açıklar için session güvencesi...
// ==> | işaretinden sonrası her site için ayrı tanımlanmalıdır 
define('SES',md5(SITELINK.'|SES'));

//sql cache yolunu belirtelim
$vt->kayitYolu('./_cache/'); 
