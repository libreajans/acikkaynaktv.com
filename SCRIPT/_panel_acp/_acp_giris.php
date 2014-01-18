<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"]==1) exit ();

include($siteyolu."/_panel_acp/_temp/_t_adminbaslangic.php");
include($siteyolu."/_panel_acp/_temp/_t_adminmenuleri.php");
?>
<div id="main">

<h1><?=$YAKUSHA["site_isim"]?> Yönetim Paneline Hoşgeldiniz</h1>

<p>Bu sayfadan panonuz için gerekli olan tüm fonksiyonlara hızlı bir şekilde ulaşabilirsiniz.</p>

<?=$mesaj?>

<table>
<tr>
<th width="33%">DOSYA YÖNETİMİ</th>
<th width="33%">ÜYE YÖNETİMİ</th>
<th width="33%">İKON YÖNETİMİ</th>
</tr>
<tr>
<td class="middle">
<a class="main-item" href="<?=$acp_dosyalarlink?>"><img src="<?=SITELINK?>/_img/icon_dosya.png"><br>Dosya Listesi</a>
<a class="main-item" href="<?=$acp_dosyalarlink?>&amp;dosyaekle=1"><img src="<?=SITELINK?>/_img/icon_ekle.png"><br>Dosya Ekle</a>
</td>

<td class="middle">
<a class="main-item" href="<?=$acp_uyelerlink?>"><img src="<?=SITELINK?>/_img/icon_uyelistesi.png"><br>Üye Listesi</a>
<a class="main-item" href="<?=$acp_uyelerlink?>&amp;uyeekle=1"><img src="<?=SITELINK?>/_img/icon_uyeekle.png"><br>Üye Ekle</a>
</td>

<td class="middle">
<a class="main-item" href="<?=$acp_iconlink?>"><img src="<?=SITELINK?>/_img/icon_iconlistesi.png"><br>İkon Listesi</a>
<a class="main-item" href="<?=$acp_iconlink?>&amp;ekle=1"><img src="<?=SITELINK?>/_img/icon_ekle.png"><br>İkon Ekle</a>
</td>

</tr>
<tr>
<th width="33%">HABER YÖNETİMİ</th>
<th width="33%">KATEGORİ YÖNETİMİ</th>
<th width="33%"></th>
</tr>
<tr>
<td class="middle">
<a class="main-item" href="<?=$acp_haberlerlink?>"><img src="<?=SITELINK?>/_img/icon_news.png"><br>Haber Listesi</a>
<a class="main-item" href="<?=$acp_haberlerlink?>&amp;haber_ekle=1"><img src="<?=SITELINK?>/_img/icon_ekle.png"><br>Haber Ekle</a>
</td>

<td class="middle">
<a class="main-item" href="<?=$acp_kategorilerlink?>"><img src="<?=SITELINK?>/_img/icon_kategorilistesi.png"><br>Kategori Listesi</a>
<a class="main-item" href="<?=$acp_kategorilerlink?>&amp;kategoriekle=1"><img src="<?=SITELINK?>/_img/icon_ekle.png"><br>Kategori Ekle</a>
</td>

<td class="middle"></td>

</tr>
</table>
</div>

<?php include($siteyolu."/_panel_acp/_temp/_t_adminbitis.php"); ?>