<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="tr">
<head>
<meta name="google-site-verification" content="1m30TyMl16NlSmo6h26M5zL1DABx5hi1Ezn3AG6WnlQ" />
<meta http-equiv="Content-Language" content="tr">
<meta http-equiv="Cache-Control" content="public">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="category" content="general">
<meta name="robots" content="index, follow">
<meta name="distribution" content="global">
<meta name="resource-type" content="document">
<link rel="stylesheet" type="text/css" href="style.css" media="screen">
<?php if ($mes_http_link) echo '<link rel="canonical" href="http://'.$mes_http_link.'" />';?>
<title><?=$sayfa_baslik?></title>

<!-- [+] open search //-->
<link href="<?=SITELINK?>/mozilla.xml" rel="search" type="application/opensearchdescription+xml" title="Açık Kaynak Yazılım Arama Motoru" />
<!-- [-] open search //-->

<!-- [+] RSS feed //-->
<link rel="alternate" type="application/rss+xml" title="<?php $YAKUSHA["site_isim"]?> Sürüm Güncellemeleri" href="<?=SITELINK?>/feed/" />
<link rel="alternate" type="application/rss+xml" title="<?php $YAKUSHA["site_isim"]?> Yeni Yazılım Eklemeleri" href="<?=SITELINK?>/feed/addtime/" />
<!-- [-] RSS feed //-->

</head>

<body>
<noscript>
Açık Kaynak Tv, Özgür Yazılım Arşiviniz. Açık Kaynak, Open Source, Özgür Yazılım...
</noscript>
<div id="content">
<div id="top_info">

<?php
	include($siteyolu."/_lib_temp/_t_uyemenuleri_top.php");
?>	
</div>

<div id="logo">
<table border="0">
<tr colspan="2">
<td>
	<a title="<?=$YAKUSHA["site_title"];?>" href=""><img src="<?=SITELINK;?>/open-source-logo.gif"></a>
</td>
<td>
<h1><a href="<?=ANASAYFALINK?>" title="<?=$YAKUSHA["site_isim"]?>"><?=$YAKUSHA["site_isim"]?></a></h1>
<div id="slogan"><?=$YAKUSHA["site_slogan"]?></div>
</td>
</tr>
</table>
</div>

<ul id="tablist">
<li><a href="<?=ANASAYFALINK?>"><span class="key">A</span>na Sayfa</a></li>
<li><a href="<?=YAZILIMLISTESILINK?>"><span class="key">Y</span>azılım Listesi</a></li>
<li><a href="<?=HABERLERLINK?>"><span class="key">H</span>aberler</a></li>
<li><a href="<?=YOLHARITASILINK?>"><span class="key">Y</span>ol Haritası</a></li>
<li><a href="<?=KURALLARLINK?>"><span class="key">K</span>urallar</a></li>
<li><a href="<?=HAKKIMIZDALINK?>"><span class="key">H</span>akkımızda</a></li>
<!--<li><a href="<?=SITELINK?>/forum/"><span class="key">FORUM</span></a></li>-->
</ul>
<br>