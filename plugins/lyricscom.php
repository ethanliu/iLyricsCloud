<?php
/**
 * fetcher for lyrics.com
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @package plugin
 **/

function lyricscom_lyrics_hook($param) {
	$patterns = array('/ {1,}/', '/[^\w-]/', '/-{1,}/');
	$replacements = array('-', '', '-');
	$artist = preg_replace($patterns, $replacements, strtolower($param['artist']));

	$patterns = array('/ /', '/[^\w-]/', '/-{2,}/');
	$replacements = array('-', '', '-');
	$title = preg_replace($patterns, $replacements, strtolower($param['title']));

	if (strlen($title . $artist) <= 2) {
		return '';
	}

	$url = sprintf("http://www.lyrics.com/%s-lyrics-%s.html", $title, $artist);
	//echo $url . '<hr>';exit;
	$html = file_get_contents($url);
	if (empty($html)) {
		return '';
	}

	$doc = phpQuery::newDocumentHTML($html)->find('div#lyric_space');
	$html = strip_tags($doc->html(), '<br>');

	if (strpos($html, "Your name will be printed as part of the credit when your lyric is approved") !== false) {
		return '';
	}

	return $html;
}
