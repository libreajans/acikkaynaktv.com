<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"] == 1) exit ();

require($siteyolu.'/_lib_class/eb.upload.php');

// upload işlemleri
if( $_FILES ) 
{
	// sınıfı hazırla 
	$up = new UPLOAD( $_FILES['dosyalar'] ); 

	// yüklenecek dosyalar hangi klasöre kayıt edilecek 
	$up->yolDizin('posticons'); 
	// yüklenecek dosyalar boyutu ve miktarı
	$up->minBoyut(1);
	$up->minDosya(1);
	// kabul edilecek tipler
	$up->tipKabul('gif, jpg, png');
	// varsa üstüne yazılsın mı
	$up->yazUstune(true);

	if( $up->baslat() === false ) 
	{
		$sayfabilgisi = $up->ilkHata();
		$sayfabilgisi = '<div class="errorbox">'.$sayfabilgisi.'</div>';
	}
	else
	{
		$sayfabilgisi = '<div class="successbox">Dosyalarınız başarıyla sisteme eklenmiştir.</div>';
	}
	unset($up);
}
?>

<h1>İkon Listesi &raquo; İkon Ekle</h1>

<?=$sayfabilgisi ?>

<form method="post" action="" enctype="multipart/form-data" >
<div>
<input name="dosyalar[]" type="file" size="45" maxlength="500"  />
<br>
<input name="dosyalar[]" type="file" size="45" maxlength="500"  />
<br>
<input name="dosyalar[]" type="file" size="45" maxlength="500"  />
<br>
<button name="submit" type="submit" style="width:334px; padding: 10px">Yükle</button>
</div>
</form>
</div>