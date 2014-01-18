<?php
	if (!defined('yakusha')) die('...');
	if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

	if (isset($_REQUEST["kategoriform"]))
	{
		//varsayılan değerler
		$changetar 		= time();
		//metin gelmesi gereken alanlar
		$cat_name 		= addslashes(trim($_REQUEST["cat_name"]));
		$cat_desc 		= addslashes(trim($_REQUEST["cat_desc"]));
		$cat_icon 		= addslashes(trim($_REQUEST["cat_icon"]));
		//sayı gelmesi gereken alanlar
		$cat_parent 	= $_REQUEST["cat_parent"]; settype($cat_parent,"integer");
		$cat_order 		= $_REQUEST["cat_order"]; settype($cat_order,"integer");

		//HATA KONTROLÜ
		if ( strlen($cat_name) < 2 or !eregi("[[:alpha:]]",$cat_name) )
		$islem_bilgisi = '<div class="errorbox">Kategori Adı alanını boş bırakmayınız.</div>';

		if ($islem_bilgisi == '')
		{
			$vt->sql('INSERT INTO tv_cat (cat_name, cat_desc, cat_icon, cat_parent, cat_order, createtar, changetar ) VALUES ( %s, %s, %s, %u, %u, %u, %u )');
			$vt->arg($cat_name, $cat_desc, $cat_icon, $cat_parent, $cat_order, $changetar, $changetar)->sor();
			$islem_bilgisi = '<div class="successbox">'.stripslashes($cat_name).' isimli kategori sisteme eklenmiştir.</div>';			
		}
		//bellekteki kategori bilgileri temizleniyor
		pco_metatablosu_temizle();
	}
?>

<form name="kategoriform" action="<?=$acp_kategorilerlink?>&amp;kategoriekle=1" method="POST">
<input type="hidden" name="menu" value="kategoriler">
<input type="hidden" name="islem" value="kategoriekle">
<input type="hidden" name="kategoriekle" value="1">

<h1>Yeni Kategori Ekle</h1>

<?=$islem_bilgisi ?>


<table valign="top" width="100%" cellspacing="3" border="0">
<tr class="col1">
<th colspan="3">
TEMEL BİLGİLER
</th>
</tr>
<tr><td width="150" height="30">Kategori Adı </td><td> : </td><td><div><input type="text" name="cat_name" style="width: 250px"> <font color="red">*</font></div></td></tr>
<tr><td height="30">Kategori Açıklaması </td><td> : </td><td><div><input type="text" name="cat_desc" style="width: 250px"></div></td></tr>

<tr><td height="30">Üst Kategori </td><td> : </td>
	<td>
		<select style="width: 255px;" name="cat_parent">
			<option value="">-- üst kategori, gerekliyse seçiniz --</option>
			<?=$kategoriler_options ?>
		</select>					
	</td>
</tr>

<tr><td height="30">Kategori İkon </td><td> : </td><td><div><input type="text" name="cat_icon" style="width: 250px"> </div></td></tr>

<tr><td height="30">Kategori Sıralaması </td><td> : </td><td><div><input type="text" name="cat_order" style="width: 50px"></div></td></tr>

<tr>
<td colspan="3">
<input class="button1" id="kategoriform" name="kategoriform" value="KATEGORİ EKLE" type="submit">
</td>
</tr>
</table>

</form>
<pre>
* Kırmızı işaretli alanların doldurulması zorunludur.
</pre>