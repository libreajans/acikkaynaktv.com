<?php
	define('yakusha', 1);
	include("_header.php");
	$type = $_REQUEST["type"];
	$listeleneceksonuc = 100;

	/*
	* prepare to $metin for rss
	*/
	function pco_rss_metin_hazirla($metin)
	{
		$metin = trim(strip_tags(substr( $metin,0,350)));
		$metin = stripslashes($metin);
		// embed $metin into CDATA tags in case it contains HTML tags or entities
		if (preg_match('/<[^>]+>|&#?[\w]+;/', $metin))
		{
			// replace any ]]>
			$metin = str_replace(']]>', ']]&gt;', $metin);
			$metin = '<![CDATA[' . $metin . ']]>';
		}
		$metin = str_replace('`', '\'', $metin);
		$metin = str_replace('', '\'', $metin);
		return $metin;
	}

	/**
	* create a date according to  RFC 822 for RSS2
	*/
	function pco_format_date($vitrintar)
	{
		return date('D, d M Y H:i:s O', $vitrintar);
	}

	// get time, use current time
	$last_build_date = mktime();

	if ($type == 'createtar'){ $type = 'createtar'; } else { $type = 'changetar';}

	$vt->sql('SELECT file_id, file_name, file_desc, file_version, file_posticon, changetar FROM tv_files WHERE file_status = 0 AND file_id > 0 ORDER BY '.$type.' DESC LIMIT 0,'. $listeleneceksonuc)->sor($cachetime);
	$sonuc = $vt->alHepsi();
	$adet = $vt->numRows();

	$sayfabilgisi = '
	<?xml version="1.0" encoding="UTF-8"?>
	<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
	<channel>
		<title>'.$YAKUSHA["site_isim"].' | RSS Kaynağı</title>
		<link>'.SITELINK.'/feed/</link>
		<description>'.$YAKUSHA["site_isim"].' için RSS Kaynağıdır.</description>
		<language>TR-tr</language>
		<pubDate>'.pco_format_date($last_build_date).'</pubDate>
		<lastBuildDate>'.pco_format_date($last_build_date).'</lastBuildDate>
		<docs></docs>
		<generator>Libre Ajans</generator>
		<managingEditor></managingEditor>
		<webMaster></webMaster>
	';

	for ( $i = 0; $i < $adet; $i++ ) 
	{
		$file_id 		= $sonuc[$i]->file_id;
		$file_name 		= $sonuc[$i]->file_name;
		$file_desc 		= $sonuc[$i]->file_desc;
		$file_version 	= $sonuc[$i]->file_version;
		$file_posticon 	= $sonuc[$i]->file_posticon;
		$changetar 		= $sonuc[$i]->changetar;
		
		//metin temizleme fonksiyonu
		$file_name 		= stripslashes($file_name);
		$file_version 	= stripslashes($file_version);
		$file_desc 		= stripslashes($file_desc);

		$file_desc 		= pco_rss_metin_hazirla($file_desc);
		$changetar 		= pco_format_date($changetar);
		
		if ($file_posticon <> '') 
		{
			$file_posticon = '<img height="40" src="'.SITELINK.'/posticons/'.$file_posticon.'">';
		}
		else
		{
			$file_posticon = '<img height="40" src="'.SITELINK.'/posticons/rules.jpg">';
		}
			

		$file_link = SITELINK.'/' . URUNDETAY . '?fid=' . $file_id .'-'. pco_format_url($file_name) ;			
		if (SEO_OPEN == 1) $file_link = SITELINK.'/' . pco_format_url($file_name) . '-f' . $file_id . SEO;	
			
		$sayfabilgisi.= "\n<item>\n";
		$sayfabilgisi.= "\t<dc:creator>".$file_name."</dc:creator>\n";
		$sayfabilgisi.= "\t<pubDate>".$changetar."</pubDate>\n";
		$sayfabilgisi.= "\t<link>".$file_link."</link>\n";
		$sayfabilgisi.= "\t<guid>".$file_link."</guid>\n";
		$sayfabilgisi.= "\t<title>".$file_name." V".$file_version."</title>\n";
		$sayfabilgisi.= "\t<description><![CDATA[".$file_posticon."]]>".$file_desc."</description>\n";
		$sayfabilgisi.= "</item>";
	}
	$sayfabilgisi .= "\n\t</channel>\n\t</rss>";
	header('Content-type: application/xml');
	echo trim($sayfabilgisi);
?>