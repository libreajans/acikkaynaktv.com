<?php
if (!defined('yakusha')) die('...');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Language" content="tr">
<meta http-equiv="Cache-Control" content="public">
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<title><?=$sayfa_baslik?> :: Üye Paneli</title>
</head>
<body>

<?php include($siteyolu."/_panel_acp/_temp/_t_admincss.php");?>

<div id="wrap">

<div id="page-body">
<div id="tabs">

<ul>
<li <?php if(!$menu || 
$menu == 'profile' || 
$menu == 'bilgi' || 
$menu == 'parola'
) echo 'id="activetab"'; ?>><a href="<?=PROFILELINK?>"><span>ÜYE PANELİM</span></a></li>
<li><a href="<?=ANASAYFALINK?>"><span>ANA SAYFA</span></a></li>
</ul>
</div>

<div id="acp">
<div class="panel">
<span class="corners-top"><span></span></span>
<div id="content">