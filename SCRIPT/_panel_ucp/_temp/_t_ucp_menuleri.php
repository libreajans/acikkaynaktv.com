<?php
if (!defined('yakusha')) die('...');
?>

<div id="menu">
<p><strong><?=$_SESSION[SES]["user_email"];?></strong> 
<br>[ <a href="<?=CIKISLINK?>">Oturum Kapat</a> ]
<?php 
if ($_SESSION[SES]["ADMIN"]==1)
{
	echo '<br>[ <a href="'.ADMINLINK.'">Yetkili Paneli</a> ]';
}
?>

<ul>
<li class="header">Hızlı Menü</li>
<li <?php if($id == 'bilgi') echo 'id="activemenu"'; ?>><a href="<?=$ucp_bilgi?>"><span>Üye Bilgilerim</span></a></li>
<li <?php if($id == 'parola') echo 'id="activemenu"'; ?>><a href="<?=$ucp_parola?>"><span>Parola Bilgilerim</span></a></li>
</ul>
</div>