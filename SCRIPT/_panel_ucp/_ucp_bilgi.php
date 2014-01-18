<?php
if (!defined('yakusha')) die('...');
if ($_REQUEST["adresformu"])
{
	$user_username = addslashes(trim(strip_tags($_POST["user_username"])));
	$vt->sql('UPDATE tv_users SET user_username = %s WHERE user_id = %u')->arg($user_username, $_SESSION[SES]["user_id"])->sor();
	$islem_bilgisi = '<div class="successbox">Üye bilgileriniz güncellendi.</div>';
}
else
{
	$vt->sql('SELECT user_id, user_username, user_email FROM tv_users WHERE user_id = %u AND user_email = %s');
	$vt->arg($_SESSION[SES]["user_id"],$_SESSION[SES]["user_email"])->sor();
	$sonuc = $vt->alHepsi();
	$user_id 		= $sonuc[0]->user_id;
	$user_email 	= $sonuc[0]->user_email;
	$user_username 	= $sonuc[0]->user_username;
	//slash işaretleri temizleniyor
	$user_email 	= stripslashes($user_email);
	$user_username 	= stripslashes($user_username);
}
include($siteyolu."/_panel_ucp/_temp/_t_ucp_bilgi.php");
?>