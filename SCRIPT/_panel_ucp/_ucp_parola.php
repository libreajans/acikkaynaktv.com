<?php
if (!defined('yakusha')) die('...');
if ($_REQUEST["sifredegistirmeformu"])
{
	$yenisifre = trim(substr($_POST["parola1"],0,32));
	$yenisifredogrulama = trim(substr($_POST["parola2"],0,32));
	$mevcutsifre = trim(substr($_POST["parolam"],0,32));

	if ($yenisifre <> $yenisifredogrulama)
	{
		$islem_bilgisi.= '<div class="errorbox">Yeni Parola Hatası <br>Her iki alan da aynı ifadeyi içermelidir.</div>';
	}	
	
	if(strlen($yenisifre) < 4 or strlen($yenisifre) > 32)
	{	
		$islem_bilgisi.= '<div class="errorbox">Yeni Parola Hatası <br>Yeni parola en az 4, en fazla 32 karakter uzunlukta olmalıdır.</div>';
	}
	
	if(strlen($mevcutsifre) < 1)
	{
		$islem_bilgisi.= '<div class="errorbox">Mevcut Parola Hatası <br>Mevcut Parolayı boş bırakmayınız.</div>';
	}
	
	if ($islem_bilgisi == '')
	{
		$yenisifre = md5($yenisifre);
		$mevcutsifre = md5($mevcutsifre);

		if ($yenisifre == $mevcutsifre)
		{
			$islem_bilgisi.= '<div class="errorbox">Mevcut ve Yeni parola alanları aynı olamaz.</div>';
		}	
		else 
		{
			$vt->sql('UPDATE tv_users SET user_password = %s WHERE user_email = %s AND user_password = %s');
			$vt->arg($yenisifre,$_SESSION[SES]["user_email"],$mevcutsifre)->sor();
			if ( $vt->affRows())
			{
				$islem_bilgisi.= '<div class="successbox">Yeni parolanız belirlendi.</div>';
			}
			else
			{
				$islem_bilgisi.= '<div class="errorbox">Mevcut Parola Hatası <br>Mevcut parolanızı yanlış girdiniz.</div>';
			}
		}
	}
}
include($siteyolu."/_panel_ucp/_temp/_t_ucp_parola.php");
?>