<?php
//
// +---------------------------------------------------------------------------+
// | eburhan Upload class v1.6                                                 |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | Sınıf adı      : eburhan Upload class                                     |
// | Versiyonu      : 1.6                                                      |
// | Görevi         : sunucuya dosya yüklenmesini sağlar                       |
// | Gereksinimler  : PHP 4.3.0 ve üzeri                                       |
// | Son güncelleme : 13 Şubat 2009, 18:18                                     |
// |                                                                           |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | Programcı      : Erhan BURHAN                                             |
// | E-posta        : eburhan {at} gmail {dot} com                             |
// | Web adresi     : http://www.eburhan.com/                          		   |
// |                                                                           |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | Copyright (C) 2008                                                        |
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

// hataları ekranda göstermeyi kapat
// bu sayede @ kullanmaktan kurtuluyoruz
$oldErr = error_reporting(0);

class UPLOAD{

    var $_dosyalar  = array();
    var $_sayDosya  = 0;
    var $_hataMsg   = array();
    var $_bilgiVer  = array();
    var $_yolDizin  = './upload';
    var $_yazUstune = false;
	var $_imgUzanti = array('png', 'jpg', 'jpeg', 'jpe', 'gif', 'bmp');
    var $_gdEtkin 	= false;


    /**
     * kurucu fonksiyondur. öntanımlı işlemleri yapar
     *
     * @access  public
     * @param	array	yükleme dizininin yolu
    */
    function UPLOAD($_FILES)
    {
		// GD kütüphanesi etkin mi? (geçersiz resim kontrolü için gerekli)
		$this->_gdEtkin = extension_loaded('gd');

        $dosyalar = array();
        $dosyaSay = count($_FILES['name']);

		// yüklenecek dosya sayısına göre "dosyalar" dizisini oluştur
		if( $dosyaSay===1 && !is_array($_FILES['name']) ) {
			$dosyalar['name'][] 	 = $_FILES['name'];
			$dosyalar['type'][]	 	 = $_FILES['type'];
			$dosyalar['tmp_name'][]  = $_FILES['tmp_name'];
			$dosyalar['error'][] 	 = $_FILES['error'];
			$dosyalar['size'][] 	 = $_FILES['size'];
		} else {
			$dosyalar = $_FILES;
		}

        // boş kayıtları aradan çıkart
        for( $i=0; $i<$dosyaSay; ++$i )
        {
            if( !empty($dosyalar['name'][$i]) )
            {
				// geçerli dosyanın uzantısı
                $dosyalar['ext'][$i] = $this->_dosyaUzantisi( $dosyalar['name'][$i] );

                $this->_dosyalar['name'][]      = $dosyalar['name'][$i];
                $this->_dosyalar['ex_name'][]   = $dosyalar['name'][$i];
                $this->_dosyalar['ext'][] 		= $dosyalar['ext'][$i];
                $this->_dosyalar['type'][]      = $dosyalar['type'][$i];
                $this->_dosyalar['tmp_name'][]  = $dosyalar['tmp_name'][$i];
                $this->_dosyalar['error'][]     = $dosyalar['error'][$i];
                $this->_dosyalar['size'][]      = $dosyalar['size'][$i];

                // ön hata kontrolü
                if( $dosyalar['error'][$i] !== 0 ){
                    $this->_hataMsg[] = "<strong>{$dosyalar['name'][$i]}</strong> ".$this->_phpDurumMsj($dosyalar['error'][$i]);
                }

				// MIME kontrolü
                if( $this->_mimeKontrol($dosyalar['ext'][$i], $dosyalar['type'][$i]) === false ){
                    $this->_hataMsg[] = "<strong>{$dosyalar['name'][$i]}</strong> dosyasının MIME tipine izin verilmiyor.";
				}

                /*
					Geçersiz Resim Kontrolü
					 1. önce dosya uzantısına bak
					 2. uzantı $_imgUzanti dizisi içerisinde var mı?
					 3. eğer varsa $_imgKontrol() fonksiyonuna gönder
                */
                if( in_array($dosyalar['ext'][$i], $this->_imgUzanti) ) {
					if( $this->_imgKontrol($dosyalar['type'][$i], $dosyalar['tmp_name'][$i])===false ) {
                    	$this->_hataMsg[] = "<strong>{$dosyalar['name'][$i]}</strong> dosyası geçerli bir resim dosyası değil !";
                    }
                }

                $this->_sayDosya++;
            }
        }
    }



    /**
     * dosyaların kaydedileceği dizinin yolu
     *
     * @access  public
	 * @param	string	yükleme dizininin yolu
    */
    function yolDizin($yol)
    {
        // Dizin var mı?
        if( !is_dir($yol) && !mkdir($yol, 0755) ){
            $this->_hataMsg[] = "<strong>$yol</strong> klasörü bulunamıyor";
        }

        // Dizin yazılabilir mi?
        if( !is_writable($yol) && !chmod($yol, 0755) ){
            $this->_hataMsg[] = "<strong>$this->_yolDizin</strong> klasörü yazılabilir değil";
        }

        $this->_yolDizin = $yol;
    }



    /**
     * yüklenmesi gereken minimum dosya sayısı
     *
     * @access  public
	 * @param	integer	tamsayı
    */
    function minDosya($min)
    {
        if( $this->_sayDosya < $min ) {
            $this->_hataMsg[] = "En az <strong>$min</strong> dosya yüklemeniz gerekiyor.";
        }
    }


    /**
     * aynı anda yüklenebilecek maksimum dosya sayısı
     *
     * @access  public
	 * @param	integer	tamsayı
    */
    function maxDosya($max)
    {
        if( $this->_sayDosya > $max ) {
            $this->_hataMsg[] = "Aynı anda en fazla <strong>$max</strong> dosya yükleyebilirsiniz.";
        }
    }


    /**
     * yüklenecek dosyanın minimum dosya boyutu
     *
     * @access  public
	 * @param	integer	KB cinsinden tamsayı
    */
    function minBoyut($min)
    {
        for( $i=0; $i<$this->_sayDosya; ++$i )
        {
            if( $this->_dosyalar['size'][$i] < $this->_kb2bayt($min) ){
                $this->_hataMsg[] = "<strong>{$this->_dosyalar['ex_name'][$i]}</strong> dosyasının boyutu <strong>$min KB</strong> altında olamaz.";
            }
        }
    }


    /**
     * yüklenecek dosyanın maksimum dosya boyutu
     *
     * @access  public
	 * @param	integer	KB cinsinden tamsayı
    */
    function maxBoyut($max)
    {
        for( $i=0; $i<$this->_sayDosya; ++$i )
        {
            if( $this->_dosyalar['size'][$i] > $this->_kb2bayt($max) ){
                $this->_hataMsg[] = "<strong>{$this->_dosyalar['ex_name'][$i]}</strong> dosyasının boyutu <strong>$max KB</strong> üstünde olamaz.";
            }
        }
    }


    /**
     * yüklenmesine izin verilmeyecek dosya türleri
     *
     * @access  public
	 * @param	string	'php, exe, bat' şeklindeki uzantılar
	 * @param	string	uzantıları ayıran özel bir karakter
    */
    function tipYasak($uzantilar, $ayirici=',')
    {
		// Uzantıları parselle ve ARRAY olarak geri döndür
		$uzantilar = $this->_dosyaUzantisiParselle($ayirici, $uzantilar);

		if( !is_array($uzantilar) ) {
			$this->_hataMsg[] = "<strong>tipYasak</strong> ayarı yanlış belirlenmiş.";
		}

		// uzantı kontrolü
		for( $i=0; $i<$this->_sayDosya; ++$i)
		{
			if( in_array($this->_dosyalar['ext'][$i], $uzantilar) ){
				$this->_hataMsg[] = "<strong>{$this->_dosyalar['ex_name'][$i]}</strong> dosyası izin verilmeyen bir türde.";
			}
		}
    }


    /**
     * yüklenmesine izin verilecek dosya türleri
     *
     * @access  public
	 * @param	string	'php, exe, bat' şeklindeki uzantılar
	 * @param	string	uzantıları ayıran özel bir karakter
    */
    function tipKabul($uzantilar, $ayirici=',')
    {
		// Uzantıları parselle ve ARRAY olarak geri döndür
		$uzantilar = $this->_dosyaUzantisiParselle($ayirici, $uzantilar);

		if( !is_array($uzantilar) ) {
			$this->_hataMsg[] = "<strong>tipKabul</strong> ayarı yanlış belirlenmiş.";
		}

		// uzantı kontrolü
		for( $i=0; $i<$this->_sayDosya; ++$i)
		{
			if( !in_array($this->_dosyalar['ext'][$i], $uzantilar) ){
				$this->_hataMsg[] = "<strong>{$this->_dosyalar['ex_name'][$i]}</strong> dosyası izin verilmeyen bir türde.";
			}
		}
    }


    /**
     * dosyayı yeniden isimlendir
     *
     * @access  public
	 * @param	string	"yeni_isim" şeklinde gelmeli
    */
    function yeniAd($ad)
    {
        if( is_bool($ad) && $ad === true )
        {
            for( $i=0; $i<$this->_sayDosya; ++$i )
            {
                $this->_dosyalar['name'][$i] = md5(uniqid(mt_rand(), true)).'.'.$this->_dosyalar['ext'][$i];
            }
        }
        elseif( is_bool($ad) && $ad === false )
        {
            for( $i=0; $i<$this->_sayDosya; ++$i )
            {
                // dosya ismindeki istenmeyen karakterleri temizle
                $this->_dosyalar['name'][$i] = $this->_dosyaIsmiTemizle( $this->_dosyalar['name'][$i] );
            }
        }
        else
        {
            for( $i=0; $i<$this->_sayDosya; ++$i )
            {
                $this->_dosyalar['name'][$i] = $ad.'.'.$this->_dosyalar['ext'][$i];
            }
        }
    }


    /**
     * dosya isminin Başına bir ifade eklemek için
     *
     * @access  public
	 * @param	string	'ek' şeklinde gelmeli
    */
    function basaEk($ek)
    {
        if( is_bool($ek) && $ek === true )
        {
            for( $i=0; $i<$this->_sayDosya; ++$i )
            {
                $oldName  = $this->_dosyalar['name'][$i];
                $newName  = ($i+1).'_'.$oldName;

                $this->_dosyalar['name'][$i] = $newName;
            }
        }

        if( is_string($ek) )
        {
            for( $i=0; $i<$this->_sayDosya; ++$i )
            {
                $oldName  = $this->_dosyalar['name'][$i];
                $newName  = $ek.'_'.$oldName;

                $this->_dosyalar['name'][$i] = $newName;
            }
        }
    }


    /**
     * dosya isminin Sonuna bir ifade eklemek için
     *
     * @access  public
	 * @param	string	'ek' şeklinde gelmeli
    */
    function sonaEk($ek)
    {
        if( is_bool($ek) && $ek === true )
        {
            for( $i=0; $i<$this->_sayDosya; ++$i )
            {
                $oldName    = $this->_dosyalar['name'][$i];
                $extension  = '.'.$this->_dosyalar['ext'][$i];

                $noExtension = explode($extension, $oldName);
                $newName     = $noExtension[0].'_'.($i+1).$extension;

                $this->_dosyalar['name'][$i] = $newName;
            }
        }

        if( is_string($ek) )
        {
            for( $i=0; $i<$this->_sayDosya; ++$i )
            {
                $oldName    = $this->_dosyalar['name'][$i];
                $extension  = '.'.$this->_dosyalar['ext'][$i];

                $noExtension = explode($extension, $oldName);
                $newName     = $noExtension[0].'_'.$ek.$extension;

                $this->_dosyalar['name'][$i] = $newName;
            }
        }
    }


    /**
     * upload klasöründe aynı isimde dosyalar varsa,
     * üzerlerine yazılıp yazılmayacağını belirler.
     *
     * @access  public
	 * @param	boolean
    */
    function yazUstune($durum=true)
    {
		if( is_bool($durum) && $durum === false ) {
			$this->_yazUstune = false;
		} else {
			$this->_yazUstune = true;
		}
	}


    /**
     * Yükleme işlemini başlatır. Temp klasöründe bulunan dosyaları
     * asıl yükleme klasörüne taşımakla görevlidir.
     *
     * @access  public
	 * @return	boolean
    */
    function baslat()
    {
		// bu noktaya gelinceye kadar bir hata oluştuysa yüklemeyi durdur
        if( !empty($this->_hataMsg) ) {
            return false;
        }

        /* hata yoksa dosyaları yeni yerlerine taşımayı dene */
        for( $i=0; $i<$this->_sayDosya; ++$i)
        {
            $isim	= $this->_dosyaIsmiTemizle( $this->_dosyalar['name'][$i] );
            $adres	= $this->_yolDizin.'/'.$isim;

            // TEMEL dosya bilgilerini kaydet
            $this->_bilgiVer[$i]['yeniAd']	= $isim;
			$this->_bilgiVer[$i]['eskiAd'] 	= $this->_dosyalar['ex_name'][$i];
			$this->_bilgiVer[$i]['icerik']	= $this->_dosyalar['type'][$i];
			$this->_bilgiVer[$i]['boyut']	= $this->_dosyalar['size'][$i];
			$this->_bilgiVer[$i]['adres']	= $adres;

            // yükleme işlemini başlat
            if( $this->_yazUstune === false && file_exists($adres) ) {
				$this->_bilgiVer[$i]['durum'] = "ERROR";
				$this->_bilgiVer[$i]['mesaj'] = "Dosya yüklenmedi! Çünkü aynı isimde bir dosya zaten var.";
         	}
            elseif( move_uploaded_file($this->_dosyalar['tmp_name'][$i], $adres) ) {
				$this->_bilgiVer[$i]['durum'] = "OK";
				$this->_bilgiVer[$i]['mesaj'] = "Dosya yüklendi.";
            } else {
				$this->_bilgiVer[$i]['durum'] = "HATA";
                $this->_bilgiVer[$i]['mesaj'] = 'Muhtemelen dosya taşıma hatası meydana geldi !';
            }
        }

        return true;
    }


    /**
     * bir resim(!) dosyasının, gerçekten resim olup olmadığı
     * konusunda basit bir kontrol gerçekleştirir.
     *
     * @access  private
     * @param	string	dosyanın mime bilgisi
     * @param	string	dosyanın temp klasöründeki yolu
	 * @return	boolean
    */
    function _imgKontrol($mime, $dosya)
    {
		if( $this->_gdEtkin && !imagecreatefromstring(file_get_contents($dosya)) ) {
            return false;
		}

        // GD etkin değilse getimagesize ile kontrol et
        if( ($mime == 'image/pjpeg' || $mime == 'image/jpeg') && !getimagesize($dosya) ) return false;
        if( ($mime == 'image/png' || $mime == 'image/x-png') && !getimagesize($dosya) ) return false;
        if( $mime == 'image/gif' && !getimagesize($dosya) ) return false;

  		return true;
    }


    /**
     * yüklenmek istenen dosyanın içerik türünün (mime type),
     * geçerli olup olmadığını kontrol eder.
     *
     * @access  private
     * @param	string	dosya uzantısı
     * @param	string	kontrol edilecek mime bilgisi
	 * @return	boolean
    */
	function _mimeKontrol($uzanti, $mime)
	{
		// Mime Type bilgileri CodeIgniter çatısından alınmıştır.
		$uzantilar  = array(
            'hqx'	=>	'application/mac-binhex40',
			'cpt'	=>	'application/mac-compactpro',
			'csv'	=>	array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
			'bin'	=>	'application/macbinary',
			'dms'	=>	'application/octet-stream',
			'lha'	=>	'application/octet-stream',
			'lzh'	=>	'application/octet-stream',
			'exe'	=>	'application/octet-stream',
			'class'	=>	'application/octet-stream',
			'psd'	=>	'application/x-photoshop',
			'so'	=>	'application/octet-stream',
			'sea'	=>	'application/octet-stream',
			'dll'	=>	'application/octet-stream',
			'oda'	=>	'application/oda',
			'pdf'	=>	array('application/pdf', 'application/x-download'),
			'ai'	=>	'application/postscript',
			'eps'	=>	'application/postscript',
			'ps'	=>	'application/postscript',
			'smi'	=>	'application/smil',
			'smil'	=>	'application/smil',
			'mif'	=>	'application/vnd.mif',
			'xls'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
			'ppt'	=>	array('application/powerpoint', 'application/vnd.ms-powerpoint'),
			'wbxml'	=>	'application/wbxml',
			'wmlc'	=>	'application/wmlc',
			'dcr'	=>	'application/x-director',
			'dir'	=>	'application/x-director',
			'dxr'	=>	'application/x-director',
			'dvi'	=>	'application/x-dvi',
			'gtar'	=>	'application/x-gtar',
			'gz'	=>	'application/x-gzip',
			'php'	=>	'application/x-httpd-php',
			'php4'	=>	'application/x-httpd-php',
			'php3'	=>	'application/x-httpd-php',
			'phtml'	=>	'application/x-httpd-php',
			'phps'	=>	'application/x-httpd-php-source',
			'js'	=>	'application/x-javascript',
			'swf'	=>	'application/x-shockwave-flash',
			'sit'	=>	'application/x-stuffit',
			'tar'	=>	'application/x-tar',
			'tgz'	=>	'application/x-tar',
			'xhtml'	=>	'application/xhtml+xml',
			'xht'	=>	'application/xhtml+xml',
			'zip'	=>  array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
			'mid'	=>	'audio/midi',
			'midi'	=>	'audio/midi',
			'mpga'	=>	'audio/mpeg',
			'mp2'	=>	'audio/mpeg',
			'mp3'	=>	array('audio/mpeg', 'audio/mpg'),
			'aif'	=>	'audio/x-aiff',
			'aiff'	=>	'audio/x-aiff',
			'aifc'	=>	'audio/x-aiff',
			'ram'	=>	'audio/x-pn-realaudio',
			'rm'	=>	'audio/x-pn-realaudio',
			'rpm'	=>	'audio/x-pn-realaudio-plugin',
			'ra'	=>	'audio/x-realaudio',
			'rv'	=>	'video/vnd.rn-realvideo',
			'wav'	=>	'audio/x-wav',
			'bmp'	=>	'image/bmp',
			'gif'	=>	'image/gif',
			'jpeg'	=>	array('image/jpeg', 'image/pjpeg'),
			'jpg'	=>	array('image/jpeg', 'image/pjpeg'),
			'jpe'	=>	array('image/jpeg', 'image/pjpeg'),
			'png'	=>	array('image/png',  'image/x-png'),
			'tiff'	=>	'image/tiff',
			'tif'	=>	'image/tiff',
			'css'	=>	'text/css',
			'html'	=>	'text/html',
			'htm'	=>	'text/html',
			'shtml'	=>	'text/html',
			'txt'	=>	'text/plain',
			'text'	=>	'text/plain',
			'log'	=>	array('text/plain', 'text/x-log'),
			'rtx'	=>	'text/richtext',
			'rtf'	=>	'text/rtf',
			'xml'	=>	'text/xml',
			'xsl'	=>	'text/xml',
			'mpeg'	=>	'video/mpeg',
			'mpg'	=>	'video/mpeg',
			'mpe'	=>	'video/mpeg',
			'qt'	=>	'video/quicktime',
			'mov'	=>	'video/quicktime',
			'avi'	=>	'video/x-msvideo',
			'movie'	=>	'video/x-sgi-movie',
			'doc'	=>	'application/msword',
			'docx'	=>	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'xlsx'	=>	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'word'	=>	array('application/msword', 'application/octet-stream'),
			'xl'	=>	'application/excel',
			'eml'	=>	'message/rfc822'
		);

		if( !isset($uzantilar[$uzanti]) ) return false;

		if( is_array($uzantilar[$uzanti]) ) {
			return in_array($mime, $uzantilar[$uzanti]);
		}
		// is_string
		return ($mime===$uzantilar[$uzanti]);
	}


    /**
     * yüklenmek istenen dosyanın isminde istenmeyen karakterler varsa temizler
     *
     * @access  private
     * @param	string	'türkçe.gif' şeklinde olmalı
	 * @return	string
    */
    function _dosyaIsmiTemizle($oldName)
    {
    	// isim ile uzantıyı ayır
		$oldName	= trim($oldName);
		$extension 	= strrchr($oldName, '.');
		$extRegex	= "/(\\$extension)\$/";
        $onlyName	= preg_split($extRegex, $oldName, -1, PREG_SPLIT_NO_EMPTY);

		// türkçe karakterleri değiştir
		$degistir  = array(
			'ı'=>'i', 'ğ'=>'g', 'ü'=>'u', 'ş'=>'s', 'ö'=>'o', 'ç'=>'c',
			'İ'=>'i', 'Ğ'=>'g', 'Ü'=>'U', 'Ş'=>'s', 'Ö'=>'o', 'Ç'=>'c'
		);
		$onlyName[0] = strtr($onlyName[0], $degistir);
		$onlyName[0] = preg_replace('/\W/', '_', $onlyName[0]);

		return ($onlyName[0].$extension);
    }


    /**
     * dosya uzantısını parseller
     *
     * @access  private
     * @param	string	uzantılar arasındaki ayırıcı sembol
     * @param	string	'exe, gif' şeklinde gelen uzantılar
	 * @return	array
    */
    function _dosyaUzantisiParselle($ayirici, $uzantilar)
    {
    	$sonuc = explode($ayirici, $uzantilar);
        $sonuc = array_map('trim', $sonuc);
        return $sonuc;
	}


    /**
     * dosya uzantısını bulur
     *
     * @access  private
     * @param	string	'dosya.exe' şeklinde gelir 'exe' olarak çıkar
	 * @return	string
    */
    function _dosyaUzantisi($dosya)
    {
        $ext = strtolower(strrchr($dosya, '.'));
        $ext = substr($ext, 1);
        return $ext;
	}


    /**
     * bu iki fonksiyon BAYT ve KILOBAYT arası dönüşümü sağlar
     *
     * @access  private
     * @param	integer	'1234' şeklinde tamsayı olmalı
	 * @return	float
    */
    function _bayt2kb($bayt) { return round(($bayt/1024), 2); }
    function _kb2bayt($bayt) { return round(($bayt*1024), 2); }


    /**
     * başarıyla yüklenen dosyalara ait en son bilgileri verir
     *
     * @access  public
	 * @return	array
    */
    function bilgiVer()
    {
        return $this->_bilgiVer;
    }


    /**
     * oluşan en son hatayı geri döndür
     *
     * @access  public
	 * @return	string
    */
    function sonHata()
    {
        return end($this->_hataMsg);
    }


    /**
     * oluşan ilk hatayı geri döndür
     *
     * @access  public
	 * @return	string
    */
    function ilkHata()
    {
        return $this->_hataMsg[0];
    }


    /**
     * bütün hataları bir dizi halinde geri döndür
     *
     * @access  public
	 * @return	array
    */
    function tumHata()
    {
        return $this->_hataMsg;
    }


    /**
     * Upload işlemi sonrasında PHP'nin döndürdüğü durum mesajları
     *
     * @access  private
     * @param	integer	hata numarası
	 * @return	string
    */
	function _phpDurumMsj($no)
	{
		$durum = array();
		$durum[0] = 'dosyası başarıyla yüklendi';
		$durum[1] = 'dosyası, php.ini içerisindeki upload_max_filesize direktifini aşıyor';
		$durum[2] = 'dosyası, HTML formundaki MAX_FILE_SIZE direktifini aşıyor';
		$durum[3] = 'dosyasının yalnızca bir kısmı yüklendi';
		$durum[4] = 'dosyası yüklenemedi';
		$durum[5] = 'Geçiçi klasör eksik';

		return $durum[$no];
	}

}//sınıf sonu

// hata raporlamayı eski haline döndür
error_reporting($oldErr);
?>