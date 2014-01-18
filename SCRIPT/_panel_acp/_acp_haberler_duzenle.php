<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

$news_id 	= $_REQUEST["un"];		settype($news_id,"integer");
$delete 	= $_REQUEST["delete"];	settype($delete,"integer");

if ($_REQUEST["newsform"])
{
	//varsayılan değerler
	$changetar = time();

	if ($news_id > 0)
	{
		$changetar = time();
		$news_fileid 	= $_REQUEST["news_fileid"]; 	settype($news_fileid,"integer");
		$news_status 	= $_REQUEST["news_status"]; 	settype($news_status,"integer");
		//metin gelmesi gereken alanlar
		$news_link 		= addslashes(trim($_REQUEST["news_link"]));
		$news_title 	= addslashes(trim($_REQUEST["news_title"]));
		$news_desc 		= addslashes(trim($_REQUEST["news_desc"]));
		$news_text 		= addslashes(trim($_REQUEST["news_text"]));

		//yapı gereği, dosya id seçilmemişse -1 nolu dosyaya atıyoruz
		//bu da harici konular demek oluyor
		if ( $news_fileid < 1) $news_fileid = '-1';	

		//HATA KONTROLÜ
		if ( strlen($news_title) < 2 or !eregi("[[:alpha:]]",$news_title) )
		$islem_bilgisi = '<div class="errorbox">Başlık alanını boş bırıkmayınız.</div>';

		if ($islem_bilgisi == '')
		{
	 		$vt->sql('UPDATE tv_news SET news_fileid = %u, news_status = %u, news_link = %s, news_title = %s, news_desc = %s, news_text = %s, changetar = %u WHERE news_id = %u');
			$vt->arg($news_fileid, $news_status, $news_link, $news_title, $news_desc, $news_text, $changetar, $news_id)->sor(); 
			$islem_bilgisi = '<div class="successbox">'.stripslashes($news_title).' isimli haber güncellenmiştir.</div>';
		}
		else
		{
			$islem_bilgisi = '<div class="errorbox">İşlem Hatası, neden acaba?</div>';
		}
	}
}

//haber id varsa ve silinmesi isteniyorsa, silinmiş diye işaretliyoruz.
if ($news_id > 0 && $delete == 1)
{
	$vt->sql('UPDATE tv_news SET news_status = 100 WHERE news_id = %u')->arg($news_id)->sor();;
	$islem_bilgisi = '<div class="errorbox">Seçilen haber silinmiş olarak işaretlenip arşive alınmıştır.</div>';
}

$vt->sql('SELECT news_fileid, news_status, news_link, news_title, news_desc, news_text FROM tv_news WHERE news_id = %u')->arg($news_id)->sor();
$sonuc = $vt->alHepsi();
$adet = $vt->numRows();

//seçmeli gelen alanlar
$news_fileid 	= $sonuc[0]->news_fileid;
$news_status 	= $sonuc[0]->news_status;

//metin gelmesi gereken alanlar
$news_link 		= $sonuc[0]->news_link;
$news_title 	= $sonuc[0]->news_title;
$news_desc 		= $sonuc[0]->news_desc;
$news_text 		= $sonuc[0]->news_text;

//metin geldiği için temizlenmesi gereken alanlar
$news_link 		= stripslashes($news_link);
$news_title 	= stripslashes($news_title);
$news_desc 		= stripslashes($news_desc);
$news_text 		= stripslashes($news_text);
?>

<h1>Haber Düzenle &raquo; <a href="<?=HABERLERLINK?>"> <?=$news_title?></a></h1>

<script>
function confirmDelete(delUrl)
{
if (confirm("<?=$news_title?> isimli haberi silinmiş olarak işaretlemek istediğinizden emin misiniz?"))
{
document.location = delUrl;
}
}
</script>

<?=$islem_bilgisi?>

<form name="urunform" action="<?=$acp_haberlerlink?>&amp;un=<?=$news_id?>" method="POST">
<input type="hidden" name="menu" value="haberler">
<input type="hidden" name="islem" value="haber_duzenle">
<input type="hidden" name="un" value="<?=$news_id?>">

<table width="100%" valign="top" cellspacing="5" border="0">
<table width="100%" cellspacing="5" cellspacing="10" border="0">

<tr>
	<th colspan="3" height="25">
		<a class="button1" href="javascript:confirmDelete('<?=$acp_haberlerlink?>&amp;un=<?=$news_id?>&amp;delete=1')">HABER SİL</a>
	</th>
</tr>


<tr>
<td width="300">Haber Tipi</td>
<td>:</td>
<td colspan="3">
<select style="width: 305px;" name="news_status">
<option value="<?=$news_status?>"> &raquo; <?=$array_news_status[$news_status]?></option>
<?php
	echo $option_news_status
?>
</select>
</td>
</tr>
<tr>
<td>İlişkili Yazılım</td>
<td>:</td>
<td colspan="3">
<select style="width: 305px;" name="news_fileid">
<option value="<?=$news_fileid?>"> &raquo; <?=$array_file_list[$news_fileid]?></option>
<?php
	echo $option_file_list
?>
</select>
</td>
</tr>

<tr><td>Haber Başlık</td><td> : </td><td><input type="text" name="news_title" value="<?=$news_title ?>" style="width: 500px"> <font color="red">*</font></td></tr>
<tr><td>Haber Açıklama</td><td> : </td><td><textarea rows="4" name="news_desc" style="width: 500px"><?=$news_desc ?></textarea><font color="red"></font></td></tr>			
<tr><td>Haber Link</td><td> : </td><td><input type="text" name="news_link" value="<?=$news_link ?>" style="width: 500px"> <font color="red">*</font></td></tr>			
<tr><td>Haber Metin</td><td> : </td><td><textarea rows="17" name="news_text" style="width: 500px"><?=$news_text ?></textarea><font color="red"></font></td></tr>			
<tr><td></td><td></td><td>
<input class="button1" value="TEMİZLE" type="reset">
<input class="button1" id="newsform" name="newsform" value="HABER DÜZENLE" type="submit"></td></tr>			
</table>
</form>
<pre>
* Kırmızı işaretli alanların doldurulması zorunludur.
</pre>
</div>