<?php
if (!defined('yakusha')) die('...');
if ($_SESSION[SES]["ADMIN"] == 1)
{
	$sayfa_baslik = $YAKUSHA["site_baslik"];

	if (isset($_GET["menu"])) $menu = $_GET["menu"]; else $menu = "giris";

	include($siteyolu."/_panel_acp/_acp_define.php");
	include($siteyolu."/_panel_acp/_acp_".$menu.".php");
}
else
{
	$sayfa_başlık = 'İşleminiz yapılıyor. Lütfen bekleyiniz...';
	$sayfa_tazele = "0; URL=".ANASAYFALINK;
	include($siteyolu."/_lib_temp/_top.php");
	exit();
}
?>