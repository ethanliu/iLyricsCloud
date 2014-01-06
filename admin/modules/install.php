<?php
/**
 * Install module
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 **/

require_once dirname(__FILE__) . "/../common.php";
require_once dirname(__FILE__) . "/../../classes/controller.php";

class InstallModule extends Controller {
	public function __construct() {
		parent::__construct();
	}

	public function install() {
		if (empty($this->database)) {
			die("Unable to connect database");
		}

		$dsn = explode(':', $this->database);
		switch ($dsn[0]) {
			case 'sqlite':
				$this->install_sqlite();
				echo "Install sqlite.<br>";
				break;
			case 'pgsql':
				$this->install_postgres();
				echo "Install Postgres.<br>";
				break;
			case 'mysql':
				$this->install_mysql();
				echo "Install Mysql.<br>";
				break;
		}
		// echo "Done, please update INSTALLED from config.php.";
		$path = dirname(__FILE__) . "/../../cache/INSTALLED";
		if (file_put_contents($path, "INSTALLED") === false) {
			echo "Make sure 'cache' directory is writable.";
			exit;
		}
		//$this->db_connect();
		echo "Install success.";
		exit;
	}

	private function install_sqlite() {
		if (file_exists($this->database)) {
			return;
		}
		$this->db = new PDO($this->database);
		if (!$this->db) {
			die("Unable to connect database");
		}
		else {
			$sql = 'CREATE TABLE lyrics (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				created INTEGER NOT NULL DEFAULT 0,
				"lang" VARCHAR,
				"title" VARCHAR,
				"artist" VARCHAR,
				"album" VARCHAR,
				"lyrics" TEXT);';
			$stmt = $this->db_prepare($sql);
			$this->db_execute($stmt);

			$sql = 'CREATE TABLE artworks (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				created INTEGER NOT NULL DEFAULT 0,
				"artist" VARCHAR,
				"album" VARCHAR,
				"url" TEXT);';
			$stmt = $this->db_prepare($sql);
			$this->db_execute($stmt);

		}
	}

	private function install_mysql() {
		//$this->db = new PDO($this->database);
		if (!$this->db) {
			die("Unable to connect database");
		}

		$sql = "CREATE TABLE IF NOT EXISTS `lyrics` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`created` int(11) NOT NULL,
				`lang` varchar(10) NOT NULL,
				`title` varchar(255) NOT NULL,
				`artist` varchar(255) NOT NULL,
				`album` varchar(255) NOT NULL,
				`lyrics` text NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$stmt = $this->db_prepare($sql);
		$this->db_execute($stmt);

		$sql = "CREATE TABLE IF NOT EXISTS `artworks` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`created` int(11) NOT NULL,
				`artist` varchar(255) NOT NULL,
				`album` varchar(255) NOT NULL,
				`url` text NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$stmt = $this->db_prepare($sql);
		$this->db_execute($stmt);
	}

	private function install_postgres() {
		$this->db = new PDO($this->database);
		if (!$this->db) {
			die("Unable to connect database");
		}
		else {
			$sql = "CREATE SEQUENCE lyrics_id_seq;";
			$stmt = $this->db_prepare($sql);
			$this->db_execute($stmt);

			$sql = "CREATE TABLE lyrics (
					id integer PRIMARY KEY DEFAULT nextval('lyrics_id_seq'),
					created integer,
					lang varchar(10) NOT NULL,
					title varchar(255),
					artist varchar(255),
					album varchar(255),
					lyrics text);";
			$stmt = $this->db_prepare($sql);
			$this->db_execute($stmt);

			$sql = "ALTER SEQUENCE lyrics_id_seq OWNED BY lyrics.id;";
			$stmt = $this->db_prepare($sql);
			$this->db_execute($stmt);

			$sql = "CREATE SEQUENCE artworks_id_seq;";
			$stmt = $this->db_prepare($sql);
			$this->db_execute($stmt);

			$sql = "CREATE TABLE artworks (
					id integer PRIMARY KEY DEFAULT nextval('artworks_id_seq'),
					created integer,
					artist varchar(255),
					album varchar(255),
					url text);";
			$stmt = $this->db_prepare($sql);
			$this->db_execute($stmt);

			$sql = "ALTER SEQUENCE artworks_id_seq OWNED BY artworks.id;";
			$stmt = $this->db_prepare($sql);
			$this->db_execute($stmt);

		}
	}

}
