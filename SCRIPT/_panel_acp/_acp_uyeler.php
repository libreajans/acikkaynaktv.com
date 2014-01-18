<?php
if (!defined('yakusha')) die('...');
if (!$_SESSION[SES]["ADMIN"]==1) exit ();

include($siteyolu."/_panel_acp/_temp/_t_adminbaslangic.php"); 

$duzenle 	= $_REQUEST['duzenle']; 	settype($user_id,"integer");
$uye_ekle 	= $_REQUEST['uyeekle']; 	settype($uye_ekle,"integer");
$delete 	= $_REQUEST["delete"]; 		settype($delete,"integer");

if ($delete > 0 )
{
	$vt->sql('UPDATE tv_users SET user_status = 0 WHERE user_id > 1 AND user_id = %u')->arg($delete)->sor();
	$islem_bilgisi = '<div class="errorbox">Üye yönetim yetkileri elinden alınmıştır</div>';
}
	
if ($duzenle > 0)
{
	include($siteyolu."/_panel_acp/_acp_uyeler_duzenle.php");
}
elseif ($uye_ekle > 0)
{
	include($siteyolu."/_panel_acp/_acp_uyeler_ekle.php");
}
else
{
	//sql sorgusu oluşturuluyor
	$vt->sql('SELECT user_id, user_username, user_email, user_status FROM tv_users')->sor();
	$sonuc = $vt->alHepsi();
	$adet = $vt->numRows();

	//sayfa içi oluşturuluyor, döne döne
	if ($adet)
	{
		for ( $i = 0; $i < $adet; $i++)
		{
			$user_id 		= $sonuc[$i]->user_id;
			$user_status 	= $sonuc[$i]->user_status;
			$user_username 	= $sonuc[$i]->user_username;
			$user_email 	= $sonuc[$i]->user_email;

			if ($i%2) $trcolor = "col2";  else $trcolor = "col1";

			//slash işaretleri temizleniyor
			$user_username = stripslashes($user_username);
			$user_email = stripslashes($user_email);
			$uyeduzenlelink = '<a title="Üye Düzenle" href="'.$acp_uyelerlink.'&amp;duzenle='.$user_id.'"><img src="'.SITELINK.'/_img/icon_edit.gif"> Düzenle</a>';
			$uyesillink = '<a title="Üye Sil" href="'.$acp_uyelerlink.'&amp;delete='.$user_id.'"><img src="'.SITELINK.'/_img/icon_delete.gif"> Sil</a>';
			
			$sayfabilgisi.= '<tr class="'.$trcolor.'">
				<td>'.$uyeduzenlelink.'</td>
				<td>'.$array_user_status[$user_status].'</td>
				<td>'.$user_username.'</td>
				<td>'.$user_email.'</td>
				<td>'.$uyesillink.'</td>
			</tr>';
		}
	}
	?>

	<a class="button1" href="<?=$acp_uyelerlink?>&uyeekle=1"><img src="<?=SITELINK?>/_img/icon_ekle.png">ÜYE EKLE</a>

	Bu paneli kullanarak üyelerinizin bilgilerini güncelleyebilir ve yetkilerini silebilirsiniz.<br><br>

	<?=$islem_bilgisi?>

	<table class="vitrinler" width="%100" border="0" cellpadding="3" cellspacing="3">
		<tr>
			<th></th>
			<th>durum</th>
			<th>ad, soyad</th>
			<th>eposta</th>
			<th></th>
		</tr>
		<?=$sayfabilgisi?>
	</table>
	<?php 
	} 
	include($siteyolu."/_panel_acp/_temp/_t_adminbitis.php");
?>