<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

$duzenle = $_REQUEST["duzenle"];

if (isset($_REQUEST["uyeform"]))
{
	//metin gelmesi gereken alanlar
	$user_id 		= addslashes(trim(strip_tags($_REQUEST["duzenle"])));
	$user_email 	= addslashes(trim(strip_tags($_REQUEST["user_email"])));
	$user_username 	= addslashes(trim(strip_tags($_REQUEST["user_username"])));

	//HATA KONTROLÜ
	if ( strlen($user_email) < 2 or !eregi("[[:alpha:]]",$user_email) )
	$islem_bilgisi = '<div class="errorbox">Üye Eposta alanını boş bırıkmayınız.</div>';

	if ($islem_bilgisi == '')
	{
		$vt->sql('UPDATE tv_users SET user_email = %s, user_username = %s WHERE user_id = %u');
		$vt->arg($user_email, $user_username, $user_id)->sor();
		$islem_bilgisi = '<div class="successbox">Üye bilgileri güncellenmiştir.</div>';
	}
}

$vt->sql('SELECT user_id, user_username, user_email, user_status FROM tv_users WHERE user_id = %u')->arg($duzenle)->sor();
$sonuc = $vt->alHepsi();

$user_id 		= $sonuc[0]->user_id;
$user_username 	= $sonuc[0]->user_username;
$user_email 	= $sonuc[0]->user_email;
$user_status 	= $sonuc[0]->user_status;
?>

<form name="uyeform" action="<?=$acp_uyelerlink?>&amp;duzenle=<?=$user_id?>" method="POST">
<input type="hidden" name="menu" value="uyeler">
<input type="hidden" name="islem" value="guncelle">
<input type="hidden" name="duzenle" value="<?=$user_id?>">

<h1>Üye Düzenle &raquo; <?=$user_username?></h1>

<?=$islem_bilgisi ?>

<table valign="top" width="100%" cellspacing="3" border="0">
	<tr class="col1">
		<th colspan="3">
			TEMEL BİLGİLER
		</th>
	</tr>

	<tr>
		<td height="30">Üyelik Seviyesi</td><td> : </td><td>
		<div>
			<?=$array_user_status[$user_status]?>
		</div>
		</td>
	</tr>
	<tr><td height="30">Üye Eposta </td><td> : </td><td><div><input type="text" name="user_email" style="width: 250px" maxlength="70" value="<?=$user_email?>"> <font color="red">*</font></div></td></tr>
	<tr><td height="30">Kullanıcı Adı </td><td> : </td><td><div><input type="text" name="user_username" style="width: 250px" maxlength="70" value="<?=$user_username?>"> <font color="red">*</font></div></td></tr>
	<tr><td> </td><td>:</td><td>
		<input class="button1" id="uyeform" name="uyeform" value="ÜYE BİLGİLERİNİ DÜZENLE" type="submit">
	</td></tr>	
</table>
</form>
<pre>
* Kırmızı işaretli alanların doldurulması zorunludur.
</pre>