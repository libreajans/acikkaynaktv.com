<?php
	if (!defined('yakusha')) die('...');
	if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

	$cat_id = $_REQUEST["un"];
	$delete = $_REQUEST["delete"];

	if (isset($_REQUEST["kategoriform"]))
	{
		//metin gelmesi gereken alanlar
		$cat_name 		= addslashes(trim($_REQUEST["cat_name"]));
		$cat_desc 		= addslashes(trim($_REQUEST["cat_desc"]));
		$cat_icon 		= addslashes(trim($_REQUEST["cat_icon"]));
		$cat_parent 	= $_REQUEST["cat_parent"]; settype($cat_parent,"integer");
		$cat_order 		= $_REQUEST["cat_order"]; settype($cat_order,"integer");
		$cat_status 	= $_REQUEST["cat_status"]; settype($cat_status,"integer");

		//HATA KONTROLÜ
		if ( strlen($cat_name) < 2 or !eregi("[[:alpha:]]",$cat_name) )
		$islem_bilgisi = '<div class="errorbox">Kategori Adı alanını boş bırakmayınız.</div>';

		if ($islem_bilgisi == '')
		{
			$vt->sql('UPDATE tv_cat SET cat_name = %s, cat_desc = %s, cat_icon = %s, cat_parent = %u, cat_order = %u, cat_status = %u WHERE cat_id = %u');
			$vt->arg($cat_name, $cat_desc, $cat_icon, $cat_parent, $cat_order, $cat_status, $cat_id)->sor();
			$islem_bilgisi = '<div class="successbox">Kategori bilgileri güncellenmiştir.</div>';
		}
		//bellekteki kategori bilgileri temizleniyor
		pco_metatablosu_temizle();
	}

	$vt->sql('SELECT cat_id, cat_name, cat_desc, cat_icon, cat_parent, cat_order, cat_status FROM tv_cat WHERE cat_id = %u')->arg($cat_id)->sor();
	$sonuc = $vt->alHepsi();

	$cat_id 		= $sonuc[0]->cat_id;
	$cat_name 		= $sonuc[0]->cat_name;
	$cat_desc 		= $sonuc[0]->cat_desc;
	$cat_icon 		= $sonuc[0]->cat_icon;
	$cat_parent 	= $sonuc[0]->cat_parent;
	$cat_order 		= $sonuc[0]->cat_order;
	$cat_status 	= $sonuc[0]->cat_status;
	//temizlemeler
	$cat_name 		= stripslashes($cat_name);
	$cat_desc 		= stripslashes($cat_desc);
?>

<form name="kategoriform" action="<?=$acp_kategorilerlink?>&amp;un=<?=$cat_id?>" method="POST">
<input type="hidden" name="menu" value="kategoriler">
<input type="hidden" name="islem" value="duzenle">
<input type="hidden" name="cat_id" value="<?=$cat_id?>">

<h1>Kategori Düzenle &raquo; <?=$cat_name?> <img src="<?=PHOTOLINK?><?=$cat_icon?>"></h1>

<?=$islem_bilgisi ?>


<table valign="top" width="100%" cellspacing="3" border="0">
<tr class="col1">
<th colspan="3">
TEMEL BİLGİLER
</th>
</tr>


<tr><td width="150" height="30">Kategori Adı </td><td> : </td><td><div><input type="text" name="cat_name" style="width: 250px" value="<?=$cat_name?>"> <font color="red">*</font></div></td></tr>
<tr><td height="30">Kategori Açıklaması </td><td> : </td><td><div><input type="text" name="cat_desc" style="width: 250px" value="<?=$cat_desc?>"> </div></td></tr>

<tr><td height="30">Üst Kategori </td><td> : </td>
<td>
	<select style="width: 255px;" name="cat_parent">
		<option value="<?=$cat_parent ?>"> <?=$array_kategorilistesi[$cat_parent]["cat_name"]?></option>
		<?=$kategoriler_options ?>
	</select>
</td>
</tr>

<tr><td height="30">Kategori İkon </td><td> : </td><td><div><input type="text" name="cat_icon" style="width: 250px" value="<?=$cat_icon?>"> </div></td></tr>

<tr><td height="30">Kategori Sıralaması </td><td> : </td><td><div><input type="text" name="cat_order" style="width: 50px" value="<?=$cat_order?>"> </div></td></tr>

<tr>
<td colspan="3">
<input class="button1" id="kategoriform" name="kategoriform" value="KATEGORİ BİLGİLERİNİ DÜZENLE" type="submit">
</td>
</tr>
</table>

</form>
<pre>
* Kırmızı işaretli alanların doldurulması zorunludur.
</pre>