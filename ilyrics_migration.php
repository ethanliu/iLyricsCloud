<?php
set_time_limit(0);
ini_set('memory_limit', '640M');
require dirname(__FILE__) . "/classes/ilyrics.php";

function _run($limit) {
	$fetcher = new LyricsFetcher();
	$sql = "SELECT id, track AS title, artist, album, lyrics FROM ilyrics_widget_db ORDER BY id ASC LIMIT {$limit}";
	$stmt = $fetcher->db_prepare($sql);
	$data = $fetcher->db_getAll($stmt);
	$total = count($data);

	for ($i=0, $loop = count($data); $i < $loop; $i++) { 
		$fetcher = new LyricsFetcher();

		$fetcher->lyricsSource = 'en';
		$lyrics = trim($data[$i]['lyrics']);
		if (!empty($lyrics)) {
		
			if (preg_match('/\p{Hangul}/u', $lyrics)) {
				$fetcher->lyricsSource = 'jp';
			}
			else if (preg_match('/\p{Katakana}/u', $lyrics)) {
				$fetcher->lyricsSource = 'jp';
			}
			else if (preg_match('/\p{Hiragana}/u', $lyrics)) {
				$fetcher->lyricsSource = 'jp';
			}
			else if (preg_match('/\p{Han}/u', $lyrics)) {
				$fetcher->lyricsSource = 'zh';
			}
		
			// kanji: [\x{4E00}-\x{9FBF}]
			// hiragana: [\x{3040}-\x{309F}]
			// katakana: [\x{30A0}-\x{30FF}]
			/*
			$pattern = '/([\x{9fa7}-\x{9FBF}\x{3040}-\x{30FF}])/ui';
			if (preg_match($pattern, $lyrics)) {
				$fetcher->lyricsSource = 'jp';
			}
			else {
				$pattern = '/([\x{4e00}-\x{9fa5}])/ui';
				if (preg_match($pattern, $lyrics)) {
					$fetcher->lyricsSource = 'zh';
				}
			}
			*/

			$fetcher->title = trim($data[$i]['title']);
			$fetcher->artist = trim($data[$i]['artist']);
			$fetcher->album = trim($data[$i]['album']);

			//$lyrics = strip_tags($data[$i]->lyrics, '<br>');
			$fetcher->saveLyrics($lyrics);
	
			echo $data[$i]['id'] . ", ";
			//echo "\t";
			//echo $fetcher->lyricsSource . ", ";
			//echo $fetcher->artist;
			//echo "\t\t";
			//echo $fetcher->title;
			//echo $fetcher->album . "<br>";
			//echo nl2br($fetcher->lyrics);
			//echo $lyrics;
			//echo "\n";
			//flush();
		}

		// delete
		$sql = "DELETE FROM ilyrics_widget_db WHERE id = " . intval($data[$i]['id']);
		$stmt = $fetcher->db_prepare($sql);
		$fetcher->db_execute($stmt);
	
		unset($fetcher);
	}

	unset($data);
}


//$id = @intval($_GET['id']) + 1;

$loop = 1000;
$limit = 100;

$start = time();

for ($i=1; $i <= $loop; $i++) { 
	echo "\n===== " . date("H:i:s", time()) . " =====\n";
	_run($limit);
}

echo "\n\nDuration: " . date("H:i:s", $start) . " ~ " . date("H:i:s", time()) . "\n\n";
/*
if ($id <= 100 && $total > 0) {
	//sleep(3);
	//header("Localtion:" . $_SERVER['PHP_SELF'] . '?id=' . $id);
	//echo '<META HTTP-EQUIV=Refresh CONTENT="10; URL=' . $_SERVER['PHP_SELF'] . '?id=' . $id . '">';
}
*/
