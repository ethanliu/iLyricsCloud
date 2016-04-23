<?php
/**
 * Configuration
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 **/

require_once dirname(__FILE__) . '/libs/phpQuery.php';

//error_reporting(E_ALL&~E_NOTICE&~E_WARNING);
// error_reporting(E_ALL);
ini_set("display_errors", 0);

ini_set('magic_quotes_runtime', 0);
ini_set('magic_quotes_gpc', 0);
ini_set('magic_quotes_sybase', 0);
ini_set('url_rewriter.tags', '');
ini_set('auto_detect_line_endings','1');

set_time_limit(180);
@mb_internal_encoding("UTF-8");

define('GA_ACCOUNT', 'UA-402625-5');

define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'admin');

define('DATABASE_DSN', 'sqlite:' . dirname(__FILE__) . '/cache/cache.db');
// define('DATABASE_DSN', 'mysql:host=127.0.0.1;port=3306;dbname=;user=;password=;');

$plugins = array();
$plugins['en'] = array(
	'metrolyrics',
	'lyricscom',
	'lyricswiki',
);
$plugins['zh'] = array(
	'mojim',
	'kkbox',
);
$plugins['jp'] = array(
	// 'yahoojp',
	'utamap',
	'jpopasia',
);
$plugins['artwork'] = array(
	// 'google',
	'itunes',
	'kkbox',
);
