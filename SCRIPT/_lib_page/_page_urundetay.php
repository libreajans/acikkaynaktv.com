<?php
if (!defined('yakusha')) die('...');

$fid = $_REQUEST['fid']; settype($fid,'integer');

$vt->sql('SELECT count(file_id) FROM tv_files WHERE file_id = %u')->arg($fid)->sor($cachetime);
$sonuc = $vt->alTek();

if ($sonuc)
{
	$vt->sql('SELECT * FROM tv_files WHERE file_id = %u')->arg($fid)->sor($cachetime);
	$detay = $vt->alHepsi();

	//değişkenleri döngüyle al
	$file_id 				= $detay[0]->file_id;
	$file_name 				= $detay[0]->file_name;
	$file_posticon 			= $detay[0]->file_posticon;
	$file_version 			= $detay[0]->file_version;
	$file_beta_version 		= $detay[0]->file_beta_version;
	$file_desc 				= $detay[0]->file_desc;
	$file_longdesc 			= $detay[0]->file_longdesc;
	$file_longdesc_note 	= $detay[0]->file_longdesc_note;
	$file_longdesc_author 	= $detay[0]->file_longdesc_author;
	$file_longdesc_link 	= $detay[0]->file_longdesc_link;
	$file_ssurl 			= $detay[0]->file_ssurl;
	$file_dlurl 			= $detay[0]->file_dlurl;
	$file_beta_dlurl 		= $detay[0]->file_beta_dlurl;
	$file_catid 			= $detay[0]->file_catid;
	$file_creator 			= $detay[0]->file_creator;
	$file_rss_version 		= $detay[0]->file_rss_version;
	$file_nonrss_version 	= $detay[0]->file_nonrss_version;
	$file_video 			= $detay[0]->file_video;

	//gerekli olan biçimlendirme
	$file_name 				= stripslashes($file_name);
	$file_desc 				= stripslashes($file_desc);
	$file_longdesc 			= stripslashes($file_longdesc);
	$file_longdesc_note 	= stripslashes($file_longdesc_note);
	$file_longdesc_author 	= stripslashes($file_longdesc_author);
	$file_longdesc_link 	= stripslashes($file_longdesc_link);
	$file_rss_version 		= stripslashes($file_rss_version);
	$file_nonrss_version 	= stripslashes($file_nonrss_version);
	$file_video 			= stripslashes($file_video);

	//yeni satırlamalar
	$file_desc 				= pco_imla_denetle($file_desc);
	$file_longdesc 			= pco_imla_denetle($file_longdesc);
	$file_longdesc_note 	= pco_imla_denetle($file_longdesc_note);

	if ($file_posticon <> '') 
	{
		$file_posticon = '<img width="82" src="'.SITELINK.'/posticons/'.$file_posticon.'">';
	}
	else
	{
		$file_posticon = '<img width="82" src="'.SITELINK.'/posticons/rules.jpg">';
	}

	if ($file_rss_version <> '') 
	{
		$file_rss_version = '<a href="'.$file_rss_version.'"><img title="Bu yazılım ile ilgili sürüm güncellemeleri RSS ile Takip edilmektedir" src="'.SITELINK.'/_img/rss_blue.png">';
	}
	else if ($file_nonrss_version <> '') 
	{
		$file_rss_version = '<a href="'.$file_nonrss_version.'"><img title="Bu yazılım ile ilgili sürüm güncellemeleri Manuel Takip edilmektedir" src="'.SITELINK.'/_img/rss_network.png">';
	}
	else
	{
		$file_rss_version = '<img title="Bu yazılım ile ilgili sürüm güncellemeleri RSS ile Takip edilememektedir :(" src="'.SITELINK.'/_img/rss_stop.png">';
	}
}

if ($sonuc){
?>

<div id="left">

<div class="right_articles">
<a href="<?=ANASAYFALINK?>">Ana Sayfa</a> &raquo; <a href="<?=ANASAYFALINK.'?cat='.$file_catid?>"><?=$array_kategorilistesi[$file_catid]["cat_name"]?></a>
<?php if ($_SESSION[SES]["ADMIN"]==1) echo '| <a title="Düzenle" href="'.SITELINK.'/admin.php?menu=dosyalar&un='.$file_id.'">@</a>'; ?>
</div>
<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr>
<td valign="top" width="100">
<div class="button-primary">
<center>
<p><?=$file_rss_version?></p>
<?=$file_posticon?>
<br>
<a target="_blank" href="<?=$file_dlurl?>">
<img title="yazılımı indir" src="<?=SITELINK?>/_img/icon_indir.png">
<br>Stabil Sürüm İndir
</a>
</center>
</div>

<?php if ($file_beta_version) { ?>
<div class="button-primary">
<center>
<br>
<a target="_blank" href="<?=$file_beta_dlurl?>">
<img width="80" title="yazılımı indir" src="<?=SITELINK?>/_img/icon_beta.png">
<br>Beta Sürüm İndir
<br>(V.<?=$file_beta_version; ?>)
</a>
</center>
</div>
<?php } ?>
</td>
<td>&nbsp;&nbsp;&nbsp;</td>
<td valign="top">
<div class="sagkutu">
<!--	<a title="Öneride Bulun" href="<?=SITELINK?>/forum/posting.php?mode=newtopic&f=6"><img width="24" src="<?=SITELINK?>/_img/icon_oneri.png"></a>
	<a title="Hata Bildir" href="<?=SITELINK?>/forum/posting.php?mode=newtopic&f=10"><img width="24" src="<?=SITELINK?>/_img/icon_hata.png"></a>
	<a title="Üye Ol" href="<?=SITELINK?>/forum/profile.php?mode=register"><img width="24" src="<?=SITELINK?>/_img/icon_uyeol.png"></a>-->
	</div>
	<h1><?=$file_name?> v.<?=$file_version?><?=$duzenlelink?></h1>
	<p><strong>Geliştirici :</strong> <a target="_blank" href="<?=$file_creator?>"><?=$file_creator?></a></p>
	<?php if ($file_longdesc_note) echo '<div class="successbox">'.$file_longdesc_note.'</div>'; ?>
	<?php if ($file_longdesc_author) {?>
	<div><strong>Yazan: <?=$file_longdesc_author?></strong></div>
	<?php }?>
	<?php if ($file_longdesc_link) {?>
	<div><strong>Yazı Kaynağı: <a href="<?=$file_longdesc_link?>"><?=$file_longdesc_link?></a></strong></div>
	<?php }?>
	<div><?=$file_longdesc?></div>
	<img width="100%" src="<?=$file_ssurl?>">
	<?php if ($file_video) echo $file_video; ?>
</td>
</tr>
</table>
</div>


<?php } else { include($siteyolu."/_lib_temp/_hata.php"); } ?>