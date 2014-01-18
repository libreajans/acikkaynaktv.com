<?php
		if (!defined('yakusha')) die('...');
		$sayfabilgisi.='
		<tr>
			<td class="mynotes" colspan="3" align="left">
				<h2><a href="'.HABERLERLINK.'?cat='.$cat.'">'.$news_status_name.'</a></h2>
				<p>'.$news_status_desc.'</p>
			</td>
		</tr>
		';
			
		$vt->sql('
		SELECT 
			tv_news.news_id,
			tv_news.news_fileid,
			tv_news.news_link,
			tv_news.news_title,
			tv_news.news_desc,
			tv_news.news_status,
			tv_files.file_posticon
		FROM tv_news,tv_files
		WHERE
			tv_news.news_fileid = tv_files.file_id
			AND tv_news.news_id > 0
			AND tv_news.news_status = %u
		ORDER BY news_id DESC limit 0,50
		')->arg($cat)->sor();
		$sonuc = $vt->alHepsi();
		$adet = $vt->numRows();

		if ($adet)
		{
			for ( $i = 0; $i < $adet; $i++)
			{
				//sorgudan alınıyor
				$news_id 		= $sonuc[$i]->news_id;
				$news_fileid 	= $sonuc[$i]->news_fileid;
				$news_link 		= $sonuc[$i]->news_link;
				$news_title 	= $sonuc[$i]->news_title;
				$news_desc 		= $sonuc[$i]->news_desc;
				$news_status 	= $sonuc[$i]->news_status;
				$file_posticon 	= $sonuc[$i]->file_posticon;
				if ($news_fileid > 0 && $file_posticon <> '') 
				{
					$news_icon = '<img width="40" src="'.SITELINK.'/posticons/'.$file_posticon.'">';
				}
				else
				{
					$news_icon = '<img width="40" src="'.SITELINK.'/_img/icon_news.png">';
				}

				//gerekli olan biçimlendirme
				$news_title = stripslashes($news_title);
				$news_desc = stripslashes($news_desc);
		
				$news_ic_link = SITELINK.'/' . HABERLERLINK . '?news_id=' . $news_id .'-'. pco_format_url($file_name) ;			
				if (SEO_OPEN == 1) $news_ic_link = SITELINK.'/' . pco_format_url($news_title) . '-n' . $news_id . SEO;			
				
				$sayfabilgisi.= '
				<tr>
					<td class="mynotes" valign="top" width="45">
						'.$news_icon.' 
					</td>
					<td class="mynotes" >
						<h2>
						'.$news_title.'
						</h2>
						<p>'.$news_desc.'</p>
					</td>
					<td class="mynotes" valign="center" width="130">
						<p>
						<a target="_blank" title="Haberi Kaynağında Görüntüle" href="'.$news_link.'">Haberi Görüntüle &raquo;</a>
						<p>
					</td>
				</tr>
				';
			}
		}
?>