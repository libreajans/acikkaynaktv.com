<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

if (isset($_REQUEST["uyeform"]))
{
	//metin gelmesi gereken alanlar
	$user_email_form 		= $_REQUEST["user_email"];
	$user_username_form 	= $_REQUEST["user_username"];
	$user_password_form 	= $_REQUEST["user_password"];

	$user_email 			= addslashes(trim(strip_tags(strtolower($_REQUEST["user_email"]))));
	$user_username 			= addslashes(trim(strip_tags($_REQUEST["user_username"])));
	$user_password 			= addslashes(trim(strip_tags($_REQUEST["user_password"])));

	//HATA KONTROLÜ
	if ( strlen($user_email) < 2 or !eregi("[[:alpha:]]",$user_email) )
	$islem_bilgisi = '<div class="errorbox">Üye Eposta alanını boş bırıkmayınız.</div>';

	if ( strlen($user_username) < 2 or !eregi("[[:alpha:]]",$user_username) )
	$islem_bilgisi = '<div class="errorbox">Üye Adı alanını boş bırıkmayınız.</div>';

	if ( strlen($user_password) < 2 or !eregi("[[:alpha:]]",$user_password) )
	$islem_bilgisi = '<div class="errorbox">Üye Parola alanını boş bırıkmayınız.</div>';
	
	if ( strlen($user_email) > 2 )
	{
		$vt->sql('SELECT count(user_email) FROM tv_users WHERE user_email = %s')->arg($user_email)->sor();
		$sayi = $vt->alTek();
		if ( $sayi > 0 )
		{
			$islem_bilgisi = '<div class="errorbox">'.$user_email.' Bu eposta adresi sistemde kayıtlı bir üye ile eşleşiyor, lütfen kontrol ediniz.</div>';
		}
	}	

	if ($islem_bilgisi == '')
	{
		//üye ekliyoruz
		$vt->sql('INSERT INTO tv_users ( user_email, user_username, user_password ) VALUES ( %s,%s,%s)');
		$vt->arg($user_email, $user_username, $user_password)->sor();
		if($vt->affRows())
		{
			$islem_bilgisi = '<div class="successbox">'.$user_username.' isimli üye sisteme eklenmiştir.</div>';
		}
		$user_email_form = $user_username_form = $user_password_form = '';
	}
}
?>

<form name="urunform" action="<?=$acp_uyelerlink?>&uyeekle=1" method="POST">
<input type="hidden" name="menu" value="uyeler">
<input type="hidden" name="islem" value="uyeekle">

<h1>Üye Ekle</h1>

<?=$islem_bilgisi ?>

<table width="100%" border="0">
<tr>
<td valign="top"> 
<table valign="top" width="100%" cellspacing="5" border="0">
<tr class="col1">
<th colspan="6">
TEMEL BİLGİLER
</th>
</tr>
<tr><td>Üye Eposta </td><td> : </td><td><input type="text" name="user_email" style="width: 250px" maxlength="70" value="<?=$user_email_form ?>"> <font color="red">*</font></td></tr>
<tr><td>Kullanıcı Adı </td><td> : </td><td><input type="text" name="user_username" style="width: 250px" maxlength="70" value="<?=$user_username_form ?>"> <font color="red">*</font></td></tr>			
<tr><td>Parola </td><td> : </td><td><input type="password" name="user_password" style="width: 250px" maxlength="70" value="<?=$user_password_form ?>"> <font color="red">*</font></td></tr>	
<tr><td></td><td> : </td><td><input class="button1" id="uyeform" name="uyeform" value="ÜYE EKLE" type="submit"></td></tr>	
</table>
</td>
</tr>
</table>
</form>
<pre>
* Kırmızı işaretli alanların doldurulması zorunludur.
</pre>