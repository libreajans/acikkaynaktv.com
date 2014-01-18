<?php
if (!defined('yakusha')) die('...');

$vt->sql('SELECT count(news_id) FROM tv_news')->sor($cachetime);
$news_conut = $vt->alTek();

$vt->sql('SELECT count(file_id) FROM tv_files WHERE file_status = 0')->sor($cachetime);
$file_conut = $vt->alTek();

$endtime = microtime(true); 
$bitistime = substr(($endtime - $starttime),0,6); 

//$kullanim = memory_get_usage();
$kullanim = memory_get_peak_usage(true);
$kullanim = round($kullanim / 1024 / 1024, 2);
$sorgusayisi = $vt->sorguSayisi();
?>

<div id="footer">
<p class="right">
Tema: <a href="http://www.solucija.com/">Solucija</a> & Sistem: <a href="http://www.libreajans.com">Libre Ajans</a>
<br>SÜS: <?=$bitistime?> sayine. USG: <?=$kullanim?>  MB.  SQL: <?=$sorgusayisi?>
</p>
<p class="vitrinler">
<?=$YAKUSHA["site_bilgi"]?>
<br /><?=$news_conut ?> haber, <?=$file_conut ?> yazılım sitemizde bulunmaktadır.
</div>
</div>

</body>
</html>