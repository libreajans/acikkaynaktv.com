<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"]==1) exit ();

include($siteyolu."/_panel_acp/_temp/_t_adminbaslangic.php"); 

$id = $_REQUEST['id']; settype($id,"integer");
$ekle = $_REQUEST['ekle']; settype($ekle,"integer");
if ($ekle > 0)
{
	include($siteyolu."/_panel_acp/_temp/_t_adminmenuleri.php");
	echo '</div><div id="main">';
	include($siteyolu."/_panel_acp/_acp_icon_ekle.php");
}
else
{

	$bellekyolu = 'posticons';
	$bellek = opendir($bellekyolu);
	if (!$bellek)
	{
		$mesaj =  '<div class="errorbox">İkon Dizini Bulunamadı</div>';
	}

	$ic = 0;
	$mesaj = '<table width="700"><tr class="col1">';
	while ($dosya = readdir($bellek))
	{
		//kitap ile başlayan dosyalar vitrin dosyaları ise sadece o dosyaları buluyoruz ve siliyoruz
		if ($dosya <> '.' and $dosya <> '..' and $dosya <> 'index.html')
		{
			$mesaj.= '
			<td width="10" valign="bottom">
			<center>
			<img width="82" src="'.SITELINK.'/posticons/'.$dosya.'">'.$dosya.'
			</center>
			</td>';
		}
		$ic++;
		if ($ic == 8)
		{
			$mesaj.= '</tr><tr class="col1">';
			$ic = 0;
		}
	}
	$mesaj.= '</tr></table>';
	closedir($bellekyolu);
?>

<style>
.td.name {text-align: center}
</style>
<a class="button1" href="<?=$acp_iconlink?>&ekle=1"><img src="<?=SITELINK?>/_img/icon_ekle.png">İKON EKLE</a>

Bu paneli kullanarak sisteminize ekli ikonları görüntüleyebilir ve yeni ikonlar ekleyebilirsiniz.<br><br>

<?=$mesaj?>

<?php } ?>		
<?php include($siteyolu."/_panel_acp/_temp/_t_adminbitis.php"); ?>