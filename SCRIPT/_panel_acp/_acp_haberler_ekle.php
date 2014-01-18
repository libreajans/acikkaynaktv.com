<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

if (isset($_REQUEST["newsform"]))
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
		//dış haber ekliyoruz
		$vt->sql('INSERT INTO tv_news ( news_fileid, news_link, news_title, news_desc, news_text, news_status, createtar, changetar ) VALUES ( %s, %s, %s, %s, %s, %s, %u, %u )');
		$vt->arg($news_fileid, $news_link, $news_title, $news_desc, $news_text, $news_status, $changetar, $changetar)->sor();		
		$islem_bilgisi = '<div class="successbox">'.stripslashes($news_title).' isimli haber sisteme eklenmiştir.</div>';
	}
}
?>

<form name="urunform" action="<?=$acp_haberlerlink?>&amp;haber_ekle=1" method="POST">
<input type="hidden" name="menu" value="haberler">
<input type="hidden" name="islem" value="haber_ekle">

<h1>HABER EKLE</h1>
<p>Bu paneli kullanarak, sitenizin haberler bölümünde görüntülenmek üzere dış bağlantılı özet haberler ve metin haberler yayınlayabilirsiniz.</p>
<?=$islem_bilgisi ?>

<table valign="top" width="100%" cellspacing="5" border="0">
<tr>
<td width="300">Haber Tipi</td>
<td>:</td>
<td colspan="3">
<select style="width: 305px;" name="news_status">
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
<?php
	echo $option_file_list
?>
</select>
</td>
</tr>

<tr><td>Haber Başlık</td><td> : </td><td><input type="text" name="news_title" style="width: 500px"> <font color="red">*</font></td></tr>
<tr><td>Haber Açıklama</td><td> : </td><td><textarea rows="4" name="news_desc" style="width: 500px"></textarea><font color="red"></font></td></tr>
<tr><td>Haber Link</td><td> : </td><td><input type="text" name="news_link" style="width: 500px"> <font color="red">*</font></td></tr>			
<tr><td>Haber Metin</td><td> : </td><td><textarea rows="17" name="news_text" style="width: 500px"></textarea><font color="red"></font></td></tr>
<tr><td></td><td></td><td>
<input class="button1" value="TEMİZLE" type="reset">
<input class="button1" id="newsform" name="newsform" value="HABER EKLE" type="submit"></td></tr>			
</table>
</form>
<pre>
* Kırmızı işaretli alanların doldurulması zorunludur.
</pre>
</div>