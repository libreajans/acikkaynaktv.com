<?php
if (!defined('yakusha')) die('...');
if ($_SESSION[SES]["giris"]==1)
{
?>

<!-- [+] çıkış işlemi -->
<script type="text/javascript" language="JavaScript">
<!--
//görsel bir çıkış olsun mu ne dersiniz
//hayri gülle size karartacak şimdi
function logout_question()
{
	body_self = document.getElementsByTagName('body');
	body_self[0].style.filter = 'Alpha(opacity="60")';
	body_self[0].style.MozOpacity = '0.6';
	body_self[0].style.opacity = '0.6';

	if (confirm('Oturumu kapatmak istediğinizden emin misiniz?'))
	{
		body_self[0].style.filter = 'Alpha(opacity="100")';
		body_self[0].style.MozOpacity = '1';
		body_self[0].style.opacity = '1';
		return true;
	}
	else
	{
		body_self[0].style.filter = 'Alpha(opacity="100")';
		body_self[0].style.MozOpacity = '1';
		body_self[0].style.opacity = '1';
		return false;
	}
}
//-->
</script>

<font color="#cc0000" face="trebuchet ms, Arial, Helvetica" size="2"><?=$_SESSION[SES]["email"];?></font></b>
<br>
<table border="0" width="100%">
<tr colspan="2">
<?php if ($_SESSION[SES]["ADMIN"] == 1) { ?>
<td valign="middle"><a class="vitrinler_menu" href="<?=ADMINLINK?>">Yönetici Paneli</a></td>
<td valign="middle"><img width="28" src="<?=SITELINK?>/_img/icon_acp.png"></td>
<?php } ?>

<td valign="middle"><a class="vitrinler_menu" href="<?=PROFILELINK?>">Üye Paneli</a></td>
<td valign="middle"><img width="28" src="<?=SITELINK?>/_img/icon_ucp.png"></td>

<td valign="middle"><a class="vitrinler_menu" href="<?=CIKISLINK?>" onclick="return logout_question();">Oturumu Kapat</a></td>
<td valign="middle"><img width="28" src="<?=SITELINK?>/_img/icon_exit.png"> </td>

</tr>
</table>
<?php 
} 
else
{ 
	?>
	<form method="post" action="<?=GIRISLINK?>">
		E-posta & Parola<input name="eposta" id="eposta" style="width: 120px" type="text">
		<input name="parola" id="parola" style="width: 90px" type="password">
		<input type="submit" name="fmemberin" value="onay">
	</form>
	<?php 
}
?>