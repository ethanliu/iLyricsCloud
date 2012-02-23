<?php
/**
 * 
 *
 * @author Ethan Liu
 * @version $Id$
 * @copyright , 19 February, 2012
 * @package default
 **/

require_once dirname(__FILE__) . "/../common.php";
require_once dirname(__FILE__) . "/../../classes/controller.php";

class InstallModule extends Controller {
	public function __construct() {
		parent::__construct();
	}
	
	public function install() {
		if (empty($this->database)) {
			echo "There is no DSN information.";
		}
		else if (!INSTALLED) {
			//$this->install_heroku(); echo "done."; exit;
			
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
			}
			echo "Installed success, please update INSTALLED from config.php.";
		}
		else {
			echo "Database already installed.";
		}
		//$this->db_connect();
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

			$sql = 'CREATE TABLE news (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				created INTEGER NOT NULL DEFAULT 0,
				"news" TEXT);';
			$stmt = $this->db_prepare($sql);
			$this->db_execute($stmt);
		}
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

			$sql = "CREATE SEQUENCE news_id_seq;";
			$stmt = $this->db_prepare($sql);
			$this->db_execute($stmt);

			$sql = "CREATE TABLE news (
					id integer PRIMARY KEY DEFAULT nextval('news_id_seq'),
					created integer,
					news text);";
			$stmt = $this->db_prepare($sql);
			$this->db_execute($stmt);

			$sql = "ALTER SEQUENCE news_id_seq OWNED BY news.id;";
			$stmt = $this->db_prepare($sql);
			$this->db_execute($stmt);
		}
	}
	
	// temp use only, for adding missing database to heroku
	private function install_heroku() {
		$sql = "CREATE SEQUENCE news_id_seq;";
		$stmt = $this->db_prepare($sql);
		$this->db_execute($stmt);

		$sql = "CREATE TABLE news (
				id integer PRIMARY KEY DEFAULT nextval('news_id_seq'),
				created integer,
				news text);";
		$stmt = $this->db_prepare($sql);
		$this->db_execute($stmt);

		$sql = "ALTER SEQUENCE news_id_seq OWNED BY news.id;";
		$stmt = $this->db_prepare($sql);
		$this->db_execute($stmt);
	}
	
}
