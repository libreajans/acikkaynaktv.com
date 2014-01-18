<?php
# oturum dosyası
session_start();

//genel oturumu başlatıyoruz
if ( !isset($_SESSION[SES]) )
{
	$_SESSION[SES]["ip"] = $_SERVER["REMOTE_ADDR"]; // Bağlanırken kullanılan IP
	$_SESSION[SES]["tarayici"] = $_SERVER["HTTP_USER_AGENT"]; // Bağlantı hangi tarayıcı ile yapılmış?
	$_SESSION[SES]["ilkerisim"] = time(); // İlk bağlantının IP si
	$_SESSION[SES]["sonerisim"] = time(); // En son yapılan erişim zamanı
	$_SESSION[SES]["giris"] = 0;
	$_SESSION[SES]["giristar"] = 0;
	$_SESSION[SES]["sessionstarttime"] = $simdikizaman;
}
else
{
	$_SESSION[SES]["sonerisim"] = time(); // En son yapılan erişim zamanı
}
?>