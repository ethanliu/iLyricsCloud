<?php
/**
 * 
 *
 * @author Ethan Liu
 * @copyright , 14 February, 2012
 **/

include "common.php";
include "header.php";

$module = trim($_REQUEST['q']);
$action = trim($_REQUEST['action']);

switch ($module) {
	case 'artworks':
		include "modules/artworks.php";
		$m = new ArtworksModule();
		break;

	case 'lyrics':
		include "modules/lyrics.php";
		$m = new LyricsModule();
		break;

	case 'login':
		include "modules/login.php";
		break;
	
	default:
		include "modules/dashboard.php";
		break;
}

if ($module == 'dashboard' || empty($module)) {
	include "modules/artworks.php";
	include "modules/lyrics.php";

	$m = new ArtworksModule();
	$totalArtworks = $m->numberOfRecords();

	$m = new LyricsModule();
	$totalLyrics = $m->numberOfRecords();

	include "views/dashboard-view.php";
}
else if ($module == 'login') {
}
else {
	switch ($action) {
		case 'edit':
			$result = $m->edit($_REQUEST['id']);
			include "views/{$module}-edit.php";
		break;
		case 'update':
			$result = $m->update();
			if (!$result) {
				$result = $m->edit($_REQUEST['id']);
				include "views/{$module}-edit.php";
			}
		//break;
		default:
			$total = $m->numberOfRecords();
			$pages = $m->numberOfPages();

			$page = @intval($_REQUEST['page']);
			$page = $page <= 0 ? 1 : ($page > $pages ? $pages : $page);

			$result = $m->records($page);
			include "views/{$module}-view.php";
			break;
	}
}

include "footer.php";