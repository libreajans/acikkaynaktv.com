<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"]==1) exit ();

$acp_anamenulink 		= ADMINLINK."?menu=giris";
$acp_uyelerlink 		= ADMINLINK."?menu=uyeler";
$acp_dosyalarlink 		= ADMINLINK."?menu=dosyalar";
$acp_kategorilerlink 	= ADMINLINK."?menu=kategoriler";
$acp_haberlerlink 		= ADMINLINK."?menu=haberler";
$acp_iconlink 			= ADMINLINK."?menu=icon";
?>