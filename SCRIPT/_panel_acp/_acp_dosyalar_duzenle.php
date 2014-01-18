<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

$file_id 	= $_REQUEST["un"]; 			settype($file_id,"integer");
$delete 	= $_REQUEST["delete"]; 		settype($delete,"integer");

if (isset($_REQUEST["urunform"]))
{
	//varsayılan değerler
	$changetar = time();

	if ($file_id > 0)
	{
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
		$file_beta_version 		= addslashes(trim(strip_tags($_REQUEST["file_beta_version"])));
		$file_posticon 			= addslashes(trim(strip_tags($_REQUEST["file_posticon"])));
		$file_creator 			= addslashes(trim(strip_tags($_REQUEST["file_creator"])));
		$file_ssurl 			= addslashes(trim(strip_tags($_REQUEST["file_ssurl"])));
		$file_dlurl 			= addslashes(trim(strip_tags($_REQUEST["file_dlurl"])));
		$file_beta_dlurl 		= addslashes(trim(strip_tags($_REQUEST["file_beta_dlurl"])));
		$file_screen 			= addslashes(trim(strip_tags($_REQUEST["file_screen"])));
		$file_rss_version 		= addslashes(trim(strip_tags($_REQUEST["file_rss_version"])));
		$file_nonrss_version 	= addslashes(trim(strip_tags($_REQUEST["file_nonrss_version"])));
		//tasarım gereği böyle bir saçmalık var
		//kim saçmaladı: tabii ben
		if ($file_catid < 1) $file_catid = '-1';
		
		//hata kontrolü
		if ( strlen($file_name) < 2 or !eregi("[[:alpha:]]",$file_name) )
		$islem_bilgisi = '<br>Dosya Adı alanını boş bırıkmayınız';

		if ( strlen($file_desc) < 2 or !eregi("[[:alpha:]]",$file_desc) )
		$islem_bilgisi .= '<br>Dosya Açıklaması alanını boş bırakmayınız';

		if ($islem_bilgisi == '')
		{
			$vt->sql('UPDATE tv_files SET file_name = %s, file_version = %s, file_beta_version = %s, file_posticon = %s, file_desc = %s, file_longdesc = %s, file_longdesc_note = %s, file_longdesc_author = %s, file_longdesc_link = %s, file_creator = %s, file_ssurl = %s, file_dlurl = %s, file_beta_dlurl = %s, file_catid = %u, file_status = %s, file_screen = %s, file_rss_version = %s, file_nonrss_version = %s, file_video = %s, changetar = %u WHERE file_id = %u');
			$vt->arg($file_name, $file_version, $file_beta_version, $file_posticon, $file_desc, $file_longdesc, $file_longdesc_note, $file_longdesc_author, $file_longdesc_link, $file_creator, $file_ssurl, $file_dlurl, $file_beta_dlurl, $file_catid, $file_status, $file_screen, $file_rss_version, $file_nonrss_version, $file_video, $changetar, $file_id);
			$vt->sor();
			$islem_bilgisi = '<div class="successbox">'.stripslashes($file_name).' dosyasına ait bilgiler güncellenmiştir.</div>';
		}
		else
		{
			$islem_bilgisi = '<div class="errorbox">İşlem Hatası, neden acaba?</div>';
		}
	}
}

//dosya id varsa ve silinmesi isteniyorsa, silinmiş diye işaretliyoruz.
if ($file_id > 0 && $delete == 1)
{
	$vt->sql('UPDATE tv_files SET file_status = 3 WHERE file_id = %u')->arg($file_id)->sor();;
	// $vt->sql('DELETE FROM tv_files WHERE file_id = %u')->arg($file_id)->sor();;
	$islem_bilgisi = '<div class="errorbox">Seçilen ürün silinmiş olarak işaretlenip arşive alınmıştır.</div>';
}

$vt->sql('SELECT file_name, file_desc, file_creator, file_version, file_beta_version, file_longdesc, file_longdesc_note, file_longdesc_author, file_longdesc_link, file_ssurl, file_dlurl, file_beta_dlurl, file_posticon, file_screen, file_rss_version, file_nonrss_version, file_video, file_catid, file_status FROM tv_files WHERE file_id = %u')->arg($file_id)->sor();
$sonuc = $vt->alHepsi();
$adet = $vt->numRows();

//seçmeli gelen alanlar
$file_catid 			= $sonuc[0]->file_catid;
$file_status 			= $sonuc[0]->file_status;

//metin gelmesi gereken alanlar
$file_name 				= $sonuc[0]->file_name;
$file_version 			= $sonuc[0]->file_version;
$file_beta_version 		= $sonuc[0]->file_beta_version;

$file_desc				= $sonuc[0]->file_desc;
$file_longdesc 			= $sonuc[0]->file_longdesc;
$file_longdesc_note 	= $sonuc[0]->file_longdesc_note;
$file_longdesc_author	= $sonuc[0]->file_longdesc_author;
$file_longdesc_link 	= $sonuc[0]->file_longdesc_link;
$file_creator 			= $sonuc[0]->file_creator;
$file_ssurl 			= $sonuc[0]->file_ssurl;
$file_dlurl 			= $sonuc[0]->file_dlurl;
$file_beta_dlurl 		= $sonuc[0]->file_beta_dlurl;
$file_posticon 			= $sonuc[0]->file_posticon;
$file_screen 			= $sonuc[0]->file_screen;
$file_rss_version 		= $sonuc[0]->file_rss_version;
$file_nonrss_version 	= $sonuc[0]->file_nonrss_version;
$file_video 			= $sonuc[0]->file_video;

//metin geldiği için temizlenmesi gereken alanlar
$file_name 				= stripslashes($file_name);
$file_version 			= stripslashes($file_version);
$file_beta_version 		= stripslashes($file_beta_version);
$file_posticon 			= stripslashes($file_posticon);
$file_desc 				= stripslashes($file_desc);
$file_longdesc 			= stripslashes($file_longdesc);
$file_longdesc_note 	= stripslashes($file_longdesc_note);
$file_longdesc_author 	= stripslashes($file_longdesc_author);
$file_longdesc_link 	= stripslashes($file_longdesc_link);
$file_creator 			= stripslashes($file_creator);
$file_ssurl 			= stripslashes($file_ssurl);
$file_beta_dlurl 		= stripslashes($file_beta_dlurl);
$file_screen 			= stripslashes($file_screen);
$file_rss_version 		= stripslashes($file_rss_version);
$file_nonrss_version 	= stripslashes($file_nonrss_version);
$file_video 			= stripslashes($file_video);

if ($file_posticon <> '') 
{
	$file_posticon_path = '<img width="80" src="'.SITELINK.'/posticons/'.$file_posticon.'">';
}
else
{
	$file_posticon_path = '<img width="80" src="'.SITELINK.'/posticons/rules.jpg">';
}

$file_link = SITELINK.'/' . URUNDETAY . '?fid=' . $file_id .'-'. pco_format_url($file_name) ;			
if (SEO_OPEN == 1) $file_link = SITELINK.'/' . pco_format_url($file_name) . '-f' . $file_id . SEO;	
?>
<h2><?=$file_posticon_path?> Dosya Düzenle &raquo; <a href="<?=$file_link?>"><?=$file_name?></a></h2>

<script>
function confirmDelete(delUrl)
{
if (confirm("<?=$file_name?> isimli bu dosyayı silinmiş olarak işaretlemek istediğinizden emin misiniz?"))
{
document.location = delUrl;
}
}
</script>

<script type="text/javascript">
function highlight(field)
{
field.focus();
field.select();
}
</script>

<?=$islem_bilgisi?>

<div>
<form name="urunform" action="<?=$acp_dosyalarlink?>&un=<?=$file_id?>" method="POST">
<input type="hidden" name="menu" value="dosyalar">
<input type="hidden" name="islem" value="guncelle">
<input type="hidden" name="file_id" value="<?=$file_id?>">

<table width="100%" cellspacing="5" cellspacing="10" border="0">
<tr>
	<th colspan="2">
		<a class="button1" href="javascript:confirmDelete('<?=$acp_dosyalarlink?>&amp;un=<?=$file_id?>&amp;delete=1')">X</a>
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
<option value="<?=$file_status?>">&raquo; <?=$array_file_status[$file_status]?></option>					
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
<option value="<?=$file_catid?>">&raquo; <?=$array_kategorilistesi[$file_catid]["cat_name"]?></option>					
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
Posticon: <input type="text" name="file_posticon" value="<?=$file_posticon?>" style="width: 200px;">
</div>
</td>
</tr>

<tr>
<td>Stabil Versiyon & İndirme Link</td>
<td>:</td>
<td>
<div>
<input type="text" name="file_version" value="<?=$file_version?>" style="width: 100px;">
<input onClick="highlight(this);" type="text" name="file_dlurl" value="<?=$file_dlurl?>" style="width: 465px;">
</div>
</td>
</tr>

<tr>
<td>Beta Versiyon & İndirme Link</td>
<td>:</td>
<td>
<div>
<input type="text" name="file_beta_version" value="<?=$file_beta_version?>" style="width: 100px;">
<input onClick="highlight(this);" type="text" name="file_beta_dlurl" value="<?=$file_beta_dlurl?>" style="width: 465px;">
</div>
</td>
</tr>

<tr>
<td>RSS ile Sürüm Takibi</td>
<td>:</td>
<td>
<div>
<input type="text" name="file_rss_version" value="<?=$file_rss_version?>" style="width: 580px;">
</div>
</td>
</tr>

<tr>
<td>Manuel Sürüm Takibi</td>
<td>:</td>
<td>
<div>
<input type="text" name="file_nonrss_version" value="<?=$file_nonrss_version?>" style="width: 580px;">
</div>
</td>
</tr>

<tr>
<td>Özet Bilgi</td>
<td>:</td>
<td>
<div>
	<textarea name="file_desc" rows="7" style="width: 580px"><?=$file_desc?></textarea>
</div>
</td>
</tr>

<tr>
<td>Dosya Açıklaması</td>
<td>:</td>
<td>
<div>
	<textarea name="file_longdesc" rows="20" style="width: 580px"><?=$file_longdesc?></textarea>
</div>
</td>
</tr>

<tr>
<td>Dosya Açıklaması Notları</td>
<td>:</td>
<td>
<div>
	<textarea name="file_longdesc_note" rows="2" style="width: 580px"><?=$file_longdesc_note?></textarea>
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
<input type="text" name="file_ssurl" value="<?=$file_ssurl?>" style="width: 580px;">
</div>
</td>
</tr>

<tr class="col2">
<td>Ekran Görüntüsü</td>
<td>:</td>
<td>
<div>
<img width="585" src="<?=$file_ssurl?>">
</div>
</td>
</tr>

<tr class="col1">
<td>Dosya Video</td>
<td>:</td>
<td>
<div>
	<textarea name="file_video" rows="10" style="width: 580px"><?=$file_video?></textarea>
</div>
<br>
<div>
	<?=$file_video?>
</div>
</td>
</tr>

<tr>
<td>Diğer Ekran Görüntüleri veya Notlar</td>
<td>:</td>
<td>
<div>
	<textarea name="file_screen" rows="5" style="width: 580px"><?=$file_screen?></textarea>
</div>
</td>
</tr>
 
</table>	
</form>
</div>
</div>