<?php
if ($_SESSION[SES]["giris"] == 1) $cachetime = 0; else $cachetime = 600;

//dizi içine tam girmiyor lakin bu işlemlerden biz bir dizi elde ediyoruz
$vt->sql('SELECT meta_value FROM tv_meta WHERE meta_name = "ser_kategorilistesi"')->sor($cachetime);
$ser_kategorilistesi = $vt->alTek();

$vt->sql('SELECT meta_value FROM tv_meta WHERE meta_name = "ser_altkategoriler"')->sor($cachetime);
$ser_altkategoriler = $vt->alTek();

$vt->sql('SELECT meta_value FROM tv_meta WHERE meta_name = "kategoriler_metin"')->sor($cachetime);
$kategoriler_metin = $vt->alTek();

$vt->sql('SELECT meta_value FROM tv_meta WHERE meta_name = "kategoriler_options"')->sor($cachetime);
$kategoriler_options = $vt->alTek();

if ($ser_kategorilistesi <> '' && $ser_kategorilistesi <> '' && $ser_kategorilistesi <> '' && $kategoriler_options <> '')
{
	//slash işaretleri temizleniyor
	// $kategorilistesi = stripslashes($ser_kategorilistesi);
	// $altkategoriler = stripslashes($ser_altkategoriler);
	// $kategoriler_metin = stripslashes($ser_kategoriler_metin);
	//seri bozuluyor
	// $ser_kategorilistesi = substr($ser_kategorilistesi,1);
	// $ser_altkategoriler = substr($ser_altkategoriler,1);
	// $ser_kategoriler_metin = substr($ser_kategoriler_metin,1);

	$array_kategorilistesi = unserialize($ser_kategorilistesi);
	$array_altkategoriler = unserialize($ser_altkategoriler);
}
else
{
	//sql sorgusu oluşturuluyor
	$vt->sql('SELECT cat_id, cat_name, cat_desc, cat_icon, cat_parent, cat_order FROM tv_cat WHERE cat_parent = 0 ORDER BY cat_order ASC')->sor($cachetime);
	$sonuc = $vt->alHepsi();
	$adet = $vt->numRows();

	if ($adet)
	{
		$kategoriler_metin = '<table width="100%" border="0">';
		for ( $i = 0; $i < $adet; $i++)
		{
			//sorgudan alınıyor
			$cat_id 	= $sonuc[$i]->cat_id;
			$cat_name 	= $sonuc[$i]->cat_name;
			$cat_desc 	= $sonuc[$i]->cat_desc;
			$cat_icon 	= $sonuc[$i]->cat_icon;
			$cat_parent	= $sonuc[$i]->cat_parent;
			$cat_order 	= $sonuc[$i]->cat_order;

			//kimi değerlere varsayılan atıyoruz
			if (!$cat_icon) $cat_icon = '_nokta.png';

			//kimi varsayılan değerler üretiyoruz
			$kategoriler_options.= '<option value="'.$cat_id.'">'.$cat_name.'</option>'. "\r\n";
			$kategoriler_metin.= '
			<tr colspan="3">
				<td valign="middle" width="25px">
					<img width="22" src="'.PHOTOLINK. $cat_icon.'">
				</td>
				<td colspan="2" valign="middle">
					<a class="vitrinler_menu" href="'.ANASAYFALINK.'?cat='.$cat_id.'">'.$cat_name.'</a>
				</td>
			</tr>
			';

			//dizilere almaya başlıyoruz
			//?? acaba kullanılıyor mu bu seçenek
			//evet, yöntetim panelinde kategori seçiminde kullanılıyor
			$array_kategori_adlari[$cat_id] = $cat_name;

			$array_kategoriler[$cat_id]["cat_id"] = $cat_id;
			$array_kategoriler[$cat_id]["cat_name"] = $cat_name;
			$array_kategoriler[$cat_id]["cat_desc"] = $cat_desc;
			$array_kategoriler[$cat_id]["cat_icon"] = $cat_icon;
			$array_kategoriler[$cat_id]["cat_parent"] = $cat_parent;
			$array_kategoriler[$cat_id]["cat_order"] = $cat_order;		

			$array_kategorilistesi[$cat_id]["cat_id"] = $cat_id;		
			$array_kategorilistesi[$cat_id]["cat_name"] = $cat_name;		
			$array_kategorilistesi[$cat_id]["cat_desc"] = $cat_desc;		
			$array_kategorilistesi[$cat_id]["cat_icon"] = $cat_icon;		

			//sql sorgusu oluşturuluyor
			$vt->sql('SELECT cat_id, cat_name, cat_desc, cat_icon, cat_parent, cat_order FROM tv_cat WHERE cat_parent = '.$cat_id.' ORDER BY cat_order ASC')->sor($cachetime);
			$altsonuc = $vt->alHepsi();
			$icbulunanadet = $vt->numRows();

			if ($icbulunanadet)
			{
				for ( $ii = 0; $ii < $icbulunanadet; $ii++)
				{
					//sorgudan alınıyor
					$alt_cat_id 	= $altsonuc[$ii]->cat_id;
					$alt_cat_name 	= $altsonuc[$ii]->cat_name;
					$alt_cat_desc 	= $altsonuc[$ii]->cat_desc;
					$alt_cat_icon 	= $altsonuc[$ii]->cat_icon;
					$alt_cat_parent = $altsonuc[$ii]->cat_parent;
					$alt_cat_order 	= $altsonuc[$ii]->cat_order;

					//kimi değerlere varsayılan atıyoruz
					if (!$alt_cat_icon) $alt_cat_icon = '_nokta.png';
					//kimi varsayılan değerler üretiyoruz
					$kategoriler_options.= '<option value="'.$alt_cat_id.'">&raquo;&raquo; '.$alt_cat_name.'</option>'. "\r\n";
					$kategoriler_metin.= '
					<tr colspan="3">
						<td valign="middle"></td>
						<td valign="middle" width="25">
							<img width="22" src="'.PHOTOLINK. $alt_cat_icon.'">
						</td>
						<td valign="middle">
							<a class="vitrinler_menu" href="'.ANASAYFALINK.'?cat='.$alt_cat_id.'">'.$alt_cat_name.'</a>
						</td>
					</tr>';

					//dizilere almaya başlıyoruz
					$array_kategorilistesi[$alt_cat_id]["cat_id"] = $alt_cat_id;		
					$array_kategorilistesi[$alt_cat_id]["cat_name"] = $cat_name." &raquo; ".$alt_cat_name;		
					$array_kategorilistesi[$alt_cat_id]["cat_desc"] = $alt_cat_desc;		
					$array_kategorilistesi[$alt_cat_id]["cat_icon"] = $alt_cat_icon;		

					$array_altkategoriler[$alt_cat_id]["cat_id"] = $alt_cat_id;
					$array_altkategoriler[$alt_cat_id]["cat_name"] = $alt_cat_name;
					$array_altkategoriler[$alt_cat_id]["cat_desc"] = $alt_cat_desc;
					$array_altkategoriler[$alt_cat_id]["cat_icon"] = $alt_cat_icon;
					$array_altkategoriler[$alt_cat_id]["cat_parent"] = $alt_cat_parent;
					$array_altkategoriler[$alt_cat_id]["cat_order"] = $alt_cat_order;
				}
			} 
		}
		$kategoriler_metin.= '</table>';
	}
	$createtar = time();
	$ser_kategorilistesi = serialize($array_kategorilistesi);
	$ser_altkategoriler = serialize($array_altkategoriler);
	$ser_kategoriler_metin = $kategoriler_metin;
	$vt->sql('INSERT INTO tv_meta ( id, meta_name, meta_value, createtar) VALUES (1, "ser_kategorilistesi",%s,"'.$createtar.'")')->arg($ser_kategorilistesi)->sor();
	$vt->sql('INSERT INTO tv_meta ( id, meta_name, meta_value, createtar) VALUES (2, "ser_altkategoriler",%s,"'.$createtar.'")')->arg($ser_altkategoriler)->sor();
	$vt->sql('INSERT INTO tv_meta ( id, meta_name, meta_value, createtar) VALUES (3, "kategoriler_metin",%s,"'.$createtar.'")')->arg($kategoriler_metin)->sor();
	$vt->sql('INSERT INTO tv_meta ( id, meta_name, meta_value, createtar) VALUES (4, "kategoriler_options",%s,"'.$createtar.'")')->arg($kategoriler_options)->sor();
}

$array_user_status = array(
	'0' => 'Üye',
	'1' => 'Admin'
);

$array_news_status = array(
	'0' => 'Türkçe Haberler',
	'1' => 'İngilizce Haberler',
	'21' => 'Türkçe Makaleler',
	'22' => 'İngilizce Makaleler',
	'100' => 'Arşivlenmiş Notlar'
);

$array_news_status_desc = array(
	'0' => 'Türkçe Haberler',
	'1' => 'İngilizce Haberler',
	'21' => 'Türkçe Makaleler. Kimisini biz yazdık, kimisi zaten yazılmıştı. Sitemiz üstünden buyrun okuyunuz.',
	'22' => 'İngilizce Makaleler. Belki birileri çıkar tercüme eder, veya belki siz tercüme edilmeden de çok şey anlıyor olabilirsiniz.',
	'100' => 'Arşivlenmiş Notlar. Kimisini yakında sitemizde yazılım olarak bile görebilirsiniz.'
);

$array_file_status = array(
	'0' => 'Yayınlandı',
	'1' => 'Taslak',
	'2' => 'İnceleniyor',
	'3' => 'Silinmiş'
);

$array_file_status_icon = array(
	'0' => 'icon_check.gif',
	'1' => 'icon_tukendi.gif',
	'2' => 'icon_bilgi.gif',
	'3' => 'icon_ikaz.gif'
);
?>