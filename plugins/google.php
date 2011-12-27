<?php
/**
 * Fetcher for goole.com
 *
 * @author Ethan Liu
 * @version $Id$
 * @copyright , 26 December, 2011
 * @package plugin
 **/

function google_artwork_hook($param) {
	$image_size = "large"; //can be 'icon' 'small' 'medium' 'large'
	$limit_to_domain = ""; //"amazon.com"; // 'amazon.com' or 'artistdirect.com'
	$query = urlencode($param['album']) . '%20' . urlencode($param['artist']);
	$url = "http://images.google.com/images?ie=utf-8&hl=en&btnG=Google+Search";
	$url .= "&imgsz={$image_size}&as_sitesearch={$limit_to_domain}&q=" . $query;

	$html = file_get_contents($url);
	if (empty($html)) {
		return '';
	}
	
	$url = phpQuery::newDocument($html)->find('table.images_table a:first')->attr('href');
	parse_str(parse_url($url, PHP_URL_QUERY), $args);
	if (empty($args['imgurl'])) {
		return '';
	}
	return $args['imgurl'];
}