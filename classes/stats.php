<?php
/**
 * Statics
 *
 * @author Ethan Liu
 * @version $Id$
 * @copyright , 27 December, 2011
 * @package class
 **/

require_once dirname(__FILE__) . "/controller.php";

class Stats extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function status() {
		printf("%d lyrics<br>", $this->numberOfLyrics());
		printf("%d artworks<br>", $this->numberOfArtworks());
	}

	public function firstLyrics() {
		$sql = "SELECT * FROM lyrics ORDER BY created DESC LIMIT 1";
		$stmt = $this->db_prepare($sql);
		$result = $this->db_getRow($stmt);
		return $result;
	}

	public function lastLyrics() {
		$sql = "SELECT * FROM lyrics ORDER BY created ASC LIMIT 1";
		$stmt = $this->db_prepare($sql);
		$result = $this->db_getRow($stmt);
		return $result;
	}

	public function firstArtwork() {
		$sql = "SELECT * FROM artworks ORDER BY created DESC LIMIT 1";
		$stmt = $this->db_prepare($sql);
		$result = $this->db_getRow($stmt);
		return $result;
	}

	public function lastArtwork() {
		$sql = "SELECT * FROM artworks ORDER BY created ASC LIMIT 1";
		$stmt = $this->db_prepare($sql);
		$result = $this->db_getRow($stmt);
		return $result;
	}

	public function numberOfLyrics() {
		$sql = "SELECT COUNT(*) AS total FROM lyrics";
		$stmt = $this->db_prepare($sql);
		$total = $this->db_getOne($stmt);
		return intval($total);
	}

	public function numberOfArtworks() {
		$sql = "SELECT COUNT(*) AS total FROM artworks";
		$stmt = $this->db_prepare($sql);
		$total = $this->db_getOne($stmt);
		return intval($total);
	}

}
