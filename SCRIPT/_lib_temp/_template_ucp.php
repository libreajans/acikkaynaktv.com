<?php
if (!defined('yakusha')) die('...');
//üye giriş yapmış mı hemen kontrol ediyoruz
if ($_SESSION[SES]["giris"]==0)
{
	$sayfa_baslik = 'İşleminiz yapılıyor. Lütfen bekleyiniz...';
	$sayfa_tazele = "0; URL=".ANASAYFALINK;
	include($siteyolu."/_lib_temp/_top.php");
	exit();
}
else
{
	$sayfa_baslik = $YAKUSHA["site_baslik"];

	//menü oluşturmakta kullanıyoruz
	//işlem yoksa varsayılan işlemi belirliyoruz
	$menu = $_GET["menu"]; $menu = htmlspecialchars($menu);
	if (!$menu) $menu = "profile"; 
	switch ($menu)
	{
		case 'profile':
			$sayfa_baslik = 'Hoşgeldiniz';
			$menu = 'profile';
		break;
		case 'bilgi':
			$sayfa_baslik = 'Üye Bilgileri';
			$menu = 'bilgi';
		break;
		case 'parola':
			$sayfa_baslik = 'Parola Bilgileri';
			$menu = 'parola';
		break;
		default:
			$sayfa_baslik = 'Hoşgeldiniz';
			$menu = 'profile';
		break;
	}
	//önce linkleri yüklüyoruz
	include($siteyolu."/_panel_ucp/_ucp_define.php");
	include($siteyolu."/_panel_ucp/_temp/_t_ucp_baslangic.php"); 
	include($siteyolu."/_panel_ucp/_temp/_t_ucp_menuleri.php"); 
	include($siteyolu."/_panel_ucp/_ucp_".$menu.".php");
	include($siteyolu."/_panel_ucp/_temp/_t_ucp_bitis.php");
}
?>