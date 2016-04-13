<?php
/**
 * Main entry
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 **/

include "./common.php";

$module = trim($_REQUEST['q']);
$search = trim($_REQUEST['search']);
$action = trim($_REQUEST['action']);

if ($action !== 'set') {
	include "header.php";
}

switch ($module) {
	case 'artworks':
		include "modules/artworks.php";
		$m = new ArtworksModule();
		break;

	case 'lyrics':
		include "modules/lyrics.php";
		$m = new LyricsModule();
		break;

	case 'plugins':
		include "modules/plugins.php";
		$m = new PluginsModule();
		break;

	case 'news':
		include "modules/news.php";
		$m = new NewsModule();
		break;

	case 'login':
		include "modules/login.php";
		break;

	case 'install':
		include "modules/install.php";
		$m = new InstallModule();
		$m->install();
		break;

	default:
		$module = "dashboard";
		include "modules/dashboard.php";
		$m = new DashboardModule();
}

switch ($action) {
	case 'edit':
		$result = $m->edit($_REQUEST['id']);
		include "views/{$module}-edit.php";
	break;
	case 'set':
		$result = $m->set();
		die($result);
	break;
	case 'update':
		$result = $m->update();
		if (!$result) {
			$result = $m->edit($_REQUEST['id']);
			include "views/{$module}-edit.php";
		}
	//break;
	default:
		if ($module !== 'dashboard') {
			if (method_exists($m, 'numberOfRecords')) {
				$total = $m->numberOfRecords();
			}
			if (method_exists($m, 'numberOfPages')) {
				$pages = $m->numberOfPages();
			}

			$page = @intval($_REQUEST['page']);
			$page = $page <= 0 ? 1 : ($page > $pages ? $pages : $page);

			$result = $m->records($page);
			include "views/{$module}-view.php";
		}
		break;
}

include "footer.php";