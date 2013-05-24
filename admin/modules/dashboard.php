<?php
/**
 * Dashboard module
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 **/

require_once dirname(__FILE__) . "/../common.php";
require_once dirname(__FILE__) . "/../../classes/controller.php";

class DashboardModule extends Controller {
	public function __construct() {
		parent::__construct();

		if (!empty($this->database) && !file_exists(dirname(__FILE__) . "/../../cache/INSTALLED")) {
			die('<a href="?q=install">Install</a> database.');
		}

		$this->dashboard();
	}


	private function dashboard() {
		include dirname(__FILE__) . "/artworks.php";
		include dirname(__FILE__) . "/lyrics.php";

		$m = new ArtworksModule();
		$totalArtworks = $m->numberOfRecords();

		$m = new LyricsModule();
		$totalLyrics = $m->numberOfRecords();

		include "views/dashboard-view.php";

	}
}