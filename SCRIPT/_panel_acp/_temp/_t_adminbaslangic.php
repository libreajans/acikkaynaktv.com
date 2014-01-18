<?php
if (!defined('yakusha')) die('...');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Language" content="tr">
<meta http-equiv="Cache-Control" content="public">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?=$YAKUSHA["site_isim"]?> &bull; Yönetim Paneli</title>
</head>
<body>

<?php include($siteyolu."/_panel_acp/_temp/_t_admincss.php");?>

<div id="wrap">

<div id="page-body">
<div id="tabs">

<?php
$menu = $_GET['menu'];
?>
<ul>
<li <?php 
if(!$menu 
|| $menu == "giris"
|| $menu == "istatistik")
echo 'id="activetab"'; ?>><a href="<?=$acp_anamenulink?>"><span>YÖNETİM</span></a></li>
<li <?php if($menu == "haberler") echo 'id="activetab"'; ?>><a href="<?=$acp_haberlerlink?>"><span>HABERLER</span></a></li>
<li <?php if($menu == "dosyalar") echo 'id="activetab"'; ?>><a href="<?=$acp_dosyalarlink?>"><span>DOSYALAR</span></a></li>
<li <?php if($menu == "kategoriler") echo 'id="activetab"'; ?>><a href="<?=$acp_kategorilerlink?>"><span>KATEGORİLER</span></a></li>
<li <?php if($menu == "uyeler") echo 'id="activetab"'; ?>><a href="<?=$acp_uyelerlink?>"><span>ÜYELER</span></a></li>
<li <?php if($menu == "icon") echo 'id="activetab"'; ?>><a href="<?=$acp_iconlink?>"><span>İKONLAR</span></a></li>
<li><a href="<?=ANASAYFALINK?>"><span>ANA SAYFA</span></a></li>
</ul>
</div>

<div id="acp">
<div class="panel">
<span class="corners-top"><span></span></span>
<div id="content">