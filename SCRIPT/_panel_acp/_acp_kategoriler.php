<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"]==1) exit ();

include($siteyolu."/_panel_acp/_temp/_t_adminbaslangic.php"); 

$kategori_id		= $_REQUEST['un']; 				settype($kategori_id,"integer");
$kategori_ekle 		= $_REQUEST['kategoriekle']; 	settype($kategori_ekle,"integer");
if ($kategori_id > 0)
{
	include($siteyolu."/_panel_acp/_acp_kategoriler_duzenle.php");
}
elseif ($kategori_ekle > 0)
{
	include($siteyolu."/_panel_acp/_acp_kategoriler_ekle.php");
}
else
{

	//toplu güncelleme için gereken değerleri alıyoruz
 	$cat_order 		= $_REQUEST["cat_order"];
 	$cat_parent 	= $_REQUEST["cat_parent"];
 	$cat_name 		= $_REQUEST["cat_name"];
 	$cat_desc 		= $_REQUEST["cat_desc"];
 	$cat_forum 		= $_REQUEST["cat_forum"];
	//formla ilgili öncelikli işlemler varsa tamamlıyoruz
 	if (isset ($_REQUEST["topluduzenle"]))
	{
		//kategori forum bilgilerini güncelliyoruz
		foreach ($cat_forum as $k => $v)
		{
			settype($v,"integer");
			$vt->sql('UPDATE tv_cat SET cat_forum = %u WHERE cat_id = %u')->arg($v,$k)->sor();
		}

		//kategori sıralaması bilgilerini güncelliyoruz
		foreach ($cat_order as $k => $v)
		{
			settype($v,"integer");
			$vt->sql('UPDATE tv_cat SET cat_order = %u WHERE cat_id = %u')->arg($v,$k)->sor();
		}

		//kategori parent bilgilerini güncelliyoruz
		foreach ($cat_parent as $k => $v)
		{
			settype($v,"integer");
			$vt->sql('UPDATE tv_cat SET cat_parent = %u WHERE cat_id = %u')->arg($v,$k)->sor();
		}

		//kategori isim bilgilerini güncelliyoruz
		foreach ($cat_name as $k => $v)
		{
			$v = addslashes(trim($v));
			$vt->sql('UPDATE tv_cat SET cat_name = %s WHERE cat_id = %u')->arg($v,$k)->sor();
		}

		//kategori açıklama bilgilerini güncelliyoruz
		foreach ($cat_desc as $k => $v)
		{
			$v = addslashes(trim($v));
			$vt->sql('UPDATE tv_cat SET cat_desc = %s WHERE cat_id = %u')->arg($v,$k)->sor();
		}		
		$islemsonucu = '<div class="successbox">İşlem Başarı İle Tamamlandı.</div>';
		//bellekteki kategori bilgileri temizleniyor
		pco_metatablosu_temizle();
	}

	//normal form alanına dönüyoruz
	//sql sorgusu oluşturuluyor
	$vt->sql('SELECT * FROM tv_cat WHERE cat_parent = 0 ORDER BY cat_order ASC')->sor();
	$sonuc = $vt->alHepsi();
	$adet = $vt->numRows();

	if ($adet)
	{
		$iii = 1;
		for ( $i = 0; $i < $adet; $i++)
		{
			//sorgudan alınıyor
			$cat_id 		= $sonuc[$i]->cat_id;
			$cat_name 		= $sonuc[$i]->cat_name;
			$cat_desc 		= $sonuc[$i]->cat_desc;
			$cat_order 		= $sonuc[$i]->cat_order;
			$cat_parent 	= $sonuc[$i]->cat_parent;
			$cat_icon 		= $sonuc[$i]->cat_icon;
			$cat_forum 		= $sonuc[$i]->cat_forum;

			if ($iii%2)  $trcolor = "col2";  else  $trcolor = "col1";

			$sayfabilgisi.= '
			<tr class="'.$trcolor.'">
				<td>
					<a class="vitrinler" title="kategori bilgilerini düzenle" href="'.$acp_kategorilerlink.'&amp;un='.$cat_id.'">
					<img src="'.SITELINK.'/_img/icon_edit.gif"></a>
				</td>
				<td>
				<img src="'.PHOTOLINK.$cat_icon.'">
				</td>
				<td>
					<div>
						<input style="width: 35px;" type="text" name="cat_order['.$cat_id.']" value="'.$cat_order.'">
						<input style="width: 200px;" type="text" name="cat_name['.$cat_id.']" value="'.$cat_name.'">
						<a class="vitrinler" href="'.ANASAYFALINK.'?cat='.$cat_id.'"><img src="'.SITELINK.'/_img/icon_right.png"></a>
					 </div>
				</td>
				<td>
					<div><input style="width: 250px;" type="text" name="cat_desc['.$cat_id.']" value="'.$cat_desc.'"></div>
				</td>
				<td>
					<div>
						<select style="width: 200px;" name="cat_parent['.$cat_id.']">
							<option value="'.$cat_parent.'">'.$array_kategorilistesi[$cat_parent]["cat_name"].'</option>
							'.$kategoriler_options.'
						</select>
					</div>
				</td>
				<td>
					<div><input style="width: 35px;" type="text" name="cat_forum['.$cat_id.']" value="'.$cat_forum.'"></div>
				</td>					
			</tr>';

			//sql sorgusu oluşturuluyor
			$vt->sql('SELECT * FROM tv_cat WHERE cat_parent = %u ORDER BY cat_order ASC')->arg($cat_id)->sor();
			$altsonuc = $vt->alHepsi();
			$icbulunanadet = $vt->numRows();

			if ($icbulunanadet)
			{
				for ( $ii = 0; $ii < $icbulunanadet; $ii++)
				{
					$iii++;
					//sorgudan alınıyor
					$alt_cat_id 		= $altsonuc[$ii]->cat_id;
					$alt_cat_name 		= $altsonuc[$ii]->cat_name;
					$alt_cat_desc 		= $altsonuc[$ii]->cat_desc;
					$alt_cat_order 		= $altsonuc[$ii]->cat_order;
					$alt_cat_parent 	= $altsonuc[$ii]->cat_parent;
					$alt_cat_icon 		= $altsonuc[$ii]->cat_icon;
					$alt_cat_forum 		= $altsonuc[$ii]->cat_forum;

					if ($iii%2) $trcolor = "col2";  else  $trcolor = "col1";

					$sayfabilgisi.= '
					<tr class="'.$trcolor.'">
						<td><a class="vitrinler" title="kategori bilgilerini düzenle" href="'.$acp_kategorilerlink.'&amp;un='.$alt_cat_id.'">
						<img src="'.SITELINK.'/_img/icon_edit.gif"></a></td>
						<td>
						<img src="'.PHOTOLINK.$alt_cat_icon.'">
						</td>						
						<td>
							<div>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input style="width: 35px;" type="text" name="cat_order['.$alt_cat_id.']" value="'.$alt_cat_order.'">
								<input style="width: 200px;" type="text" name="cat_name['.$alt_cat_id.']" value="'.$alt_cat_name.'">
								<a class="vitrinler" href="'.ANASAYFALINK.'?cat='.$alt_cat_id.'"><img src="'.SITELINK.'/_img/icon_right.png"></a>
							</div>
						</td>
						<td>
							<div><input style="width: 250px;" type="text" name="cat_desc['.$alt_cat_id.']" value="'.$alt_cat_desc.'"></div>
						</td>
						<td>
							<div>
								<select style="width: 200px;" name="cat_parent['.$alt_cat_id.']">
									<option value="'.$alt_cat_parent.'">'.$array_kategorilistesi[$alt_cat_parent]["cat_name"].'</option>
									'.$kategoriler_options.'
								</select>
							</div>
						</td>
						<td>
							<div><input style="width: 35px;" type="text" name="cat_forum['.$alt_cat_id.']" value="'.$alt_cat_forum.'"></div>
						</td>					
					</tr>';
				}
			}
			$iii++;
		}
	}
?>

<a class="button1" href="<?=$acp_kategorilerlink?>&kategoriekle=1"><img src="<?=SITELINK?>/_img/icon_ekle.png">KATEGORİ EKLE</a>

Bu paneli kullanarak kategori bilgilerinizi güncelleyebilir ve silebilirsiniz.<br><br>

<?=$islemsonucu?>

<form name="topluduzenle" action="<?=$acp_kategorilerlink?>" method="POST">
<input type="hidden" name="menu" value="kategoriler">
<input type="hidden" name="topluduzenle" value="1">
<table class="vitrinler" width="%100" border="0" cellpadding="3" cellspacing="3">
<tr>
<th width="1"></th>
<th width="1"></th>
<th width="350">Kategori Adı</th>
<th>Kategori Açıklaması</th>
<th width="200">Üst Kategorisi</th>
<th width="1">Fid</th>
</tr>
<?=$sayfabilgisi?>
<tr>
	<td colspan="12"><div align="right"><input class="button1" type="submit" name="topluduzenle" value="TOPLU DÜZENLE"></div></td>
</tr>
</table>

<?php } ?>		
<?php include($siteyolu."/_panel_acp/_temp/_t_adminbitis.php"); ?>