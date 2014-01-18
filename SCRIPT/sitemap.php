<?php
	define('yakusha', 1);
	include("_header.php");

	$listeleneceksonuc = 300;

	$vt->sql('SELECT file_id, file_name, changetar FROM tv_files WHERE file_id > 0 AND file_status = 0 ORDER BY changetar DESC	LIMIT 0,'. $listeleneceksonuc)->sor($cachetime);
	$sonuc = $vt->alHepsi();
	$adet = $vt->numRows();

	$sayfabilgisi  = '<' . '?xml version="1.0" encoding="UTF-8"?' . '>';
	$sayfabilgisi .= '<?xml-stylesheet type="text/xsl" href="'.SITELINK.'/sitemap.xsl"?><urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	for ( $i = 0; $i < $adet; $i++ ) 
	{
		$file_id 	= $sonuc[$i]->file_id;
		$file_name 	= $sonuc[$i]->file_name;
		$changetar 	= $sonuc[$i]->changetar;
		$changetar 	= date('Y-m-d',$changetar);

		$file_link = SITELINK.'/' . URUNDETAY . '?fid=' . $file_id .'-'. pco_format_url($file_name) ;			
		if (SEO_OPEN == 1) $file_link = SITELINK.'/' . pco_format_url($file_name) . '-f' . $file_id . SEO;			

		$sayfabilgisi .= "<url>\n";
		$sayfabilgisi .= "\t<loc>$file_link</loc>\n";
		$sayfabilgisi .= "\t<lastmod>$changetar</lastmod>\n";
		$sayfabilgisi .= "\t<changefreq>daily</changefreq>\n";
		$sayfabilgisi .= "\t<priority>0.5</priority>\n";
		$sayfabilgisi .= "</url>\n\n";
	}
	$sayfabilgisi .= "</urlset>\n";
	header('Content-type: application/xml');
	echo $sayfabilgisi;
?>