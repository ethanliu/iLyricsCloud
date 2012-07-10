<?php
/**
 * Glboal configuration
 *
 * @author Ethan Liu
 * @copyright , 27 December, 2011
 **/

//error_reporting(E_ALL&~E_NOTICE&~E_WARNING);

//require_once('FirePHPCore/FirePHP.class.php');
//$firephp = FirePHP::getInstance(true);

error_reporting(E_ALL);
ini_set("display_errors", 1);

ini_set('magic_quotes_runtime', 0);
ini_set('magic_quotes_gpc', 0);
ini_set('magic_quotes_sybase', 0);
ini_set('url_rewriter.tags', '');
ini_set('auto_detect_line_endings','1');

set_time_limit(180);
@mb_internal_encoding("UTF-8");

define('GA_ACCOUNT', 'MO-30101378-1');
define('GA_PIXEL', 'http://app-creativecrap.rhcloud.com/qa.php');

define('ADMIN_USER', 'admin');
define('ADMIN_PASS', '79WD.s>ri,76r;*3fx');

define('INSTALLED', true);

define('DATABASE_DNS', 'mysql:host=127.0.0.1;port=3306;dbname=cloudapp;user=root;password=root;');
//define('DATABASE_DNS', 'mysql:host=127.4.53.129;port=3306;dbname=app;user=admin;password=r5uHX7TuNVhn');

require_once dirname(__FILE__) . '/phpQuery.php';
