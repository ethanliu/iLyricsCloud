<?php
/**
 * Install module
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 **/

require_once dirname(__FILE__) . "/../common.php";
require_once dirname(__FILE__) . "/../../classes/controller.php";

class UpgradeModule extends Controller {
	public function __construct() {
		parent::__construct();
		$this->db_connect();
	}

	public function upgrade($version) {

		$sql = "UPDATE lyrics SET lang = 'metrolyrics' WHERE lang = 'en'";
		$stmt = $this->db_prepare($sql);
		$result = $this->db_execute($stmt);

		$sql = "UPDATE lyrics SET lang = 'mojim' WHERE lang = 'zh'";
		$stmt = $this->db_prepare($sql);
		$result = $this->db_execute($stmt);

		$sql = "UPDATE lyrics SET lang = 'utamap' WHERE lang = 'jp'";
		$stmt = $this->db_prepare($sql);
		$result = $this->db_execute($stmt);

		echo "Upgrade {$version} successed.";
		exit;
	}

}
