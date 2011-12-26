<?php
/**
 * Statics
 *
 * @author Ethan Liu
 * @version $Id$
 * @copyright , 27 December, 2011
 * @package class
 **/

require_once dirname(__FILE__) . "/class.controller.php";

class Stats extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function status() {
		printf("%d lyrics<br>", $this->numberOfLyrics());
		printf("%d artworks<br>", $this->numberOfArtworks());
	}


	private function numberOfLyrics() {
		$sql = "SELECT COUNT(*) AS total FROM lyrics";
		$stmt = $this->db_prepare($sql);
		$total = $this->db_getOne($stmt);
		return intval($total);
	}

	private function numberOfArtworks() {
		$sql = "SELECT COUNT(*) AS total FROM artworks";
		$stmt = $this->db_prepare($sql);
		$total = $this->db_getOne($stmt);
		return intval($total);
	}

}

$stats = new Stats();
$stats->status();