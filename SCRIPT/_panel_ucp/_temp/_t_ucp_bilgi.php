<?php
if (!defined('yakusha')) die('...');
?>
<div id="main">

<h1>Üye Bilgilerim</h1>

<p>Bu formu kullanarak, Üye Bilgilerinizin denetimini gerçekleştirebilirsiniz.</p>

<?=$islem_bilgisi ?>

<form name="adresformu" action="<?=$ucp_bilgi?>" method="post">
<table bgcolor="#F9F9F9" width="100%">
<tr><td colspan="3"><strong>Adres Bilgisi</strong></td></tr>
<tr><td style="width: 150px">E-Posta </td><td> : </td><td> <?=$_SESSION[SES]["user_email"]?></td></tr>
<tr><td>Kullanıcı Adı </td><td> : </td><td><input type="text" name="user_username" style="width: 300px" maxlength="70" value="<?=$user_username?>"></b><font color="#FF0000">*</font></td></tr>
<tr>
<td colspan="3" align="center">
<input type="hidden" name="formuisle" value="1">
<input type="hidden" name="menu" value="bilgi">
<input class="button2" value="Temizle" type="reset">
<input name="adresformu" class="button2" value="Üye Bilgilerimi Güncelle" type="submit">
</td>
</tr>
</table>
</form>