<?php
/**
 * fetcher for http://music.naver.com/
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @package plugin
 **/

function naver_lyrics_hook($param) {
	$url = '';
	if (!empty($param['album'])) {
		$url = "http://music.naver.com/search/search.nhn?target=album&query=" . urlencode($param['album'] . ' ' . $param['artist']);
		$html = file_get_contents($url);
		$firstAlbumLink = phpQuery::newDocument($html)->find('.lst_default5 dt a:first')->attr('href');
		if ($firstAlbumLink) {
			$url = 'http://music.naver.com' . $firstAlbumLink;
		}
	}
	else {
		$url = "http://music.naver.com/search/search.nhn?target=track&query=" . urlencode($param['title'] . ' ' . $param['artist']);
	}
	

	$html = file_get_contents($url);
	$doc = phpQuery::newDocument($html)->find('tr._tracklist_move');//->find('a._lyric[title*=' . $param['title'] . ']');
	foreach (pq('tr') as $item) {
		$row = pq($item);
		$track = $row->find('a._title');
		$title = $track->text();
		$id = $track->attr('href');

		// begin with
		if (preg_match("/^" . $param['title'] . "/i", $title)) {
			$class = $row->find('a._lyric')->attr('class');
			if ($class !== "_lyrics none") {
				// has lyrics
				$url = 'http://music.naver.com/lyric/index.nhn?trackId=' . str_replace('#', '', $id);
				$html = file_get_contents($url);
				$doc = phpQuery::newDocument($html)->find('#lyricText');
				$lyrics = $doc->html();
				$lyrics = str_replace('<br>', "\n", $lyrics);
				return $lyrics;
			}
		}
	}

	// http://music.naver.com/search/search.nhn?query=no+make+up+-+zion.t&target=track
	// http://music.naver.com/search/search.nhn?query=no+make+up+-+zion.t&target=album
	// http://music.naver.com/album/index.nhn?albumId=589055
	// td.lyrics <a class="_lyric NPI=a:lyric,r:1,i:5756111">
	/// http://music.naver.com/lyric/index.nhn?trackId=5756111
	// #lyricText
	return '';
}