<?php
function t($txt) {
	return $txt;
}

function _time($timestamp, $format = 'timeago') {
	switch ($format) {
		case 'timeago':
		case 'timeago_long':
			// Takes an timestamp and returns a string representing how long ago the date represents.
			$now = time();
			$diff = $now - $timestamp;
			$day_diff = floor($diff / 86400);
			
			// only care about dates in the past (by far the most common use case)
			// and only dates within the past month (anything beyond a month becomes fuzzy and impractical).
			if ($day_diff < 0 || $day_diff >= 31 ) {
				switch ($format) {
					case 'timeago_long':
						return date('r', $timestamp);
					break;
					default:
						return date("M j, Y", $timestamp);
					break;
				}
			}
			
			if ($day_diff == 0) {
				switch ($diff) {
					case $diff < 60;
						return t("just now");
					break;
					case $diff < 120;
						return t("1 minute ago");
					break;
					case $diff < 3600;
						return sprintf(t("%d minutes ago"), floor($diff / 60));
					break;
					case $diff < 7200;
						return t("1 hour ago");
					break;
					case $diff < 86400;
						return sprintf(t("%d hours ago"), floor($diff / 3600));
					break;
					default:
						return t("just now");
					break;
				}
			}
			else if ($day_diff == 1) {
				return t("Yesterday");
			}
			else if ($day_diff < 7) {
				return sprintf(t("%d days ago"), $day_diff);
			}
			else if ($day_diff < 31) {
				return sprintf(t("%d weeks ago"), ceil($day_diff / 7));
			}
		break;
		
		case 'full':
			return date("Y-m-d H:i:s", $timestamp);
		break;
		
		case 'long': // rfc
			return date('r', $timestamp);
		break;
		
		case 'iso': // iso 8601
		default:
			return date('c', $timestamp);
		break;
	}
}
