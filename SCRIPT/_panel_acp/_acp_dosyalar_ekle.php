<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

if ($_REQUEST["urunform"])
{
	//varsayılan değerler
	$changetar = time();

	//seçmeli gelen alanlar
	$file_catid 			= trim(strip_tags($_REQUEST["file_catid"]));
	$file_status 			= trim(strip_tags($_REQUEST["file_status"]));

	//etiket bulundurabilecek metin alanlar
	$file_desc 				= addslashes(trim($_REQUEST["file_desc"]));
	$file_longdesc 			= addslashes(trim($_REQUEST["file_longdesc"]));
	$file_longdesc_note 	= addslashes(trim($_REQUEST["file_longdesc_note"]));
	$file_longdesc_author 	= addslashes(trim($_REQUEST["file_longdesc_author"]));
	$file_longdesc_link 	= addslashes(trim($_REQUEST["file_longdesc_link"]));
	$file_video 			= addslashes(trim($_REQUEST["file_video"]));

	//metin gelmesi gereken alanlar
	$file_name 				= addslashes(trim(strip_tags($_REQUEST["file_name"])));
	$file_version 			= addslashes(trim(strip_tags($_REQUEST["file_version"])));
	$file_posticon 			= addslashes(trim(strip_tags($_REQUEST["file_posticon"])));
	$file_creator 			= addslashes(trim(strip_tags($_REQUEST["file_creator"])));
	$file_ssurl 			= addslashes(trim(strip_tags($_REQUEST["file_ssurl"])));
	$file_dlurl 			= addslashes(trim(strip_tags($_REQUEST["file_dlurl"])));
	$file_screen 			= addslashes(trim(strip_tags($_REQUEST["file_screen"])));
	$file_rss_version 		= addslashes(trim(strip_tags($_REQUEST["file_rss_version"])));
	$file_nonrss_version 	= addslashes(trim(strip_tags($_REQUEST["file_nonrss_version"])));

	//tasarım gereği böyle bir saçmalık var
	//kim saçmaladı: tabii ben
	if ($file_catid < 1) $file_catid = '-1';
	
	//hata kontrolü
	if ( strlen($file_name) < 2 || !eregi("[[:alpha:]]",$file_name) )
	$islem_bilgisi = '<div class="errorbox">Dosya Adı veya Dosya Açıklaması alanını boş bırıkmayınız</div>';

	if ($islem_bilgisi == '')
	{
		$vt->sql('INSERT INTO tv_files ( file_name, file_desc, file_creator, file_version, file_beta_version, file_longdesc, file_longdesc_note, file_longdesc_author, file_longdesc_link, file_ssurl, file_dlurl, file_beta_dlurl, file_catid, file_posticon, file_status, file_screen, file_rss_version, file_nonrss_version, file_video, createtar, changetar ) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)');
		$vt->arg($file_name, $file_desc, $file_creator, $file_version, $file_beta_version, $file_longdesc, $file_longdesc_note, $file_longdesc_author, $file_longdesc_link, $file_ssurl, $file_dlurl, $file_beta_dlurl, $file_catid,  $file_posticon, $file_status, $file_screen, $file_rss_version, $file_nonrss_version, $file_video, $changetar, $changetar);
		$vt->sor();
		$islem_bilgisi = '<div class="successbox">'.stripslashes($file_name).' isimli dosya sisteme eklenmiştir.</div>';
	}
}
?>
<h2>Dosya Ekle</h2>

<?=$islem_bilgisi?>

<div>
<form name="urunform" action="<?=$acp_dosyalarlink?>&dosyaekle=1" method="POST">
<input type="hidden" name="menu" value="dosyalar">
<input type="hidden" name="dosyaekle" value="1">
<table width="100%" cellspacing="5" cellspacing="10" border="0">
<tr>
	<th colspan="2">
	</th>
	<th>
		<div><input class="button1" id="urunform" name="urunform" value="Düzenle" type="submit"></div>
	</th>
</tr>

<tr>
<td width="350" valign="top">Dosya Durumu</td>
<td>:</td>
<td>
<div>
<select style="width:305px" name="file_status">
<?php
foreach ($array_file_status as $k => $v)
{
	echo '<option value="'.$k.'">'.$v.'</option>'. "\r\n";
}
?>
</select>
</div>
</td>
</tr>


<tr>
<td>Dosya Kategorisi</td>
<td>:</td>
<td>
<div>
<select style="width:305px" name="file_catid">
<option value="">&raquo; Tüm Kategoriler</option>					
<?=$kategoriler_options?>
</select>
</div>
</td>
</tr>

<tr>
<td>Dosya Adı</td>
<td>:</td>
<td>
<div>
<input type="text" name="file_name" value="<?=$file_name?>" style="width: 300px;">
</div>
</td>
</tr>

<tr>
<td>Geliştirici Link</td>
<td>:</td>
<td>
<div>
<input type="text" name="file_creator" value="<?=$file_creator?>" style="width: 300px;">
Posticon: <input type="text" name="file_posticon" style="width: 200px;">
</div>
</td>
</tr>

<tr>
<td>Stabil Versiyon & İndirme Link</td>
<td>:</td>
<td>
<div>
<input type="text" name="file_version" style="width: 100px;">
<input onClick="highlight(this);" type="text" name="file_dlurl" style="width: 465px;">
</div>
</td>
</tr>

<tr>
<td>Beta Versiyon & İndirme Link</td>
<td>:</td>
<td>
<div>
<input type="text" name="file_beta_version" style="width: 100px;">
<input onClick="highlight(this);" type="text" name="file_beta_dlurl" style="width: 465px;">
</div>
</td>
</tr>

<tr>
<td>RSS ile Sürüm Takibi</td>
<td>:</td>
<td>
<div>
<input type="text" name="file_rss_version" style="width: 580px;">
</div>
</td>
</tr>

<tr>
<td>Manuel Sürüm Takibi</td>
<td>:</td>
<td>
<div>
<input type="text" name="file_nonrss_version" style="width: 580px;">
</div>
</td>
</tr>


<tr>
<td>Özet Bilgi</td>
<td>:</td>
<td>
<div>
	<textarea name="file_desc" rows="7" style="width: 580px"></textarea>
</div>
</td>
</tr>

<tr>
<td>Dosya Açıklaması</td>
<td>:</td>
<td>
<div>
	<textarea name="file_longdesc" rows="20" style="width: 580px"></textarea>
</div>
</td>
</tr>

<tr>
<td>Dosya Açıklaması Notları</td>
<td>:</td>
<td>
<div>
	<textarea name="file_longdesc_note" rows="2" style="width: 580px"></textarea>
</div>
</td>
</tr>

<tr>
<td>Dosya Açıklaması Yazarı</td>
<td>:</td>
<td>
<div>
<input type="text" name="file_longdesc_author" value="<?=$file_longdesc_author?>" style="width: 580px;">
</div>
</td>
</tr>

<tr>
<td>Dosya Açıklaması Linki</td>
<td>:</td>
<td>
<div>
<input type="text" name="file_longdesc_link" value="<?=$file_longdesc_link?>" style="width: 580px;">
</div>
</td>
</tr>

<tr>
<td>Ekran Gör, imageshack.us Linki</td>
<td>:</td>
<td>
<div>
<input type="text" name="file_ssurl" style="width: 580px;">
</div>
</td>
</tr>

<tr>
<td>Dosya Video</td>
<td>:</td>
<td>
<div>
	<textarea name="file_video" rows="10" style="width: 580px"></textarea>
</div>
</td>
</tr>

<tr>
<td>Diğer Ekran Görüntüleri</td>
<td>:</td>
<td>
<div>
	<textarea name="file_screen" rows="5" style="width: 580px"></textarea>
</div>
</td>
</tr>

</table>	
</form>
</div>
</div>