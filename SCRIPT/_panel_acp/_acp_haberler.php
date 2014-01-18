<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"]==1) exit ();

include($siteyolu."/_panel_acp/_temp/_t_adminbaslangic.php"); 

$news_id 		= $_REQUEST['un']; 				settype($news_id,"integer");
$haber_ekle 	= $_REQUEST['haber_ekle']; 		settype($haber_ekle,"integer");

//birden çok yerde kullanacağımız için
//önce dizileri oluşturuyoruz
$vt->sql('SELECT file_id, file_name FROM tv_files WHERE file_id > 0 ORDER BY file_name ASC;')->sor();
$sonuc = $vt->alHepsi();
$adet_files = $vt->numRows();
for ( $ii = 0; $ii < $adet_files; $ii++)
{
	$file_id 					= $sonuc[$ii]->file_id;
	$file_name 					= $sonuc[$ii]->file_name;
	$array_file_list[$file_id] 	= substr($file_name,0,32);
}

$option_file_list = '<option value="-1">Tüm Yazılımlar</option>'. "\r\n";
foreach ($array_file_list as $k => $v)
{
	$option_file_list.= '<option value="'.$k.'">'.$v.'</option>'. "\r\n";
}

foreach ($array_news_status as $k => $v)
{
	$option_news_status.= '<option value="'.$k.'">'.$v.'</option>'. "\r\n";
}

//gerçek işlemlere başlıyoruz
if ($news_id > 0)
{
	include($siteyolu."/_panel_acp/_acp_haberler_duzenle.php");
}
elseif ($haber_ekle > 0)
{
	include($siteyolu."/_panel_acp/_acp_haberler_ekle.php");
}
else
{
	//reset
	$siralama 		= '';
	$sorguilavesi 	= '';

	//form için gereken değişkenler alınıyor
	$listetipi 		= $_REQUEST['lt'];
	$limit 			= $_REQUEST['limit'];
	$aramaanahtari 	= $_REQUEST['aramaanahtari'];
	$aramatipi 		= $_REQUEST['aramatipi'];
	$filecat 		= $_REQUEST['filecat'];
	$siralamatipi 	= $_REQUEST['order'];
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
  	$news_title 	= $_REQUEST["news_title"];
	$news_desc 		= $_REQUEST["news_desc"];
	$news_link 		= $_REQUEST["news_link"];
	$news_fileid 	= $_REQUEST["news_fileid"]; 
	$news_status 	= $_REQUEST["news_status"];  

 	//formla ilgili öncelikli işlemler varsa tamamlıyoruz
 	if (isset ($_REQUEST["topluduzenle"]))
	{
		//ilişkili yazılım bilgilerini güncelliyoruz
		foreach ($news_fileid as $k => $v)
		{
			$v = addslashes(trim(strip_tags($v)));
			//yapı gereği, dosya id seçilmemişse -1 nolu dosyaya atıyoruz
			//bu da harici konular demek oluyor
			if ( $v < 1) $v = '-1';
			$vt->sql('UPDATE tv_news SET news_fileid = %u WHERE news_id = %u')->arg($v,$k)->sor();;
		}

		//durum bilgilerini güncelliyoruz
		foreach ($news_status as $k => $v)
		{
			$v = addslashes(trim(strip_tags($v)));
			$vt->sql('UPDATE tv_news SET news_status = %u WHERE news_id = %u')->arg($v,$k)->sor();;
		}
		$islemsonucu = '<div class="successbox">İşlem Başarı İle Tamamlandı.</div>';
	}

	//formu oluşturmaya başlıyoruz
	
	//arama tipleri oluşturuluyor
	if ($aramatipi == "title") $siralamatipi = "title";
	if ($aramatipi == "desc") $siralamatipi = "desc";
	if ($aramatipi == "link") $siralamatipi = "link";

	//iç sıralamalar oluşturuluyor
	if ($siralamatipi == "title" && $filecat)
	{
		$sorguilavesi = 'AND news_fileid = '.$filecat.' AND news_title LIKE "%'.$aramaanahtari.'%"';
		$siralama = 'news_title '.$order_by;
	}
	else if ($siralamatipi == "desc" && $filecat)
	{
		$sorguilavesi = 'AND news_fileid = '.$filecat.' AND news_desc LIKE "%'.$aramaanahtari.'%"';
		$siralama = 'news_desc '.$order_by;
	}
	else if ($siralamatipi == "link" && $filecat)
	{
		$sorguilavesi = 'AND news_fileid = '.$filecat.' AND news_link LIKE "%'.$aramaanahtari.'%"';
		$siralama = 'news_link '.$order_by;
	}
	else if ($siralamatipi == "title" && $filecat == "")
	{
		$sorguilavesi = 'AND news_title LIKE "%'.$aramaanahtari.'%"';
		$siralama = 'news_title '.$order_by;
	}
	else if ($siralamatipi == 'desc' && $filecat == "")
	{
		$siralama = 'news_desc '.$order_by.', news_title ASC';
	}
	else if ($siralamatipi == 'link' && $filecat == "")
	{
		$siralama = 'news_link '.$order_by.', news_title ASC';
	}
	else if ($siralamatipi == "time")
	{
		$siralama = 'changetar '.$order_by.' , news_title ASC';	
	}
	else if ($siralamatipi == 'fileid')
	{
		$siralama = 'news_fileid '.$order_by.', news_title ASC';
	}
	else if ($siralamatipi == 'status')
	{
		$siralama = 'news_status '.$order_by.', news_title ASC';
	}
	else
	{
		$siralama = 'news_id DESC';	
	}
	
	//sıralama tipi oluşturuluyor
	if (!$siralama) $siralama = 'news_id '.$order_by;

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
	$vt->sql('SELECT count(news_id) as sayim FROM tv_news WHERE news_id > 0 '.$sorguilavesi.' ORDER BY '.$siralama.';')->sor();
	$sayim = $vt->alTek();
	$sayi = ($sayim /$sayfasonucmiktari);

	//sayfalama özelliği başlatılıyor
	if($sayi > 1)
	{
		$sayfalama = '<div class="successbox">Sayfalar: ';
		for ($i = 0; $i < $sayi; $i++)
		{
			$sayfalama.= '<a href="'.$acp_haberlerlink.'';
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
		$sayfalama.= ' <a href="'.$acp_haberlerlink.'&amp;lt='.$listetipi;
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

	$sayfalink = $acp_haberlerlink;
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
	$vt->sql('SELECT news_id, news_fileid, news_link, news_title, news_desc, news_status FROM tv_news WHERE news_id > 0 '.$sorguilavesi.' ORDER BY '.$siralama.' '.$limitsorgusu.';')->sor();
	$sonuc = $vt->alHepsi();
	$adet = $vt->numRows();

	//sayfa içi oluşturuluyor, döne döne
	if ($adet)
	{
		for ( $i = 0; $i < $adet; $i++)
		{
			$news_id 		= $sonuc[$i]->news_id;
			$news_fileid 	= $sonuc[$i]->news_fileid;
			$news_link 		= $sonuc[$i]->news_link;
			$news_title 	= $sonuc[$i]->news_title;
			$news_desc 		= $sonuc[$i]->news_desc;
			$news_status 	= $sonuc[$i]->news_status;

			if ($i%2) $trcolor = "col2";  else $trcolor = "col1";

			//slash işaretleri temizleniyor
			$news_title 	= stripslashes($news_title);
			$news_desc 		= stripslashes($news_desc);
			
			$sayfabilgisi.= '
			<tr class="'.$trcolor.'">
				<td valign="center"><a title="haberi güncelle" href="'.$acp_haberlerlink.'&amp;un='.$news_id.'"><img src="'.SITELINK.'/_img/icon_edit.gif">Düzenle</a></td>
				<td><div>'.$news_title.'</div></td>
				<td><div><a target="_blank" href="'.$news_link.'"><img src="'.SITELINK.'/_img/icon_right.png"></a></div></td>
				<td>
					<div>
						<select style="width:200px" name="news_fileid['.$news_id.']">
							<option value="'.$news_fileid.'">'.$array_file_list[$news_fileid].'</option>
							'.$option_file_list.'
						</select>
					</div>
				</td>
				<td>
					<div>
						<select style="width:200px" name="news_status['.$news_id.']">
							<option value="'.$news_status.'">'.$array_news_status[$news_status].'</option>
							'.$option_news_status.'
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

<a class="button1" href="<?=$acp_haberlerlink?>&amp;haber_ekle=1"><img src="<?=SITELINK?>/_img/icon_ekle.png">HABER EKLE</a>

Bu paneli kullanarak, sisteme kayıtlı haberleri güncelleyebilir ve silebilirsiniz.<br><br>

<?=$islemsonucu?>

<table class="vitrinler" width="%100" border="0" cellpadding="3" cellspacing="3">
	<tr>
		<td colspan="7"><?=$sayfalama?></td>
	</tr>
	<tr>
		<td colspan="7">
		<div>
			<form action="<?=$acp_haberlerlink?>" method="get">
			Aranacak kelime: 
			<input type="hidden" name="menu" value="haberler">
			<input type="text" style="width: 175px;" name="aramaanahtari" value="<?=$aramaanahtari?>"/>
			<select size="1" name="aramatipi" style="width:150px">
			<option value="title">dosya adı</option>
			<option value="desc">dosya açıklaması</option>
			<option value="link">dosya link</option>
			</select>
			<select style="width:180px" name="filecat">
			<?php if ($filecat > 0) { ?>
				<option value="<?=$filecat?>">&raquo; <?=$array_file_list[$filecat]?></option>
			<?php } ?>
			<?=$option_file_list?>
			</select>
				
			<input class="button1" value=" Araştır " type="submit"> 
			(<?=$adet?> sonuç görüntüleniyor)
			</form>
		</div>
		</td>
	</tr>
	<tr>
		<th height="25" width="70">
		<?php if($siralamatipi == 'time' && $by == 0) { echo '<a href="'.$sayfalink.'&order=time&amp;by=1""> Time <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == 'time' && $by == 1) { echo '<a href="'.$sayfalink.'&order=time&amp;by=0"> Time <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order=time"> Time </a> '; } ?>
		</th>
		<th>
			<?php if($siralamatipi == 'title' && $by == 0) { echo '<a href="'.$sayfalink.'&order=title&amp;by=1""> BAŞLIK <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == 'title' && $by == 1) { echo '<a href="'.$sayfalink.'&order=title&amp;by=0"> BAŞLIK <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order=title"> BAŞLIK </a> '; } ?>
		</th>
		<th width="50">
			<?php if($siralamatipi == 'link' && $by == 0) { echo '<a href="'.$sayfalink.'&order=link&amp;by=1""> LİNK <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == 'link' && $by == 1) { echo '<a href="'.$sayfalink.'&order=link&amp;by=0"> LİNK <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order=link"> LİNK </a> '; } ?>
		</th>
		<th width="1">
			<?php if($siralamatipi == 'fileid' && $by == 0) { echo '<a href="'.$sayfalink.'&order=fileid&amp;by=1""> YAZILIM <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == 'fileid' && $by == 1) { echo '<a href="'.$sayfalink.'&order=fileid&amp;by=0"> YAZILIM <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order=fileid"> YAZILIM </a> '; } ?>
		</th>
		<th width="1">
			<?php if($siralamatipi == 'status' && $by == 0) { echo '<a href="'.$sayfalink.'&order=status&amp;by=1""> DURUM <img src="'.SITELINK.'/_img/siralama_up.gif"></a>'; } else if($siralamatipi == 'status' && $by == 1) { echo '<a href="'.$sayfalink.'&order=status&amp;by=0"> DURUM <img src="'.SITELINK.'/_img/siralama_down.gif"></a>'; } else { echo '<a href="'.$sayfalink.'&order=status"> DURUM </a> '; } ?>
		</th>
	</tr>

<form name="topluduzenle" action="<?=$acp_haberlerlink?>" method="POST">
<input type="hidden" name="menu" value="haberler">
<input type="hidden" name="topluduzenle" value="1">
<input type="hidden" name="aramaanahtari" value="<?=$aramaanahtari ?>">
<input type="hidden" name="aramatipi" value="<?=$aramatipi ?>">
<input type="hidden" name="lt" value="<?=$listetipi ?>">
<input type="hidden" name="order" value="<?=$siralamatipi ?>">
<input type="hidden" name="limit" value="<?=$limit ?>">
<input type="hidden" name="filecat" value="<?=$filecat ?>">

<?=$sayfabilgisi?>
	<tr>
		<td colspan="7"><?=$sayfalama?></td>
	</tr>
	<tr>
		<td colspan="7"><div align="right"><input class="button1" type="submit" name="topluduzenle" value="TOPLU DÜZENLE"></div></td>
	</tr>
</table>
</form>
<?php
	//(if $news_id > 0) ... else sonu
	}
?>

<?php include($siteyolu."/_panel_acp/_temp/_t_adminbitis.php"); ?>