<?php
if (!defined('yakusha')) die('...');

//
// +---------------------------------------------------------------------------+
// | eburhan VT Class v1.5                                                     |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | Sýnýf adý      : eburhan VT Class                                         |
// | Versiyonu      : 1.5                                                      |
// | Görevi         : mySQL veritabaný yönetimini kolaylaþtýrmak               |
// | Gereksinimler  : mysql(i) eklentisi, php 5 ve yukarýsý                    |
// | Son güncelleme : 23 Ocak 2010                                             |
// |                                                                           |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | Programcý      : Erhan BURHAN                                             |
// | E-posta        : eburhan {at} gmail {dot} com                             |
// | Web adresi     : http://www.eburhan.com/                                  |
// |                                                                           |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | Copyright (C)                                                             |
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// +---------------------------------------------------------------------------+
//



class VT
{
// veritabaný deðiþkenleri
protected $vt_name;
protected $vt_user;
protected $vt_pass;
protected $vt_host;
protected $vt_link;

// sorgu deðiþkenleri
protected $sorguSonucu;
protected $sorguSayisi;
protected $sorguKaynak;
protected $sorguSuresi;
protected $sorguTarihi;

// iþlem deðiþkenleri
protected $insertID;
protected $numRows;
protected $affRows;

// cache deðiþkenleri
protected $cacheDurum;
protected $cacheLimit;
protected $cacheZaman;
protected $cacheDosya;

// hata ve çýktý deðiþkenleri
protected $hata;
protected $hataGercek;
protected $hataGoster;
protected $hataKaydet;
protected $hataDurdur;

// diðer deðiþkenler
protected $realEscape;
protected $debugBack;
protected $kayitYolu;
protected $almaModu;
protected $sql;


/**
* kurucu metotdur. sýnýf gereksinimlerini kontrol eder ve varsayýlan ayarlarý atar
* ### mümkün olduðunca bu metot içerisinde herhangi bir deðiþiklik yapmayýnýz ###
*
* @access public
*/
public function __construct()
{
if( version_compare(PHP_VERSION, '5.0.0') === -1 )
die('<strong>VT Error:</strong> bu sýnýfýnýn kullanýlabilmesi için enaz PHP 5 sürümü gereklidir.');

if( ! extension_loaded('mysqli') )
die('<strong>VT Error:</strong> bu sýnýfýnýn kullanýlabilmesi için "mysql(i)" eklentisi gereklidir.');

// baðlantý deðiþkenleri
$this->vt_name      = 'test';         // baðlanýlacak olan veritabaný adý
$this->vt_user      = 'root';         // veritabaný için kullanýcý adý
$this->vt_pass      = '';             // veritabaný için parola
$this->vt_host      = 'localhost';    // baðlanýlacak olan veritabanýnýn adresi
$this->vt_lang      = 'latin5';       // dil (lang) durumu
$this->vt_link      = null;           // baðlantý (link) durumu

// sorgu deðiþkenleri
$this->sorguSonucu  = null;           // sorgu sonucu
$this->sorguSayisi  = 0;              // toplam sorgu sayýsý
$this->sorguKaynak  = null;           // sorgu sonuçlarý veritabanýndan mý yoksa cache'den mi?
$this->sorguSuresi  = 0;              // sorgu ne kadar zaman aldý?
$this->sorguTarihi  = null;           // sorgu hangi tarihte yapýldý?

// iþlem deðiþkenleri
$this->insertID     = 0;             // ekledikten sonra oluþan ID numarasý
$this->numRows      = 0;             // toplam satýr sayýsý
$this->affRows      = 0;             // iþlemden etkilenen satýr sayýsý

// cache deðiþkenleri
$this->cacheDurum   = false;         // son cache durumunu tutar. önbellekleme yapýlacak mý yapýlmayacak mý?
$this->cacheLimit   = 0;             // numRows sayýsý bu deðere ulaþmadýðý sürece cache yapma
$this->cacheZaman   = 0;             // saniye cinsinden maksimum cache süresi
$this->cacheDosya   = null;          // cache dosyasýnýn tam yolunu tutan deðiþken

// hata ve çýktý deðiþkenleri
$this->hata         = array();       // hatalarý tutan dizi
$this->hataGercek   = false;         // veritabanýnýn ürettiði gerçek hatalar mý gösterilsin?
$this->hataGoster   = true;          // hatalar ekranda gösterilsin mi?
$this->hataKaydet   = true;          // hatalar bir dosyaya kaydedilsin mi?
$this->hataDurdur   = true;          // hata oluþtuðunda programdan çýkýlsýn mý?

// diðer deðiþkenler
$this->realEscape   = function_exists('mysqli_real_escape_string');
$this->debugBack    = function_exists('debug_backtrace');
$this->kayitYolu    = dirname(__FILE__).DIRECTORY_SEPARATOR.'kayitlar'.DIRECTORY_SEPARATOR;
$this->almaModu     = 'obj'; // veri alma modu
$this->sql          = null;
}



//---------------------------------------------------------------------------
//    Seçenek Belirleme
//---------------------------------------------------------------------------
/**
* veritabaný tarafýndan üretilen gerçek hata mesajlarýnýn göstirilip gösterilmeyeceðini belirler
*
* @access public
* @since 1.3
* @param bool gerçek hatalar gösterilsin mi? (true | false)
*/
public function hataGercek($val=true)
{
if( is_bool($val) )
$this->hataGercek = $val;

return $this;
}


/**
* bir hata oluþtuktan sonra, programdan çýkýlýp çýkýlmayacaðýný belirler
*
* @access public
* @param bool programdan çýkýlsýn mý? (true | false)
*/
public function hataDurdur($val=true)
{
if( is_bool($val) )
$this->hataDurdur = $val;

return $this;
}


/**
* oluþan hatalarýn ekranda gösterilip gösterilmeyeceðini belirler
*
* @access public
* @param bool hatalar gösterilsin mi? (true | false)
*/
public function hataGoster($val=true)
{
if( is_bool($val) )
$this->hataGoster = $val;

return $this;
}


/**
* oluþan hatalarýn bir dosyaya kaydedilip kaydedilmeyeceðini belirler
*
* @access public
* @param bool hatalar kaydedilsin mi? (true | false)
*/
public function hataKaydet($val=true)
{
if( is_bool($val) )
$this->hataKaydet = $val;

return $this;
}


/**
* verilerin kaydedileceði klasör yolu
*
* @access public
* @param string klasör yolu
* @return boolean
*/
public function kayitYolu($yol)
{
// klasör yolunun sonunda \ veya / var mý?
if( substr($yol, -1) != DIRECTORY_SEPARATOR )    $yol = $yol.DIRECTORY_SEPARATOR;
// klasör yolundaki dizin ayraçlarýný deðiþtir
$yol = preg_replace('%(?:\\\\|/)+%i', DIRECTORY_SEPARATOR, $yol);

// 1) klasör yoksa
if( ! file_exists($yol) && ! mkdir($yol, 0777) ) {
$this->_hataOlustur(__LINE__, __FUNCTION__, -1);
$this->_hataKontrol();
return false;
}

// 2) klasör varsa ama yazýlabilir deðilse
if( ! is_writable($yol) && ! chmod($yol, 0777) ) {
$this->_hataOlustur(__LINE__, __FUNCTION__, -2);
$this->_hataKontrol();
return false;
}

// herþey tamamsa 'kayitYolu' deðiþkenini güncelle
$this->kayitYolu = $yol;
return $this;
}


/**
* varsayýlan veri alma modunu deðiþtirir
*
* @access public
* @param string alma modu (obj, arr, num)
*/
public function almaModu($mod)
{
if( in_array($mod, array('obj', 'arr', 'num')) )
$this->almaModu = $mod;

return $this;
}


/**
* cache'e yazýlacak veri enaz kaç satýr olmalý?
*
* @access public
* @param integer minimum satýr sayýsý
*/
public function cacheLimit($min)
{
if( is_int($min) && $min > 0 )
$this->cacheLimit = $min;

return $this;
}


/**
* cache'e yazýlacak sorgular için varsayýlan cache zamaný
*
* @access public
* @param integer cache zamaný (dakika)
*/
public function cacheZaman($dk)
{
if( is_int($dk) && $dk > 0 )
$this->cacheZaman = $dk * 60; // saniyeye dönüþtü

return $this;
}



//---------------------------------------------------------------------------
//    Baðlantý açma & kapama
//---------------------------------------------------------------------------
/**
* veritabaný baðlantýsý açar
*
* @access public
* @param string veritabaný ismi
* @param string veritabaný kullanýcý adý
* @param string veritabaný parolasý
* @param string veritabaný adresi
* @return bool
*/
public function baglan()
{
$args = func_get_args();

// baðlantý ayarlarý bir ARRAY içindeyse
if( is_array($args[0]) ) {
$this->vt_name = is_string($args[0]['name']) ? $args[0]['name'] : 'test';
$this->vt_user = is_string($args[0]['user']) ? $args[0]['user'] : 'root';
$this->vt_pass = is_string($args[0]['pass']) ? $args[0]['pass'] : '';
$this->vt_host = is_string($args[0]['host']) ? $args[0]['host'] : 'localhost';
$this->vt_lang = is_string($args[0]['lang']) ? $args[0]['lang'] : 'latin5';
} else {
$this->vt_name = is_string($args[0]) ? $args[0] : 'test';
$this->vt_user = is_string($args[1]) ? $args[1] : 'root';
$this->vt_pass = is_string($args[2]) ? $args[2] : '';
$this->vt_host = is_string($args[3]) ? $args[3] : 'localhost';
$this->vt_lang = is_string($args[4]) ? $args[4] : 'latin5';
}

// baðlantý tutucuyu boþalt
$this->vt_link = null;

// veritabanýna baðlan
if( ! $this->_veritabaniBaglan() )  return false;
// if( ! $this->_veritabaniSec() ) return false;

return true;
}


/**
* farklý bir veritabaný seçer
*
* @access public
* @param string veritabaný adý
* @return bool
*/
public function sec($vt)
{
$this->vt_name = $vt;
$this->_veritabaniSec();

return $this;
}


/**
* veritabaný baðlantýsýný kapatýr
*
* @access public
* @return boolean
*/
public function __destruct()
{
// baðlantýyý kapat
if( $this->vt_link ) {
mysqli_close($this->vt_link);
$this->vt_link = null;
return;
}
}




//---------------------------------------------------------------------------
//    Sorgu oluþturma
//---------------------------------------------------------------------------
/**
* SQL cümleciðini atar
*
* @access public
* @param string SQL cümleciði
*/
public function sql($sql)
{
// girilen sql cümlesindeki gereksiz boþluklarý ve sekmeleri temizle
$this->sql = preg_replace('/\s\s+|\t\t+/', ' ', trim($sql));

return $this;
}


/**
* SQL cümleciðindeki argümanlarý alýr ve temizler
*
* @access public
*/
public function arg()
{
// argümanlarý al
$args = func_get_args();

// argümanlarýn herbirini temizlenmeye gönder :)
$args = array_map(array($this, '_temizle'), $args);

// temizlenmiþ argümanlarý %s ile deðiþtir
$this->sql = vsprintf($this->sql, $args);

return $this;
}


/**
* SQL cümleciðine bakarak verileri cache dosyasýndan veya veritabanýndan okur
*
* @access public
* @param integer cache zaman
*  @param integer cache limit
* @return boolean
*/
public function sor($cacheZaman=null, $cacheLimit=null)
{
// buraya kadar bir hata oluþtuysa çýk
if( count($this->hata) > 0 ) return false;

// önceki cache deðerlerini tutan deðiþkenler
$cacheZamanOnceki = $cacheLimitOnceki = null;

// Bu sorguda geçerli olacak Cache Zaman deðeri
if( ! is_null($cacheZaman) && (int) $cacheZaman >= 0 ) {
$cacheZamanOnceki = $this->cacheZaman;
$this->cacheZaman = $cacheZaman * 60; // 60 ile çarpýnca saniyeye dönüþtü
}

// Bu sorguda geçerli olacak Cache Limit deðeri
if( ! is_null($cacheLimit) && (int) $cacheLimit >= 0 ) {
$cacheLimitOnceki = $this->cacheZaman;
$this->cacheLimit = $cacheLimit;
}

// þartlar cache'den okuma yapmaya müsait mi?
if( $this->_cacheMusait() ){
// cache'den oku
$this->sorguSonucu = $this->_cacheOku();
$this->sorguKaynak = 'cache';
$this->numRows = count($this->sorguSonucu);
} else {
// herþey tamamsa veritabanýndan okuma yap
$this->sorguSonucu = $this->_veritabaniOku();
$this->sorguKaynak = 'veritabaný';

// sorgu sonucu FALSE deðilse ve cache kaydý isteniyorsa...
if( $this->sorguSonucu && $this->cacheZaman > 0 && ($this->numRows >= $cacheLimit) ) {
$this->_cacheYaz();
}
}

// daha önceki zamaný ve limit geri yükle
if( ! is_null($cacheZamanOnceki) ) { $this->cacheZaman = $cacheZamanOnceki; }
if( ! is_null($cacheLimitOnceki) ) { $this->cacheLimit = $cacheLimitOnceki; }

// sorgu sonucu ya FALSE olur ya da veritabanýndan dönen deðer
return ($this->sorguSonucu===false) ? false : true;
}



//---------------------------------------------------------------------------
//    Sorgu sonucunu alma
//---------------------------------------------------------------------------
/**
* Sorgu sonucunda elde edilen bütün verileri alýr
*
* @access public
* @param string veri alma modu (obj, arr, num)
* @return mixed
*/
public function alHepsi($mod=null)
{
// sorgu sonucunda geriye bir deðer dönmemiþse (boþsa)
if( empty($this->sorguSonucu) ) return array();

// dýþarýdan gelen mod geçerli deðilse, varsayýlaný kullan
if( in_array($mod, array('obj', 'arr', 'num'))===false ) {
$mod = $this->almaModu;
}

// nesne
if( $mod==='obj' )    return $this->sorguSonucu;
// dizi
if( $mod==='arr' )    return array_map('get_object_vars', $this->sorguSonucu);
// numaralandýrýlmýþ dizi
if( $mod==='num' ) {
$temp = array_map('get_object_vars', $this->sorguSonucu);
$temp = array_map('array_values', $temp);
return $temp;
}
}


/**
* Tek bir satýrdaki bütün verileri alýr
*
* @access public
* @param integer birden fazla satýr geriye döndüyse kaçýncý satýrýn yakalancak?
* @param string veri alma modu (obj, arr, num)
* @return mixed
*/
public function alSatir($sno=1, $mod=null)
{
// sorgu sonucunda geriye bir deðer dönmemiþse (boþsa)
if( empty($this->sorguSonucu) ) return array();

// diziler 0'dan baþladýðý için 1 eksilt. böylece,
// kullanýcý 1 girdiðinde dizinin 0. elemaný gelecek
$sno -= 1;

// dýþarýdan gelen mod geçerli deðilse, varsayýlaný kullan
if( in_array($mod, array('obj', 'arr', 'num'))===false ) {
$mod = $this->almaModu;
}

// satýr numarasý, dizi limitleri dýþýna çýkmamalý
if( ! is_int($sno) || $sno < 0 ) return array();
if( $sno >= $this->numRows ) return array();

// numaralandýrýlmýþ dizi
if( $mod==='num' )    return array_values(get_object_vars($this->sorguSonucu[$sno]));
// dizi
if( $mod==='arr' )    return get_object_vars($this->sorguSonucu[$sno]);
// nesne
if( $mod==='obj' )    return $this->sorguSonucu[$sno];
}


/**
* Yalnýzca bir tek veri alýr. alýnacak veri yoksa NULL geri döndürür
*
* @access public
* @return mixed
*/
public function alTek()
{
// sorgu sonucunda geriye bir deðer dönmemiþse (boþsa)
if( empty($this->sorguSonucu) ) return null;

$dizi = array_values(get_object_vars($this->sorguSonucu[0]));
return $dizi[0];
}


/**
* SQL cümleciðinin en son halini verir
*
* @access public
* @return string SQL cümleciðinin son hali
*/
public function alSql()
{
return $this->sql;
}



//---------------------------------------------------------------------------
//    Ýþlem sonucunu alma
//---------------------------------------------------------------------------
/**
* Son sorgudan, tablodaki kaç satýrýn etkilendiðini verir
*
* @access public
* @return integer
*/
public function affRows()
{
return $this->affRows;
}


/**
* Son sorgudan sonra elde edilen satýr sayýsý
*
* @access public
* @return integer
*/
public function numRows()
{
return $this->numRows;
}


/**
* En son eklenen verinin ID'si
*
* @access public
* @return integer
*/
public function insertID()
{
return $this->insertID;
}


/**
* Toplam sorgu sayýsýný verir
*
* @access public
* @return integer
*/
public function sorguSayisi()
{
return $this->sorguSayisi;
}


/**
* Son sorgu için harcanan süre
*
* @access public
* @return integer
*/
public function sorguSuresi()
{
return $this->sorguSuresi;
}



//---------------------------------------------------------------------------
//    Hata iþleme & Bilgi alma
//---------------------------------------------------------------------------
/**
* sýnýf içerisinde kullanýlan deðiþkenlerin bilgilerini verir
*
* @access public
* @return array
*/
public function bilgiVer()
{
return array (
'veritabani'    => $this->vt_name,
'kullanici'     => $this->vt_user,
'sunucu'        => $this->vt_host,
'link'          => $this->vt_link,
'sonSQL'        => $this->sql,
'ilkSonuc'      => isset($this->sorguSonucu[0]) ? $this->sorguSonucu[0] : array(),
'numRows'       => $this->numRows,
'affRows'       => $this->affRows,
'insertID'      => $this->insertID,
'sorguSayisi'   => $this->sorguSayisi,
'sorguKaynak'   => $this->sorguKaynak,
'sorguSuresi'   => $this->sorguSuresi,
'sorguTarihi'   => $this->sorguTarihi,
'almaModu'      => $this->almaModu,
'cacheLimit'    => $this->cacheLimit,
'cacheZaman'    => $this->cacheZaman,
'cacheDosya'    => $this->cacheDosya,
'ilkHataTR'     => count($this->hata) > 0 ? $this->hata[0]['user'] : null,
'ilkHataEN'     => count($this->hata) > 0 ? $this->hata[0]['real'] : null,
'kayitYolu'     => $this->kayitYolu
);
}


/**
* sýnýf içerisinde kullanýlan deðiþkenlerin bilgilerini ekrana yazdýrýr
*
* @access public
* @param boolean programdan çýkýlsýn mý?
*/
public function bilgiBas($exit=true)
{
$this->dump($this->bilgiVer());
if( $exit ) exit();
}


/**
* Herhangi bir iþlem sonucunu, formatlý bir þekilde ekrana yazdýrýr
*
* @access public
* @param mixed yazdýrýlacak veri
*/
public function dump($veri)
{
print '<pre>';
print_r( $veri );
print '</pre>';
}



//---------------------------------------------------------------------------
//    Yardýmcý fonksiyonlar
//---------------------------------------------------------------------------
/**
* hata çýktýsýný oluþturur
*
* @access protected
* @param string line
* @param string func
* @param string errNo
*/
protected function _hataOlustur($line, $func, $errNo)
{
// debug_backtrace() fonksiyonu varsa
if( $this->debugBack ){
// hatayý oluþtur
$hataIlk = debug_backtrace();
$hataSon = array();

foreach( $hataIlk AS $hata ){
// 'class' anahtarý yoksa diðer hataya geç
if( ! isset($hata['class']) ) continue;

// oluþan hatanýn sebebi bu class mý?
if( $hata['class'] === __CLASS__ ) {
array_push($hataSon, $hata);
}
}

// hatanýn en son oluþtuðu yerle ilgili bilgiler
$hataSon = end($hataSon);
} else {
$hataSon = array();
$hataSon['file'] = $this->_phpSelf();
$hataSon['line'] = $line;
$hataSon['function'] = $func;
}

array_push($this->hata, array(
'file' => $hataSon['file'],
'line' => $hataSon['line'],
'func' => __CLASS__.'::'.$hataSon['function'],
'user' => $this->_errUser($errNo),
'real' => $this->_errMsgDb()===false ? $this->_errUser($errNo) : $this->_errMsgDb(),
'sqlc' => $this->sql
));
}


/**
* oluþan hatalarýn kaydedilmesi ve gösterilmesi iþlemlerini kontrol eder
*
* @access protected
*/
protected function _hataKontrol()
{
// hatalarý dosyaya kaydet
if( $this->hataKaydet && count($this->hata)>0 ) {
$veri = "vt   : $this->vt_name".PHP_EOL.
"sql  : ".$this->hata[0]['sqlc'].PHP_EOL.
"hata : ".$this->hata[0]['real'].PHP_EOL.
"fonk : ".$this->hata[0]['func'].PHP_EOL.
"satir: ".$this->hata[0]['line'].PHP_EOL.
"dosya: ".$this->hata[0]['file'].PHP_EOL.
"zaman: ".date('d.m.Y H:i:s').PHP_EOL.PHP_EOL;

$this->_dosyayaKaydet($this->kayitYolu.date('d-m-Y').'.error', $veri);
}

// hatalarý ekranda göster
if( $this->hataGoster && count($this->hata)>0 ) {
printf(
'<pre class="vt_hata">'.PHP_EOL.
'<strong>VT HATA</strong>'.PHP_EOL.
'dosya : %s'.PHP_EOL.
'satir : %u'.PHP_EOL.
'mesaj : %s'.PHP_EOL.
'</pre>%s',
$this->hata[0]['file'],
$this->hata[0]['line'],
$this->hataGercek===false ? $this->hata[0]['user'] : $this->hata[0]['real'],
PHP_EOL
);

if( $this->hataDurdur ) exit();
}
}


/**
* veritabanýna baðlanýr
*
* @access protected
* @return boolean
*/
protected function _veritabaniBaglan()
{
// yeni bir baðlantý aç
$this->vt_link = mysqli_connect($this->vt_host, $this->vt_user, $this->vt_pass, $this->vt_name);
if( ! $this->vt_link ){
$this->_hataOlustur(__LINE__, __FUNCTION__, $this->_errNoDb());
$this->_hataKontrol();
return false;
}
mysqli_set_charset($this->vt_link, $this->vt_lang);
return true;
}


/**
* veritabaný seçer
*
* @access protected
* @return boolean
*/
protected function _veritabaniSec()
{
if( ! mysqli_select_db($this->vt_name, $this->vt_link) ){
$this->_hataOlustur(__LINE__, __FUNCTION__, $this->_errNoDb());
$this->_hataKontrol();
return false;
}

return true;
}


/**
* veritabanýna sorgu gönderip sonuçlarýný deðerlendirir
*
* @access protected
* @return mixed
*/
protected function _veritabaniOku()
{
// veritabaný baðlantýsý baþlatýlmamýþsa...
if( ! $this->vt_link ){
$this->_hataOlustur(__LINE__, __FUNCTION__, -3);
$this->_hataKontrol();
return false;
}

// sorguyu gerçekleþtir
$basla = $this->_timer();
$sorgu = mysqli_query($this->vt_link, $this->sql);
$bitir = $this->_timer();

// sorgu istatistikleri
$this->sorguSuresi = ($bitir-$basla);
$this->sorguTarihi = date('d.m.Y H:i:s');
$this->sorguSayisi++;

// bir önceki sorgunun bazý bilgilerini resetle
$this->numRows = $this->insertID = $this->affRows = 0;

// 1. sorgu baþarýsýz ise
if( $sorgu === false ) {
$this->_hataOlustur(__LINE__, __FUNCTION__, $this->_errNoDb());
$this->_hataKontrol();
return false;
}

// 2. sorgu baþarýlý ama geriye bir sonuç döndürmüyorsa
// INSERT, UPDATE, DELETE veya REPLACE türündeki sorgular
if( $sorgu === true ) {
$this->insertID = mysqli_insert_id($this->vt_link);
$this->affRows  = mysqli_affected_rows($this->vt_link);
return true;
}

// 3. sorgu baþarýlý ve geriye bir sonuç döndürdüyse
// SELECT veya SHOW türündeki sorgular
$sonuc = array();
while( $satir = mysqli_fetch_object($sorgu) ) {
$sonuc[] = $satir;
}
mysqli_free_result($sorgu);
$this->numRows = count($sonuc);
return $sonuc;
}


/**
* cache'den okuma yapýlmaya müsait mi?
*
* @access protected
* @return boolean
*/
protected function _cacheMusait()
{
// cache zamaný 0 ise cache özelliði kapalý demektir
if( $this->cacheZaman === 0 ) return false;

// eðer SELECT ve SHOW dýþýnda bir sorgu yapýldýysa cache yapýlamaz!
if( ! preg_match('/^(select|show)\s/i', $this->sql) ) return false;

// cache dosyasýnýn yolu
$this->cacheDosya = $this->kayitYolu.md5($this->vt_name.$this->sql).'.cache';

// cache dosyasý yoksa geri dön
if( ! file_exists($this->cacheDosya) ) return false;

// cache zamaný geçmiþse geri dön (önce cache dosyasýný sil)
if( time() - filemtime($this->cacheDosya) > $this->cacheZaman ) {
unlink($this->cacheDosya);
return false;
}

// herþey tamamsa TRUE geri döndür
return true;
}


/**
* cache dosyasýndan okuma yapar
*
* @access protected
* @return string
*/
protected function _cacheOku()
{
$basla = $this->_timer();
$verim = unserialize(file_get_contents($this->cacheDosya));
$bitir = $this->_timer();

// sorgu istatistikleri
$this->sorguSuresi = ($bitir - $basla);
$this->sorguTarihi = date('d.m.Y H:i:s');

return $verim;
}


/**
* cache dosyasýna veri yazar
*
* @access protected
*/
protected function _cacheYaz()
{
// 'numRows' deðeri ancak 'cacheLimit' deðerinden büyükse cache yap
if( $this->cacheLimit === 0 || ($this->cacheLimit <= $this->numRows) )
$this->_dosyayaKaydet($this->cacheDosya, serialize($this->sorguSonucu));
}


/**
* Zararlý olabilecek verileri temizler
*
* @access protected
* @param string temizlenecek veri
* @return mixed
*/
protected function _temizle($veri)
{
if( is_null($veri) ) return 'NULL';
if( is_numeric($veri) ) return $veri;

if( get_magic_quotes_gpc() ) {
$veri = stripslashes($veri);
}

if( $this->realEscape ) {
$veri = mysqli_real_escape_string($this->vt_link, $veri);
} else {
$veri = addslashes($veri);
}

return "'$veri'";
}


/**
* Veritabanýnýn kendi ürettiði hata numarasý
*
* @access protected
* @return integer
*/
protected function _errNoDb()
{
if( $this->vt_link )
return mysqli_errno($this->vt_link);
}


/**
* Veritabanýnýn kendi ürettiði hata mesajý
*
* @access protected
* @return string
*/
protected function _errMsgDb()
{
if( $this->vt_link )
return mysqli_error($this->vt_link);
}


/**
* hata numarasýna karþýlýk gelen hata mesajýný verir
*
* @param integer hata numarasý
* @access protected
* @return string
*/
protected function _errUser($errNo)
{
// eksi (-) hatalar kullanýcý hatalarý
// artý (+) hatalar veritabaný hatalarý
$hata = array(
-1   => 'Kayýt klasörü bulunamýyor',
-2   => 'Kayýt klasörü yazýlabilir deðil',
-3   => 'Veritabaný baðlantýsý baþlatýlmamýþ görünüyor',

// Server Error Codes and Messages
1044 => 'Eriþim engellendi. Veritabaný ismini kontrol edin',
1045 => 'Eriþim engellendi. Kullanýcý adýný veya þifreyi kontrol edin',
1046 => 'Veritabaný seçilemedi. Veritabaný ismini kontrol edin',
1048 => 'Ýlgili kolona (sütuna) boþ veri giremezsiniz',
1049 => 'Bilinmeyen veritabaný. Veritabaný ismini kontrol edin',
1050 => 'Zaten var olan bir tabloyu yeniden oluþturamazsýnýz',
1051 => 'Bilinmeyen tablo ismi. Sql cümleciðini kontrol edin',
1054 => 'Bilinmeyen kolon (sütun) ismi. Sql cümleciðini kontrol edin',
1062 => 'Daha önceden zaten varolan bir kayýt eklenemez',
1064 => 'Sorgu çalýþtýrýlamadý. Sql cümleciðini kontrol edin',
1115 => 'Bilinmeyen karakter seti. Sql cümleciðini kontrol edin',
1136 => 'Kolon sayýsý ile deðer sayýsý eþleþmiyor',
1146 => 'Bilinmeyen tablo ismi. Sql cümleciðini kontrol edin',
1193 => 'Bilinmeyen sistem deðiþkeni',
1227 => 'Eriþim engellendi. Bu iþlem için gerekli yetkiniz yok',
1292 => 'Yanlýþ bir deðer girilmeye çalýþýyor',
1364 => 'Varsayýlan deðere sahip olmasý gereken bir alan var',
1366 => 'Girilmeye çalýþýlan verilerden birisi sayýsal deðil',
1406 => 'Girilmeye çalýþýlan verilerden birisi gereðinden fazla uzun',

// Client Error Codes and Messages
2000 => 'Bilinmeyen MySQL hatasý',
2003 => 'Veritabaný sunucusuna baðlanýlamadý. Adresi kontrol edin',
2005 => 'Bilinmeyen veritabaný sunucusu. Adresi kontrol edin'
);

if( isset($hata[$errNo]) ) {
return $hata[$errNo];
}

return 'Tanýmlanmamýþ bir hata oluþtu. Hata no: '.$errNo;
}


/**
* yolu verilen dosyaya veri yazar
*
* @access protected
* @param string dosya yolu
* @param string dosyaya yazýlacak veri
*/
protected function _dosyayaKaydet($yol, $veri)
{
$fp = fopen($yol, 'a');
chmod($yol, 0777);

if( flock($fp, LOCK_EX)) {
fwrite($fp, $veri);
flock($fp, LOCK_UN);
}

chmod($yol, 0750);
fclose($fp);
}


/**
* zaman ölçümleri yapmak için kullanýlýr
*
* @access protected
*/
protected function _timer()
{
return microtime(true);
}


/**
* bu sýnýfý o anda hangi sayfanýn kullandýðýný belirler
*
* @access protected
* @return string geçerli sayfanýn yolu
*/
protected function _phpSelf()
{
$yol = strip_tags($_SERVER['PHP_SELF']);
$yol = substr($yol, 0, 200);
$yol = htmlentities(trim($yol), ENT_QUOTES);

return $yol;
}

}//sýnýf sonu
