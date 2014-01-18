<?php
if (!defined('yakusha')) die('...');

# mesajlar
$sayfa_baslik = $YAKUSHA["site_baslik"];

$fid = $_REQUEST["fid"]; settype($fid,"integer");
$cat = $_REQUEST["cat"]; settype($cat,"integer");

//sayfa adına göre SEO ve TITLE değerleri atıyoruz
$sayfaadi = basename($_SERVER['SCRIPT_NAME'],".php");
switch ($sayfaadi)
{
	case 'index':
		if ($cat > 0)
		{
			$sayfaadi = $array_kategorilistesi[$cat]["cat_name"];
			$sayfa_baslik = $sayfaadi .' | '. $sayfa_baslik;
		}

	break;
	case 'urundetay':
		$vt->sql('SELECT file_name FROM tv_files WHERE file_id = %u')->arg($fid)->sor($cachetime);
		$file_name = $vt->alTek();
		$site_link_canonical = SITELINK.'/' . URUNDETAY . '?fid=' . $file_id .'-'. pco_format_url($file_name) ;			
		if (SEO_OPEN == 1) $site_link_canonical = SITELINK.'/' . pco_format_url($file_name) . '-f' . $file_id . SEO;			
		$sayfa_baslik = $file_name .' | '. $sayfa_baslik;
	break;
	

	case 'haberler':
		$sayfa_baslik = 'Haberler  | '. $sayfa_baslik;
	break;

	case 'yolharitasi':
		$sayfa_baslik = 'Yol Haritası  | '. $sayfa_baslik;
	break;

	case 'kurallar':
		$sayfa_baslik = 'Kurallar  | '. $sayfa_baslik;
	break;	

	case 'hakkimizda':
		$sayfa_baslik = 'Hakkımızda  | '. $sayfa_baslik;
	break;
}

include($siteyolu."/_lib_temp/_t_sitebaslangic.php"); 
include($siteyolu."/_lib_page/_page_".$PAGE["islem"].".php");
include($siteyolu."/_lib_temp/_t_siteright.php"); 
include($siteyolu."/_lib_temp/_t_sitebitis.php"); 