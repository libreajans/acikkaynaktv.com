<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"]==1) exit ();

include($siteyolu."/_panel_acp/_temp/_t_adminbaslangic.php"); 

$file_id = $_REQUEST['un']; settype($file_id,"integer");
$dosya_ekle = $_REQUEST['dosyaekle']; settype($dosya_ekle,"integer");
if ($file_id > 0)
{
	// include($siteyolu."/_panel_acp/_temp/_t_adminmenuleri.php");
	// echo '</div><div id="main">';
	include($siteyolu."/_panel_acp/_acp_dosyalar_duzenle.php");
}
elseif ($dosya_ekle > 0)
{
	// include($siteyolu."/_panel_acp/_temp/_t_adminmenuleri.php");
	// echo '</div><div id="main">';
	include($siteyolu."/_panel_acp/_acp_dosyalar_ekle.php");
}
else
{
	//reset
	$siralama = '';
	$sorguilavesi = '';

	//form için gereken değişkenler alınıyor
	$listetipi 		= $_REQUEST['lt'];
	$limit 			= $_REQUEST['limit'];
	$aramaanahtari 	= $_REQUEST['aramaanahtari'];
	$aramatipi 		= $_REQUEST['aramatipi'];
	$filecat 		= $_REQUEST['filecat'];
	$siralamatipi	= $_REQUEST['order'];
	$by 			= $_REQUEST['by'];
	if ($by == 0) 
	{
		$by = 0;
		$order_by = 'ASC';
	}
	else
	{
		$by = 1;
		$order_by = 'DESC';
	}
	//gelen değerleri düzenliyoruz
	$aramaanahtari 	= htmlspecialchars($aramaanahtari);
	$aramatipi 		= htmlspecialchars($aramatipi);
	$siralamatipi 	= htmlspecialchars($siralamatipi);
	
	//toplu güncelleme için gereken değerleri alıyoruz
	$file_catid 	= $_REQUEST["file_catid"]; 

	//formla ilgili öncelikli işlemler varsa tamamlıyoruz
 	if (isset ($_REQUEST["topluduzenle"]))
	{
		//gerekirse kategori bilgilerini toplu güncelliyoruz
		foreach ($file_catid as $k => $v)
		{
			$v = addslashes(trim(strip_tags($v)));
			$vt->sql('UPDATE tv_files SET file_catid = %u WHERE file_id = %u')->arg($v,$k)->sor();
		}
		$islemsonucu = '<div class="successbox">İşlem Başarı İle Tamamlandı.</div>';
	}

	//formu oluşturmaya başlıyoruz
	
	//arama tipleri oluşturuluyor
	if ($aramatipi == "filename") $siralamatipi = "filename";
	
	//iç sıralamalar oluşturuluyor
	if ($siralamatipi == "filename" && $filecat > 0)
	{
		$sorguilavesi = 'AND file_catid = '.$filecat.' AND file_name LIKE "%'.$aramaanahtari.'%"';
		$siralama = 'file_name '.$order_by;
	}
	else if ($siralamatipi == "filename" && $filecat == "")
	{
		$sorguilavesi = 'AND file_name LIKE "%'.$aramaanahtari.'%"';
		$siralama = 'file_name '.$order_by;
	}
	else if ($siralamatipi == "time")
	{
		$siralama = 'changetar '.$order_by.' , file_name ASC';	
	}
	else if ($siralamatipi == 'version')
	{
		$siralama = 'file_version '.$order_by.', file_name ASC';
	}
	else if ($siralamatipi == 'dlurl')
	{
		$siralama = 'file_dlurl '.$order_by.', file_name ASC';
	}
	else if ($siralamatipi == 'creator')
	{
		$siralama = 'file_creator '.$order_by.', file_name ASC';
	}
	else if ($siralamatipi == 'catid')
	{
		$siralama = 'file_catid '.$order_by.', file_name ASC';
	}
	else if ($siralamatipi == 'rss')
	{
		$siralama = 'file_rss_version '.$order_by.', file_nonrss_version ASC, file_name ASC';
	}
	else if ($siralamatipi == 'status')
	{
		$siralama = 'file_status '.$order_by.', file_name ASC';
	}
	else
	{
		$siralama = 'changetar DESC';	
	}
	
	//sıralama tipi oluşturuluyor
	if (!$siralama) $siralama = 'file_name '.$order_by;

	//limit sorgusu oluşturuluyor
	$sayfasonucmiktari = 10;
	
	if ($limit > 0)
	{ 
		$limitsorgusu = "limit ".($limit*$sayfasonucmiktari ).",".$sayfasonucmiktari;
	} 
	else
	{ 
		$limitsorgusu = "limit 0,".$sayfasonucmiktari ;
	}

	if ($limit == "hepsi") $limitsorgusu = '';
	
	//sayım için sorgu gönderiliyor
	$vt->sql('SELECT count(file_id) FROM tv_files WHERE file_id > 0 '.$sorguilavesi.' ORDER BY '.$siralama.';')->sor();
	$sayim = $vt->alTek();
	$sayi = ($sayim /$sayfasonucmiktari);

	//sayfalama özelliği başlatılıyor
	if($sayi > 1)
	{
		$sayfalama = '<div class="successbox">Sayfalar: ';
		for ($i = 0; $i < $sayi; $i++)
		{
			$sayfalama.= '<a href="'.$acp_dosyalarlink.'';
			if ($listetipi)
			{
				$sayfalama.='&amp;lt='.$listetipi;
			}
			if ($siralamatipi)
			{
				$sayfalama.='&amp;order='.$siralamatipi;
			}
			if ($by)
			{
				$sayfalama.='&amp;by='.$by;
			}
			if ($aramaanahtari)
			{
				$sayfalama.='&amp;aramaanahtari='.$aramaanahtari;
			}
			if ($filecat)
			{
				$sayfalama.='&amp;filecat='.$filecat;
			}
			$sayfalama.= ($limit == $i && $limit <> "hepsi") ? '&amp;limit='.$i.'"><strong> '.($i+1).' </strong></a> |' : '&amp;limit='.$i.'"> '.($i+1).' </a> |';		
		}
		$sayfalama.= ' <a href="'.$acp_dosyalarlink.'&amp;lt='.$listetipi;
		if ($siralamatipi)
		{
			$sayfalama.='&amp;order='.$siralamatipi;
		}
		if ($by)
		{
			$sayfalama.='&amp;by='.$by;
		}
		if ($aramaanahtari)
		{
			$sayfalama.='&amp;aramaanahtari='.$aramaanahtari;
		}
		if ($filecat)
		{
			$sayfalama.='&amp;filecat='.$filecat;
		}
		$sayfalama.= '&limit=hepsi">';
		$sayfalama.= ($limit == "hepsi") ? 'Hepsi ' : 'Hepsi';
		$sayfalama.= '</a> | ('.$sayim.' adet)';
		$sayfalama.= "</div>";
	}

	$sayfalink = $acp_dosyalarlink;
	if ($listetipi)
	{
		$sayfalink.='&amp;lt='.$listetipi;
	}
	if ($aramaanahtari)
	{
		$sayfalink.='&amp;aramaanahtari='.$aramaanahtari;
	}
	if ($filecat)
	{
		$sayfalink.='&amp;filecat='.$filecat;
	}
	
	//sql sorgusu oluşturuluyor
	$vt->sql('SELECT file_id, file_name, file_desc, file_creator, file_version, file_longdesc, file_ssurl, file_dlurl, file_catid, file_posticon, file_rss_version, file_nonrss_version, file_status, createtar, changetar, file_screen FROM tv_files WHERE file_id > 0 '.$sorguilavesi.' ORDER BY '.$siralama.' '.$limitsorgusu.';')->sor();
	$sonuc = $vt->alHepsi();
	$adet = $vt->numRows();

	//sayfa içi oluşturuluyor, döne döne
	if ($adet)
	{
		for ( $i = 0; $i < $adet; $i++)
		{
			$file_id 				= $sonuc[$i]->file_id;
			$file_posticon 			= $sonuc[$i]->file_posticon;
			$file_name 				= $sonuc[$i]->file_name;
			$file_version 			= $sonuc[$i]->file_version;
			$file_dlurl 			= $sonuc[$i]->file_dlurl;
			$file_creator 			= $sonuc[$i]->file_creator;
			$file_desc 				= $sonuc[$i]->file_desc;
			$file_ssurl 			= $sonuc[$i]->file_ssurl;
			$file_catid 			= $sonuc[$i]->file_catid;
			$file_rss_version 		= $sonuc[$i]->file_rss_version;	
			$file_nonrss_version 	= $sonuc[$i]->file_nonrss_version;	
			$file_status 			= $sonuc[$i]->file_status;

			$file_status_icon = '<img src="'.SITELINK.'/_img/'.$array_file_status_icon[$file_status].'">';

			//renklendirme
			if ($i%2) $trcolor = "col2"; else $trcolor = "col1";

			//slash işaretleri temizleniyor
			$file_name 				= stripslashes($file_name);
			$file_desc 				= stripslashes($file_desc);
			$file_dlurl 			= stripslashes($file_dlurl);
			$file_catid 			= stripslashes($file_catid);
			$file_version 			= stripslashes($file_version);
			
			$file_link = SITELINK.'/' . URUNDETAY . '?fid=' . $file_id .'-'. pco_format_url($file_name) ;			
			if (SEO_OPEN == 1) $file_link = SITELINK.'/' . pco_format_url($file_name) . '-f' . $file_id . SEO;

			if ($file_rss_version <> '') 
			{
				$file_rss_version = '<a href="'.$file_rss_version.'"><img src="'.SITELINK.'/_img/rss_blue.png">';
			}
			else if ($file_nonrss_version <> '') 
			{
				$file_rss_version = '<a href="'.$file_nonrss_version.'"><img src="'.SITELINK.'/_img/rss_network.png">';
			}
			else
			{
				$file_rss_version = '<img src="'.SITELINK.'/_img/rss_stop.png">';
			}
			
			
			$sayfabilgisi.= '
			<tr class="'.$trcolor.'">
				<td valign="center"><a title="dosya bilgilerini güncelle" href="'.$acp_dosyalarlink.'&amp;un='.$file_id.'"><img src="'.SITELINK.'/_img/icon_edit.gif">Düzenle</a></td>
				<td width="35"><img width="35" src="'.SITELINK.'/posticons/'.$file_posticon.'"></td>
				<td><h2><a target="_blank" href="'.$file_link.'"> '.$file_name.' v.'.$file_version.'</a></h2></td>
				<td>'.$file_version.'</td>
				<td align="center">'.$file_status_icon.'</td>
				<td align="center">'.$file_rss_version.'</td>
				<td align="center"><a href="'.$file_dlurl.'"><img src="'.SITELINK.'/_img/icon_indir.png"></a></td>
				<td align="center"><a target="_blank" href="'.$file_creator.'"><img src="'.SITELINK.'/_img/icon_right.png"></a>
				</td>
				<td>
					<div>
						<select style="width:250px" name="file_catid['.$file_id.']">
						<option value="'.$file_catid.'">&raquo; '.$array_kategorilistesi[$file_catid]["cat_name"].'</option>					
						'.$kategoriler_options.'
						</select>
					</div>
				</td>
			</tr>';
		}
	}
	else
	{
		$sayfabilgisi = '<div class="errorbox">Hiçbir Sonuç Bulunamadı!</div>';
	}
?>		

<script type="text/javascript">
function highlight(field)
{
field.focus();
field.select();
}
</script>

<a class="button1" href="<?=$acp_dosyalarlink?>&amp;dosyaekle=1"><img src="<?=SITELINK?>/_img/icon_ekle.png">DOSYA EKLE</a>

Bu paneli kullanarak, sisteme kayıtlı dosyaların bilgilerini güncelleyebilir ve silebilirsiniz.<br><br>

<?=$islemsonucu?>

<table class="vitrinler" width="%100" border="0" cellpadding="3" cellspacing="3">
	<tr>
		<td colspan="10"><?=$sayfalama?></td>
	</tr>
	<tr>
		<td colspan="10">
		<div>
			<form action="<?=$acp_dosyalarlink?>" method="get">
			Aranacak kelime: 
			<input type="hidden" name="menu" value="dosyalar">
			<input type="text" style="width: 175px;" name="aramaanahtari" value="<?=$aramaanahtari?>"/>
			<select size="1" name="aramatipi" style="width:100px">
			<option value="filename" selected="selected">dosya adı</option>
			</select>
			<select style="width:180px" name="filecat">
			<?php if ($filecat > 0) { ?>
				<option value="<?=$filecat?>">&raquo; <?=$array_kategorilistesi[$filecat]["cat_name"]?></option>
			<?php } ?>
			<option value="">&raquo; Bütün Kategorilerde</option>					
			<?=$kategoriler_options?>
			</select>
				
			<input class="button1" value=" Araştır " type="submit"> 
			(<?=$adet?> sonuç görüntüleniyor)
			</form>
		</div>
		</td>
	</tr>
	<tr>
		<th height="25" width="70">
		<?php if($siralamatipi == 'time' && $by == 0) { echo '<a href="'.$sayfalink.'&order=time&by=1""> Time <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == 'time' && $by == 1) { echo '<a href="'.$sayfalink.'&order=time&by=0"> Time <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order=time"> Time </a> '; } ?>
		</th>
		<th colspan="2" width="270">
			<?php if($siralamatipi == 'filename' && $by == 0) { echo '<a href="'.$sayfalink.'&order=filename&by=1""> DOSYA ADI <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == 'filename' && $by == 1) { echo '<a href="'.$sayfalink.'&order=filename&by=0"> DOSYA ADI <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order=filename"> DOSYA ADI </a> '; } ?>
		</th>
		<th>
			<?php if($siralamatipi == 'version' && $by == 0) { echo '<a href="'.$sayfalink.'&order=version&by=1""> VERSİYON <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == 'version' && $by == 1) { echo '<a href="'.$sayfalink.'&order=version&by=0"> VERSİYON <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order=version"> VERSİYON </a> '; } ?>
		</th>
		<th width="55">
			<?php if($siralamatipi == 'status' && $by == 0) { echo '<a href="'.$sayfalink.'&order=status&by=1""> DURUM <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == 'status' && $by == 1) { echo '<a href="'.$sayfalink.'&order=status&by=0"> DURUM <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order=status"> DURUM </a> '; } ?>
		</th>
		<th width="55">
			<?php if($siralamatipi == 'rss' && $by == 0) { echo '<a href="'.$sayfalink.'&order=rss&by=1""> RSS <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == 'rss' && $by == 1) { echo '<a href="'.$sayfalink.'&order=rss&by=0"> RSS <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order=rss"> RSS </a> '; } ?>
		</th>

		<th>
			<?php if($siralamatipi == 'dlurl' && $by == 0) { echo '<a href="'.$sayfalink.'&order=dlurl&by=1""> İNDİR <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == 'dlurl' && $by == 1) { echo '<a href="'.$sayfalink.'&order=dlurl&by=0"> İNDİR <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order=dlurl"> İNDİR </a> '; } ?>
		</th>
		<th>
			<?php if($siralamatipi == 'creator' && $by == 0) { echo '<a href="'.$sayfalink.'&order=creator&by=1""> GELİŞTİRİCİ <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == 'creator' && $by == 1) { echo '<a href="'.$sayfalink.'&order=creator&by=0"> GELİŞTİRİCİ <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order=creator"> GELİŞTİRİCİ </a> '; } ?>
		</th>
		<th width="200">
			<?php if($siralamatipi == 'catid' && $by == 0) { echo '<a href="'.$sayfalink.'&order=catid&by=1""> KATEGORİ <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == 'catid' && $by == 1) { echo '<a href="'.$sayfalink.'&order=catid&by=0"> KATEGORİ <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order=catid"> KATEGORİ </a> '; } ?>
		</th>
	</tr>

<form name="topluduzenle" action="<?=$acp_dosyalarlink?>" method="POST">
<input type="hidden" name="menu" value="dosyalar">
<input type="hidden" name="topluduzenle" value="1">
<input type="hidden" name="aramaanahtari" value="<?=$aramaanahtari ?>">
<input type="hidden" name="aramatipi" value="<?=$aramatipi ?>">
<input type="hidden" name="lt" value="<?=$listetipi ?>">
<input type="hidden" name="order" value="<?=$siralamatipi ?>">
<input type="hidden" name="limit" value="<?=$limit ?>">
<input type="hidden" name="filecat" value="<?=$filecat ?>">

<?=$sayfabilgisi?>
	<tr>
		<td colspan="10"><?=$sayfalama?></td>
	</tr>
	<tr>
		<td colspan="10"><div align="right"><input class="button1" type="submit" name="topluduzenle" value="TOPLU DÜZENLE"></div></td>
	</tr>
</table>
</form>
<?php
	//(if $file_id > 0) ... else sonu
	}
?>

<?php include($siteyolu."/_panel_acp/_temp/_t_adminbitis.php"); ?>