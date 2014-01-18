<?php
if (!defined('yakusha')) die('...');

$redirectlink = PROFILELINK;
$fsignout = $_GET["fsignout"]; settype($fsignout,"integer");
if ($fsignout == 1)
{
	if (isset($_SESSION[SES])) unset($_SESSION[SES]);
	$_SESSION[SES]["giris"] = 0;
	$sayfa_tazele = '0; URL='.ANASAYFALINK;
	$sayfa_baslik = 'İşleminiz Gerçekleştiriliyor';
	$sayfa_mesaj = '<div class="successbox">Çıkış İşleminiz Onaylandı<br>Lütfen bekleyiniz.</div>';
	include($siteyolu."/_lib_temp/_top.php");
	exit();
}

if (isset($_REQUEST["fmemberin"]))
{
	$parola = substr($_REQUEST["parola"],0,32);
	$eposta = substr($_REQUEST["eposta"],0,70);
	$hash = md5($parola);
	//kullanıcı sorgulanıyor
	if ($parola == '' || $eposta == '')
	{
		$sayfa_tazele = "3; URL=".ANASAYFALINK;
		$sayfa_baslik = 'Hata Oluştu';
		$sayfa_mesaj = '<div class="errorbox">Kullanıcı adınızı ve Parolanızı boş bırakmayınız.<br>Lütfen tekrar deneyiniz.</div>';
		include($siteyolu."/_lib_temp/_top.php");
		exit();
	}

	$vt->sql('SELECT user_id, user_username, user_password, user_email, user_status	FROM tv_users WHERE user_email = %s AND user_password = %s');
	$vt->arg($eposta,$hash)->sor();
	$sonuc = $vt->alHepsi();
	$adet = $vt->numRows();

	//kullanıcı var ise
	if ($adet)
	{		
		//kullanıcı bilgileri oturuma aktarılıyor
		$_SESSION[SES]["user_id"] = $sonuc[0]->user_id;
		$_SESSION[SES]["user_username"] = $sonuc[0]->user_username;
		$_SESSION[SES]["user_email"] = $sonuc[0]->user_email;
		$_SESSION[SES]["user_status"] = $sonuc[0]->user_status;
		$_SESSION[SES]["giris"] = 1;
		$_SESSION[SES]["giristar"] = time();

		//yönetici ise yönetici oturumu açılıyor
		if ($_SESSION[SES]["user_status"] == 1)
		{
			$_SESSION[SES]["ADMIN"] = 1;
			$redirectlink = ADMINLINK;
		}
		else
		{
			$_SESSION[SES]["ADMIN"] =  0;
		}

		//sayfa yönlendirmesi oluşturuluyor
		$sayfa_tazele = "0; URL=".$redirectlink;
		$sayfa_baslik = 'İşleminiz Gerçekleştiriliyor';
		$sayfa_mesaj = '<div class="successbox">Üyelik Girişiniz Onaylandı.<br>Lütfen bekleyiniz.</div>';		
		include($siteyolu."/_lib_temp/_top.php");
		exit();
	}
	else
	{
		$sayfa_tazele = "3; URL=".ANASAYFALINK;
		$sayfa_baslik = 'Hata Oluştu';
		$sayfa_mesaj = '<div class="errorbox">Yanlış Kullanıcı Adı ve/veya Parola girdiniz. Lütfen tekrar deneyiniz.</div>';
		include($siteyolu."/_lib_temp/_top.php");
		exit();
	}
}
?>