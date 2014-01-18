<?php
if (!defined('yakusha')) die('...');
?>
<div id="main">

<h1>Parola Bilgilerim</h1>

<p>Bu formu kullanarak, Parola Bilgilerinizin denetimini gerçekleştirebilirsiniz.</p>

<?=$islem_bilgisi ?>

<form name="sifredegistirmeformu" action="<?=$ucp_parola?>" method="post">
<table bgcolor="#F9F9F9" width="100%">
<tr><td colspan="3"><strong>Parola Bilgisi</strong></td></tr>
<tr><td style="width: 150px">E-Posta </td><td> : </td><td> <?=$_SESSION[SES]["user_email"]?></td></tr>
<tr><td>Yeni Parola </td><td> : </td><td><input type="password" name="parola1" style="width: 300px" maxlength="32"></b><font color="#FF0000">*</font></td></tr>
<tr><td>Yeni Parola (Tekrar) </td><td> : </td><td><input type="password" name="parola2" style="width: 300px" maxlength="32"><font color="#FF0000">*</font></td></tr>
<tr><td>Eski Parola </td><td> : </td><td><input type="password" name="parolam" style="width: 300px" maxlength="32" value=""><font color="#FF0000">*</font></td></tr>
<tr>
<td colspan="3" align="center">
<input type="hidden" name="menu" value="parola">
<input class="button2" value="Temizle" type="reset">
<input name="sifredegistirmeformu" class="button2" value="Yeni Parola Belirle" type="submit">
</td>
</tr>
</table>
</form>