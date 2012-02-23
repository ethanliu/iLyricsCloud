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
//@mb_internal_encoding("UTF-8");

define('GA_ACCOUNT', 'MO-402625-5');
define('GA_PIXEL', 'https://creativecrap.herokuapp.com/qa.php');

define('ADMIN_USER', 'admin');
define('ADMIN_PASS', '79WD.s>ri,76r;*3fx');

define('INSTALLED', true);
define('DATABASE_DNS', 'sqlite:' . dirname(__FILE__) . '/cache/cache.db');
//define('DATABASE_DNS', 'pgsql:host=ec2-107-22-193-180.compute-1.amazonaws.com;sslmode=require;port=5432;dbname=rzvwmhytvz;user=rzvwmhytvz;password=;5F4U8FIseOBwN7CZRthj');

require_once dirname(__FILE__) . '/phpQuery.php';
