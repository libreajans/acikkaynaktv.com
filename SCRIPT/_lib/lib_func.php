<?php
//metinleri imla açısından kontrol eder
function pco_metatablosu_temizle()
{
	global $vt;
	$vt->sql('TRUNCATE TABLE vt_meta')->sor();
}

function pco_imla_denetle($metin)
{
	$metin = str_replace(array("\r\n","\r","<bn>","<br><br>",), "\n<br>", $metin); // cross-platform newlines
	$metin = trim($metin);
	return $metin;
}

function pco_format_url($url)
{
	$url = trim($url);
	$url = strtolower($url);

	$find = array(' ', '&quot;', '&amp;', '&', '\r\n', '\n', '/', '\\', '+', '<', '>');
	$url = str_replace ($find, '-', $url);

	$find = array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ë', 'Ê');
	$url = str_replace ($find, 'e', $url);

	$find = array('í', 'ı', 'ì', 'î', 'ï', 'I', 'İ', 'Í', 'Ì', 'Î', 'Ï');
	$url = str_replace ($find, 'i', $url);

	$find = array('ó', 'ö', 'Ö', 'ò', 'ô', 'Ó', 'Ò', 'Ô');
	$url = str_replace ($find, 'o', $url);

	$find = array('á', 'ä', 'â', 'à', 'â', 'Ä', 'Â', 'Á', 'À', 'Â');
	$url = str_replace ($find, 'a', $url);

	$find = array('ú', 'ü', 'Ü', 'ù', 'û', 'Ú', 'Ù', 'Û');
	$url = str_replace ($find, 'u', $url);

	$find = array('ç', 'Ç');
	$url = str_replace ($find, 'c', $url);

	$find = array('ş', 'Ş');
	$url = str_replace ($find, 's', $url);

	$find = array('ğ', 'Ğ');
	$url = str_replace ($find, 'g', $url);

	$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');

	$repl = array('', '-', '');

	$url = preg_replace ($find, $repl, $url);
	$url = str_replace ('--', '-', $url);

	$url = $url;

	return $url;
} 


function f_secure_search($f_aranacak) 
{
	//bu fonksiyon mentis bilişim, bayram atmaca tarafından yazılmıştır
	//sistemin orjinaline ait değildir

	# Aranacak ifade SQL sorgusu için güvenli hale getiriliyor.
	$aranacak = trim( strip_tags(substr( ereg_replace("%","",$f_aranacak),0,20) ) ); 
	$aranacak = trim( ereg_replace ("<","",$aranacak) );
	$aranacak = trim( ereg_replace (">","",$aranacak) );
	$aranacak = trim( ereg_replace ("\"","",$aranacak) );
	$aranacak = trim( ereg_replace ("'","",$aranacak) );
	$aranacak = trim( ereg_replace ("&","",$aranacak) );
	$aranacak = trim( ereg_replace ("#","",$aranacak) );
	$aranacak = trim( ereg_replace ("\*","",$aranacak) );
	$aranacak = trim( ereg_replace ("\?","",$aranacak) );
	$aranacak = trim( ereg_replace ("\+","",$aranacak) );
	$aranacak = trim( ereg_replace ("\(","",$aranacak) );
	$aranacak = trim( ereg_replace ("\)","",$aranacak) );
	$aranacak = trim( ereg_replace ("\[","",$aranacak) );
	$aranacak = trim( ereg_replace ("\]","",$aranacak) );
	$aranacak = trim( ereg_replace ("\{","",$aranacak) );
	$aranacak = trim( ereg_replace ("\}","",$aranacak) );
	$aranacak = trim( ereg_replace ("\|","",$aranacak) );

	$char = htmlentities($aranacak);
	$c = strlen($char);

	$char = str_replace("&eth;","&ETH;",$char);
	$char = str_replace("&uuml;","&Uuml;",$char);
	$char = str_replace("&thorn;","&THORN;",$char);
	$char = str_replace("&ccedil;","_",$char);
	$char = str_replace("&yacute;","_",$char);
	$char = str_replace("i","_",$char);
	$char = str_replace("İ","_",$char);
	$char = str_replace("ı","_",$char);
	$char = str_replace("&ouml;","&Ouml;",$char);
	//$char = str_replace("Ç","_",$char);
	//$char = str_replace("ç","_",$char);
	return html_entity_decode($char);
}

?>