<?php
	if (!defined('yakusha')) die('...');

	if (ENYENILER > 0)
	{
		$vt->sql('SELECT * FROM tv_files WHERE file_id > 0 AND file_status = 0 ORDER BY createtar DESC limit 0,'.ENYENILER)->sor($cachetime);
		$sonuc = $vt->alHepsi();
		$adet = $vt->numRows();
	}

	if ($order <> '')
	{
		if ($order == 'guncellenme') $ilavesorgu = 'changetar DESC';
		if ($order == 'eklenme') $ilavesorgu = 'createtar DESC';
		if ($order == 'name') $ilavesorgu = 'file_name ASC';
		if ($order == 'rss') $ilavesorgu = 'file_rss_version DESC';
		if ($order == 'nonrss') $ilavesorgu = 'file_nonrss_version DESC';

		$vt->sql('SELECT * FROM tv_files WHERE file_id > 0 AND file_status = 0 ORDER BY '.$ilavesorgu)->sor($cachetime);
		$sonuc = $vt->alHepsi();
		$adet = $vt->numRows();
	}

	if ($aramaanahtari <> '')
	{
		$vt->sql('SELECT * FROM tv_files WHERE file_id > 0 AND file_name LIKE "%'.$aramaanahtari.'%" AND file_status = 0 ORDER BY file_name ASC')->sor($cachetime);
		$sonuc = $vt->alHepsi();
		$adet = $vt->numRows();
	}

	if ($kategoriid > 0)
	{
		$vt->sql('SELECT * FROM tv_files WHERE file_id > 0 AND file_catid = %u AND file_status = 0 ORDER BY file_name ASC')->arg($kategoriid)->sor($cachetime);
		$sonuc = $vt->alHepsi();
		$adet = $vt->numRows();
	}
	
	if ($adet)
	{
		for ( $i = 0; $i < $adet; $i++)
		{
			//sorgudan alınıyor
			$file_id 				= $sonuc[$i]->file_id;
			$file_name 				= $sonuc[$i]->file_name;
			$file_desc 				= $sonuc[$i]->file_desc;
			$file_creator 			= $sonuc[$i]->file_creator;
			$file_version 			= $sonuc[$i]->file_version;
			$file_longdesc 			= $sonuc[$i]->file_longdesc;
			$file_ssurl 			= $sonuc[$i]->file_ssurl;
			$file_dlurl 			= $sonuc[$i]->file_dlurl;
			$file_catid 			= $sonuc[$i]->file_catid;
			$file_posticon 			= $sonuc[$i]->file_posticon;
			$file_rss_version 		= $sonuc[$i]->file_rss_version;
			$file_nonrss_version 	= $sonuc[$i]->file_nonrss_version;

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
				$file_rss_version = '
				<a href="'.$file_rss_version.'">
				<img title="Bu yazılım ile ilgili sürüm güncellemeleri RSS ile Takip edilmektedir" src="'.SITELINK.'/_img/rss_blue.png">';
			}
			else if ($file_nonrss_version <> '') 
			{
				$file_rss_version = '
				<a href="'.$file_nonrss_version.'">
				<img title="Bu yazılım ile ilgili sürüm güncellemeleri Manuel Takip edilmektedir" src="'.SITELINK.'/_img/rss_network.png">';
			}
			else
			{
				$file_rss_version = '
				<img title="Bu yazılım ile ilgili sürüm güncellemeleri Takip edilememektedir" src="'.SITELINK.'/_img/rss_stop.png">';
			}

			//gerekli olan biçimlendirme
			$file_name = stripslashes($file_name);
			$file_desc = stripslashes($file_desc);
			$file_name = pco_imla_denetle($file_name);
			$file_desc = pco_imla_denetle($file_desc);
	
			$file_link = SITELINK.'/' . URUNDETAY . '?fid=' . $file_id .'-'. pco_format_url($file_name) ;			
			if (SEO_OPEN == 1) $file_link = SITELINK.'/' . pco_format_url($file_name) . '-f' . $file_id . SEO;			
			
			$sayfabilgisi.='
			<tr>
				<td class="mynotes" valign="top" width="100">
					'.$file_posticon.' 
				</td>
				<td class="mynotes">
					<h2><a title="Detaylı Bilgileri Görüntülemek İçin Tıklayınız" href="'.$file_link.'">'.$file_name.' v.'.$file_version.'</a></h2>
					<p>'.$file_desc.'</p>
					<p><a title="Detaylı Bilgileri Görüntülemek İçin Tıklayınız" href="'.$file_link.'"><img title="yazılım hakkında detaylı bilgi" src="'.SITELINK.'/_img/detayli.gif"></a><p>
				</td class="mynotes">
				<td class="mynotes" align="center" width="70">
					<p>'.$file_rss_version.'</p>
					<a target="_blank" href="'.$file_dlurl.'">
					<img title="yazılımı indir" src="'.SITELINK.'/_img/icon_indir.png"><br>İndirmek İçin Tıklayınız
					</a>
				</td>
			</tr>
			';
		}
	}
	else
	{
		$sayfabilgisi.= '<!--<tr><td colspan="3">Bu Kategoriye Henüz Yazılım Eklenmemiş</td></tr>-->';
	}
?>