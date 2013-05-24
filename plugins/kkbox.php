<?php
/**
 * fetcher for kkbox
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @package plugin
 **/

function kkbox_artwork_hook($param) {
	$query = str_replace(' ', '+', ($param['title'] . ' ' . $param['artist']));
	$url = 'http://tw.kkbox.com/search.php?&search=song&search_lang=&word=' . $query;
	$html = @file_get_contents($url);
	$url = phpQuery::newDocument($html)->find('div.song-list > table tbody tr:first td:nth-child(3) a')->attr('href');
	if (empty($url)) {
		return '';
	}

	$url = 'http://tw.kkbox.com' . $url;
	$html = @file_get_contents($url);
	$url = phpQuery::newDocument($html)->find('img.cover')->attr('src');
	return $url;
}
