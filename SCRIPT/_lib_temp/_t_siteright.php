<?php
if (!defined('yakusha')) die('...');
?>
<div id="right">

<div class="top_box2">
<form action="<?=ANASAYFALINK?>" method="GET">
<input style="width: 175px;" name="aramaanahtari" value="" type="text">
<input style="width: 50px;" value=" Ara " type="submit"> 
</form>
</div>

<div class="top_box2">
<form action="<?=ANASAYFALINK?>" method="post">
<select style="width: 180px;" name="os">
<option value="win">Windows Yazılımları</option>					
<option value="gnu">Linux Yazılımları</option>					
<option value="mac">MacOS Yazılımları</option>					
<option value="all">Bütün Yazılımlar</option>
</select>
<input type="submit" name="selecter" value="Seç">
</form>
</div>

<div class="top_box2">
<strong>Açık Kaynak Tv -> 0.3.0 sürümü yayınlandı.</strong>
<br>
<br>Utf-8 altyapısına geçildi
</div>

<div class="right_articles">
<center>
<a title="Facebook Sayfamız" href="http://www.facebook.com/pages/Acik-Kaynak-TV/346284819039" target="_blank">
<img width="55" src="<?=SITELINK ?>/_img/icon_facebook.png"></a>

<a title="Friend Feed beslemeleri ile takip edin" href="https://friendfeed.com/acikkaynaktv" target="_blank">
<img width="55" src="<?=SITELINK ?>/_img/icon_friendfeed.png"></a>

<a title="Twitter Hesabımız" href="https://twitter.com/acikkaynaktv" target="_blank">
<img width="55" src="<?=SITELINK ?>/_img/icon_twitter.png"></a>

<a title="Beslemeleri takip edin" href="<?=SITELINK ?>/feed.php" target="_blank">
<img width="55" src="<?=SITELINK ?>/_img/icon_rss.png"></a>
</center>
</div>

<!--<div class="right_articles">
<center>
<a title="Öneride Bulun" href="<?=SITELINK?>/forum/posting.php?mode=newtopic&f=6"><img width="70" src="<?=SITELINK?>/_img/icon_oneri_big.png"></a>

<a title="Hata Bildir" href="<?=SITELINK?>/forum/posting.php?mode=newtopic&f=10"><img width="70" src="<?=SITELINK?>/_img/icon_hata_big.png"></a>

<a title="Forumlarımıza Katıl" href="<?=SITELINK?>/forum/profile.php?mode=register"><img width="70" src="<?=SITELINK?>/_img/icon_uyeol_big.png"></a>
</center>
</div>-->

<!--<div class="right_articles">
<center>
<a title="GNU Free Doc Lisance" href="http://www.gnu.org/copyleft/fdl.html">
<img src="http://www.acikkaynaktv.com/_img/gnu-fdl.png">
</a>
<a title="Creative Commons" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">
<img src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png"></a>
</center>
</div>-->

<div class="right_articles">
<b><font color="#cc0000" face="trebuchet ms, Arial, Helvetica" size="1">YAZILIM KATEGORİLERİ</font></b> 
<small>
<?=$kategoriler_metin ?>
</small>
</div>
<!--
<div class="top_box">
<strong>Hosting Sponsorumuz</strong>
<br><a href="http://www.kurehosting.com/index.php"><img width="230" src="http://www.kurehosting.com/templates/kurehosting/images/hosting-logo.gif"></a>
</div>
-->
</div>