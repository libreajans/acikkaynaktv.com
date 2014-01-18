<?php
if (!defined('yakusha')) die('...');

//
// +---------------------------------------------------------------------------+
// | eburhan VT Class v1.5                                                     |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | S�n�f ad�      : eburhan VT Class                                         |
// | Versiyonu      : 1.5                                                      |
// | G�revi         : mySQL veritaban� y�netimini kolayla�t�rmak               |
// | Gereksinimler  : mysql(i) eklentisi, php 5 ve yukar�s�                    |
// | Son g�ncelleme : 23 Ocak 2010                                             |
// |                                                                           |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | Programc�      : Erhan BURHAN                                             |
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
// veritaban� de�i�kenleri
protected $vt_name;
protected $vt_user;
protected $vt_pass;
protected $vt_host;
protected $vt_link;

// sorgu de�i�kenleri
protected $sorguSonucu;
protected $sorguSayisi;
protected $sorguKaynak;
protected $sorguSuresi;
protected $sorguTarihi;

// i�lem de�i�kenleri
protected $insertID;
protected $numRows;
protected $affRows;

// cache de�i�kenleri
protected $cacheDurum;
protected $cacheLimit;
protected $cacheZaman;
protected $cacheDosya;

// hata ve ��kt� de�i�kenleri
protected $hata;
protected $hataGercek;
protected $hataGoster;
protected $hataKaydet;
protected $hataDurdur;

// di�er de�i�kenler
protected $realEscape;
protected $debugBack;
protected $kayitYolu;
protected $almaModu;
protected $sql;


/**
* kurucu metotdur. s�n�f gereksinimlerini kontrol eder ve varsay�lan ayarlar� atar
* ### m�mk�n oldu�unca bu metot i�erisinde herhangi bir de�i�iklik yapmay�n�z ###
*
* @access public
*/
public function __construct()
{
if( version_compare(PHP_VERSION, '5.0.0') === -1 )
die('<strong>VT Error:</strong> bu s�n�f�n�n kullan�labilmesi i�in enaz PHP 5 s�r�m� gereklidir.');

if( ! extension_loaded('mysqli') )
die('<strong>VT Error:</strong> bu s�n�f�n�n kullan�labilmesi i�in "mysql(i)" eklentisi gereklidir.');

// ba�lant� de�i�kenleri
$this->vt_name      = 'test';         // ba�lan�lacak olan veritaban� ad�
$this->vt_user      = 'root';         // veritaban� i�in kullan�c� ad�
$this->vt_pass      = '';             // veritaban� i�in parola
$this->vt_host      = 'localhost';    // ba�lan�lacak olan veritaban�n�n adresi
$this->vt_lang      = 'latin5';       // dil (lang) durumu
$this->vt_link      = null;           // ba�lant� (link) durumu

// sorgu de�i�kenleri
$this->sorguSonucu  = null;           // sorgu sonucu
$this->sorguSayisi  = 0;              // toplam sorgu say�s�
$this->sorguKaynak  = null;           // sorgu sonu�lar� veritaban�ndan m� yoksa cache'den mi?
$this->sorguSuresi  = 0;              // sorgu ne kadar zaman ald�?
$this->sorguTarihi  = null;           // sorgu hangi tarihte yap�ld�?

// i�lem de�i�kenleri
$this->insertID     = 0;             // ekledikten sonra olu�an ID numaras�
$this->numRows      = 0;             // toplam sat�r say�s�
$this->affRows      = 0;             // i�lemden etkilenen sat�r say�s�

// cache de�i�kenleri
$this->cacheDurum   = false;         // son cache durumunu tutar. �nbellekleme yap�lacak m� yap�lmayacak m�?
$this->cacheLimit   = 0;             // numRows say�s� bu de�ere ula�mad��� s�rece cache yapma
$this->cacheZaman   = 0;             // saniye cinsinden maksimum cache s�resi
$this->cacheDosya   = null;          // cache dosyas�n�n tam yolunu tutan de�i�ken

// hata ve ��kt� de�i�kenleri
$this->hata         = array();       // hatalar� tutan dizi
$this->hataGercek   = false;         // veritaban�n�n �retti�i ger�ek hatalar m� g�sterilsin?
$this->hataGoster   = true;          // hatalar ekranda g�sterilsin mi?
$this->hataKaydet   = true;          // hatalar bir dosyaya kaydedilsin mi?
$this->hataDurdur   = true;          // hata olu�tu�unda programdan ��k�ls�n m�?

// di�er de�i�kenler
$this->realEscape   = function_exists('mysqli_real_escape_string');
$this->debugBack    = function_exists('debug_backtrace');
$this->kayitYolu    = dirname(__FILE__).DIRECTORY_SEPARATOR.'kayitlar'.DIRECTORY_SEPARATOR;
$this->almaModu     = 'obj'; // veri alma modu
$this->sql          = null;
}



//---------------------------------------------------------------------------
//    Se�enek Belirleme
//---------------------------------------------------------------------------
/**
* veritaban� taraf�ndan �retilen ger�ek hata mesajlar�n�n g�stirilip g�sterilmeyece�ini belirler
*
* @access public
* @since 1.3
* @param bool ger�ek hatalar g�sterilsin mi? (true | false)
*/
public function hataGercek($val=true)
{
if( is_bool($val) )
$this->hataGercek = $val;

return $this;
}


/**
* bir hata olu�tuktan sonra, programdan ��k�l�p ��k�lmayaca��n� belirler
*
* @access public
* @param bool programdan ��k�ls�n m�? (true | false)
*/
public function hataDurdur($val=true)
{
if( is_bool($val) )
$this->hataDurdur = $val;

return $this;
}


/**
* olu�an hatalar�n ekranda g�sterilip g�sterilmeyece�ini belirler
*
* @access public
* @param bool hatalar g�sterilsin mi? (true | false)
*/
public function hataGoster($val=true)
{
if( is_bool($val) )
$this->hataGoster = $val;

return $this;
}


/**
* olu�an hatalar�n bir dosyaya kaydedilip kaydedilmeyece�ini belirler
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
* verilerin kaydedilece�i klas�r yolu
*
* @access public
* @param string klas�r yolu
* @return boolean
*/
public function kayitYolu($yol)
{
// klas�r yolunun sonunda \ veya / var m�?
if( substr($yol, -1) != DIRECTORY_SEPARATOR )    $yol = $yol.DIRECTORY_SEPARATOR;
// klas�r yolundaki dizin ayra�lar�n� de�i�tir
$yol = preg_replace('%(?:\\\\|/)+%i', DIRECTORY_SEPARATOR, $yol);

// 1) klas�r yoksa
if( ! file_exists($yol) && ! mkdir($yol, 0777) ) {
$this->_hataOlustur(__LINE__, __FUNCTION__, -1);
$this->_hataKontrol();
return false;
}

// 2) klas�r varsa ama yaz�labilir de�ilse
if( ! is_writable($yol) && ! chmod($yol, 0777) ) {
$this->_hataOlustur(__LINE__, __FUNCTION__, -2);
$this->_hataKontrol();
return false;
}

// her�ey tamamsa 'kayitYolu' de�i�kenini g�ncelle
$this->kayitYolu = $yol;
return $this;
}


/**
* varsay�lan veri alma modunu de�i�tirir
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
* cache'e yaz�lacak veri enaz ka� sat�r olmal�?
*
* @access public
* @param integer minimum sat�r say�s�
*/
public function cacheLimit($min)
{
if( is_int($min) && $min > 0 )
$this->cacheLimit = $min;

return $this;
}


/**
* cache'e yaz�lacak sorgular i�in varsay�lan cache zaman�
*
* @access public
* @param integer cache zaman� (dakika)
*/
public function cacheZaman($dk)
{
if( is_int($dk) && $dk > 0 )
$this->cacheZaman = $dk * 60; // saniyeye d�n��t�

return $this;
}



//---------------------------------------------------------------------------
//    Ba�lant� a�ma & kapama
//---------------------------------------------------------------------------
/**
* veritaban� ba�lant�s� a�ar
*
* @access public
* @param string veritaban� ismi
* @param string veritaban� kullan�c� ad�
* @param string veritaban� parolas�
* @param string veritaban� adresi
* @return bool
*/
public function baglan()
{
$args = func_get_args();

// ba�lant� ayarlar� bir ARRAY i�indeyse
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

// ba�lant� tutucuyu bo�alt
$this->vt_link = null;

// veritaban�na ba�lan
if( ! $this->_veritabaniBaglan() )  return false;
// if( ! $this->_veritabaniSec() ) return false;

return true;
}


/**
* farkl� bir veritaban� se�er
*
* @access public
* @param string veritaban� ad�
* @return bool
*/
public function sec($vt)
{
$this->vt_name = $vt;
$this->_veritabaniSec();

return $this;
}


/**
* veritaban� ba�lant�s�n� kapat�r
*
* @access public
* @return boolean
*/
public function __destruct()
{
// ba�lant�y� kapat
if( $this->vt_link ) {
mysqli_close($this->vt_link);
$this->vt_link = null;
return;
}
}




//---------------------------------------------------------------------------
//    Sorgu olu�turma
//---------------------------------------------------------------------------
/**
* SQL c�mleci�ini atar
*
* @access public
* @param string SQL c�mleci�i
*/
public function sql($sql)
{
// girilen sql c�mlesindeki gereksiz bo�luklar� ve sekmeleri temizle
$this->sql = preg_replace('/\s\s+|\t\t+/', ' ', trim($sql));

return $this;
}


/**
* SQL c�mleci�indeki arg�manlar� al�r ve temizler
*
* @access public
*/
public function arg()
{
// arg�manlar� al
$args = func_get_args();

// arg�manlar�n herbirini temizlenmeye g�nder :)
$args = array_map(array($this, '_temizle'), $args);

// temizlenmi� arg�manlar� %s ile de�i�tir
$this->sql = vsprintf($this->sql, $args);

return $this;
}


/**
* SQL c�mleci�ine bakarak verileri cache dosyas�ndan veya veritaban�ndan okur
*
* @access public
* @param integer cache zaman
*  @param integer cache limit
* @return boolean
*/
public function sor($cacheZaman=null, $cacheLimit=null)
{
// buraya kadar bir hata olu�tuysa ��k
if( count($this->hata) > 0 ) return false;

// �nceki cache de�erlerini tutan de�i�kenler
$cacheZamanOnceki = $cacheLimitOnceki = null;

// Bu sorguda ge�erli olacak Cache Zaman de�eri
if( ! is_null($cacheZaman) && (int) $cacheZaman >= 0 ) {
$cacheZamanOnceki = $this->cacheZaman;
$this->cacheZaman = $cacheZaman * 60; // 60 ile �arp�nca saniyeye d�n��t�
}

// Bu sorguda ge�erli olacak Cache Limit de�eri
if( ! is_null($cacheLimit) && (int) $cacheLimit >= 0 ) {
$cacheLimitOnceki = $this->cacheZaman;
$this->cacheLimit = $cacheLimit;
}

// �artlar cache'den okuma yapmaya m�sait mi?
if( $this->_cacheMusait() ){
// cache'den oku
$this->sorguSonucu = $this->_cacheOku();
$this->sorguKaynak = 'cache';
$this->numRows = count($this->sorguSonucu);
} else {
// her�ey tamamsa veritaban�ndan okuma yap
$this->sorguSonucu = $this->_veritabaniOku();
$this->sorguKaynak = 'veritaban�';

// sorgu sonucu FALSE de�ilse ve cache kayd� isteniyorsa...
if( $this->sorguSonucu && $this->cacheZaman > 0 && ($this->numRows >= $cacheLimit) ) {
$this->_cacheYaz();
}
}

// daha �nceki zaman� ve limit geri y�kle
if( ! is_null($cacheZamanOnceki) ) { $this->cacheZaman = $cacheZamanOnceki; }
if( ! is_null($cacheLimitOnceki) ) { $this->cacheLimit = $cacheLimitOnceki; }

// sorgu sonucu ya FALSE olur ya da veritaban�ndan d�nen de�er
return ($this->sorguSonucu===false) ? false : true;
}



//---------------------------------------------------------------------------
//    Sorgu sonucunu alma
//---------------------------------------------------------------------------
/**
* Sorgu sonucunda elde edilen b�t�n verileri al�r
*
* @access public
* @param string veri alma modu (obj, arr, num)
* @return mixed
*/
public function alHepsi($mod=null)
{
// sorgu sonucunda geriye bir de�er d�nmemi�se (bo�sa)
if( empty($this->sorguSonucu) ) return array();

// d��ar�dan gelen mod ge�erli de�ilse, varsay�lan� kullan
if( in_array($mod, array('obj', 'arr', 'num'))===false ) {
$mod = $this->almaModu;
}

// nesne
if( $mod==='obj' )    return $this->sorguSonucu;
// dizi
if( $mod==='arr' )    return array_map('get_object_vars', $this->sorguSonucu);
// numaraland�r�lm�� dizi
if( $mod==='num' ) {
$temp = array_map('get_object_vars', $this->sorguSonucu);
$temp = array_map('array_values', $temp);
return $temp;
}
}


/**
* Tek bir sat�rdaki b�t�n verileri al�r
*
* @access public
* @param integer birden fazla sat�r geriye d�nd�yse ka��nc� sat�r�n yakalancak?
* @param string veri alma modu (obj, arr, num)
* @return mixed
*/
public function alSatir($sno=1, $mod=null)
{
// sorgu sonucunda geriye bir de�er d�nmemi�se (bo�sa)
if( empty($this->sorguSonucu) ) return array();

// diziler 0'dan ba�lad��� i�in 1 eksilt. b�ylece,
// kullan�c� 1 girdi�inde dizinin 0. eleman� gelecek
$sno -= 1;

// d��ar�dan gelen mod ge�erli de�ilse, varsay�lan� kullan
if( in_array($mod, array('obj', 'arr', 'num'))===false ) {
$mod = $this->almaModu;
}

// sat�r numaras�, dizi limitleri d���na ��kmamal�
if( ! is_int($sno) || $sno < 0 ) return array();
if( $sno >= $this->numRows ) return array();

// numaraland�r�lm�� dizi
if( $mod==='num' )    return array_values(get_object_vars($this->sorguSonucu[$sno]));
// dizi
if( $mod==='arr' )    return get_object_vars($this->sorguSonucu[$sno]);
// nesne
if( $mod==='obj' )    return $this->sorguSonucu[$sno];
}


/**
* Yaln�zca bir tek veri al�r. al�nacak veri yoksa NULL geri d�nd�r�r
*
* @access public
* @return mixed
*/
public function alTek()
{
// sorgu sonucunda geriye bir de�er d�nmemi�se (bo�sa)
if( empty($this->sorguSonucu) ) return null;

$dizi = array_values(get_object_vars($this->sorguSonucu[0]));
return $dizi[0];
}


/**
* SQL c�mleci�inin en son halini verir
*
* @access public
* @return string SQL c�mleci�inin son hali
*/
public function alSql()
{
return $this->sql;
}



//---------------------------------------------------------------------------
//    ��lem sonucunu alma
//---------------------------------------------------------------------------
/**
* Son sorgudan, tablodaki ka� sat�r�n etkilendi�ini verir
*
* @access public
* @return integer
*/
public function affRows()
{
return $this->affRows;
}


/**
* Son sorgudan sonra elde edilen sat�r say�s�
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
* Toplam sorgu say�s�n� verir
*
* @access public
* @return integer
*/
public function sorguSayisi()
{
return $this->sorguSayisi;
}


/**
* Son sorgu i�in harcanan s�re
*
* @access public
* @return integer
*/
public function sorguSuresi()
{
return $this->sorguSuresi;
}



//---------------------------------------------------------------------------
//    Hata i�leme & Bilgi alma
//---------------------------------------------------------------------------
/**
* s�n�f i�erisinde kullan�lan de�i�kenlerin bilgilerini verir
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
* s�n�f i�erisinde kullan�lan de�i�kenlerin bilgilerini ekrana yazd�r�r
*
* @access public
* @param boolean programdan ��k�ls�n m�?
*/
public function bilgiBas($exit=true)
{
$this->dump($this->bilgiVer());
if( $exit ) exit();
}


/**
* Herhangi bir i�lem sonucunu, formatl� bir �ekilde ekrana yazd�r�r
*
* @access public
* @param mixed yazd�r�lacak veri
*/
public function dump($veri)
{
print '<pre>';
print_r( $veri );
print '</pre>';
}



//---------------------------------------------------------------------------
//    Yard�mc� fonksiyonlar
//---------------------------------------------------------------------------
/**
* hata ��kt�s�n� olu�turur
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
// hatay� olu�tur
$hataIlk = debug_backtrace();
$hataSon = array();

foreach( $hataIlk AS $hata ){
// 'class' anahtar� yoksa di�er hataya ge�
if( ! isset($hata['class']) ) continue;

// olu�an hatan�n sebebi bu class m�?
if( $hata['class'] === __CLASS__ ) {
array_push($hataSon, $hata);
}
}

// hatan�n en son olu�tu�u yerle ilgili bilgiler
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
* olu�an hatalar�n kaydedilmesi ve g�sterilmesi i�lemlerini kontrol eder
*
* @access protected
*/
protected function _hataKontrol()
{
// hatalar� dosyaya kaydet
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

// hatalar� ekranda g�ster
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
* veritaban�na ba�lan�r
*
* @access protected
* @return boolean
*/
protected function _veritabaniBaglan()
{
// yeni bir ba�lant� a�
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
* veritaban� se�er
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
* veritaban�na sorgu g�nderip sonu�lar�n� de�erlendirir
*
* @access protected
* @return mixed
*/
protected function _veritabaniOku()
{
// veritaban� ba�lant�s� ba�lat�lmam��sa...
if( ! $this->vt_link ){
$this->_hataOlustur(__LINE__, __FUNCTION__, -3);
$this->_hataKontrol();
return false;
}

// sorguyu ger�ekle�tir
$basla = $this->_timer();
$sorgu = mysqli_query($this->vt_link, $this->sql);
$bitir = $this->_timer();

// sorgu istatistikleri
$this->sorguSuresi = ($bitir-$basla);
$this->sorguTarihi = date('d.m.Y H:i:s');
$this->sorguSayisi++;

// bir �nceki sorgunun baz� bilgilerini resetle
$this->numRows = $this->insertID = $this->affRows = 0;

// 1. sorgu ba�ar�s�z ise
if( $sorgu === false ) {
$this->_hataOlustur(__LINE__, __FUNCTION__, $this->_errNoDb());
$this->_hataKontrol();
return false;
}

// 2. sorgu ba�ar�l� ama geriye bir sonu� d�nd�rm�yorsa
// INSERT, UPDATE, DELETE veya REPLACE t�r�ndeki sorgular
if( $sorgu === true ) {
$this->insertID = mysqli_insert_id($this->vt_link);
$this->affRows  = mysqli_affected_rows($this->vt_link);
return true;
}

// 3. sorgu ba�ar�l� ve geriye bir sonu� d�nd�rd�yse
// SELECT veya SHOW t�r�ndeki sorgular
$sonuc = array();
while( $satir = mysqli_fetch_object($sorgu) ) {
$sonuc[] = $satir;
}
mysqli_free_result($sorgu);
$this->numRows = count($sonuc);
return $sonuc;
}


/**
* cache'den okuma yap�lmaya m�sait mi?
*
* @access protected
* @return boolean
*/
protected function _cacheMusait()
{
// cache zaman� 0 ise cache �zelli�i kapal� demektir
if( $this->cacheZaman === 0 ) return false;

// e�er SELECT ve SHOW d���nda bir sorgu yap�ld�ysa cache yap�lamaz!
if( ! preg_match('/^(select|show)\s/i', $this->sql) ) return false;

// cache dosyas�n�n yolu
$this->cacheDosya = $this->kayitYolu.md5($this->vt_name.$this->sql).'.cache';

// cache dosyas� yoksa geri d�n
if( ! file_exists($this->cacheDosya) ) return false;

// cache zaman� ge�mi�se geri d�n (�nce cache dosyas�n� sil)
if( time() - filemtime($this->cacheDosya) > $this->cacheZaman ) {
unlink($this->cacheDosya);
return false;
}

// her�ey tamamsa TRUE geri d�nd�r
return true;
}


/**
* cache dosyas�ndan okuma yapar
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
* cache dosyas�na veri yazar
*
* @access protected
*/
protected function _cacheYaz()
{
// 'numRows' de�eri ancak 'cacheLimit' de�erinden b�y�kse cache yap
if( $this->cacheLimit === 0 || ($this->cacheLimit <= $this->numRows) )
$this->_dosyayaKaydet($this->cacheDosya, serialize($this->sorguSonucu));
}


/**
* Zararl� olabilecek verileri temizler
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
* Veritaban�n�n kendi �retti�i hata numaras�
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
* Veritaban�n�n kendi �retti�i hata mesaj�
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
* hata numaras�na kar��l�k gelen hata mesaj�n� verir
*
* @param integer hata numaras�
* @access protected
* @return string
*/
protected function _errUser($errNo)
{
// eksi (-) hatalar kullan�c� hatalar�
// art� (+) hatalar veritaban� hatalar�
$hata = array(
-1   => 'Kay�t klas�r� bulunam�yor',
-2   => 'Kay�t klas�r� yaz�labilir de�il',
-3   => 'Veritaban� ba�lant�s� ba�lat�lmam�� g�r�n�yor',

// Server Error Codes and Messages
1044 => 'Eri�im engellendi. Veritaban� ismini kontrol edin',
1045 => 'Eri�im engellendi. Kullan�c� ad�n� veya �ifreyi kontrol edin',
1046 => 'Veritaban� se�ilemedi. Veritaban� ismini kontrol edin',
1048 => '�lgili kolona (s�tuna) bo� veri giremezsiniz',
1049 => 'Bilinmeyen veritaban�. Veritaban� ismini kontrol edin',
1050 => 'Zaten var olan bir tabloyu yeniden olu�turamazs�n�z',
1051 => 'Bilinmeyen tablo ismi. Sql c�mleci�ini kontrol edin',
1054 => 'Bilinmeyen kolon (s�tun) ismi. Sql c�mleci�ini kontrol edin',
1062 => 'Daha �nceden zaten varolan bir kay�t eklenemez',
1064 => 'Sorgu �al��t�r�lamad�. Sql c�mleci�ini kontrol edin',
1115 => 'Bilinmeyen karakter seti. Sql c�mleci�ini kontrol edin',
1136 => 'Kolon say�s� ile de�er say�s� e�le�miyor',
1146 => 'Bilinmeyen tablo ismi. Sql c�mleci�ini kontrol edin',
1193 => 'Bilinmeyen sistem de�i�keni',
1227 => 'Eri�im engellendi. Bu i�lem i�in gerekli yetkiniz yok',
1292 => 'Yanl�� bir de�er girilmeye �al���yor',
1364 => 'Varsay�lan de�ere sahip olmas� gereken bir alan var',
1366 => 'Girilmeye �al���lan verilerden birisi say�sal de�il',
1406 => 'Girilmeye �al���lan verilerden birisi gere�inden fazla uzun',

// Client Error Codes and Messages
2000 => 'Bilinmeyen MySQL hatas�',
2003 => 'Veritaban� sunucusuna ba�lan�lamad�. Adresi kontrol edin',
2005 => 'Bilinmeyen veritaban� sunucusu. Adresi kontrol edin'
);

if( isset($hata[$errNo]) ) {
return $hata[$errNo];
}

return 'Tan�mlanmam�� bir hata olu�tu. Hata no: '.$errNo;
}


/**
* yolu verilen dosyaya veri yazar
*
* @access protected
* @param string dosya yolu
* @param string dosyaya yaz�lacak veri
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
* zaman �l��mleri yapmak i�in kullan�l�r
*
* @access protected
*/
protected function _timer()
{
return microtime(true);
}


/**
* bu s�n�f� o anda hangi sayfan�n kulland���n� belirler
*
* @access protected
* @return string ge�erli sayfan�n yolu
*/
protected function _phpSelf()
{
$yol = strip_tags($_SERVER['PHP_SELF']);
$yol = substr($yol, 0, 200);
$yol = htmlentities(trim($yol), ENT_QUOTES);

return $yol;
}

}//s�n�f sonu
