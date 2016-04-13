<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>iLyrics Cloud Admin</title>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" type="text/css" media="all">
	<style>
	body {
		padding:40px;
	}
	footer {
		padding-top:40px;
		text-align:center;
	}
	</style>
</head>
<body>
<div class="row">
	<div class="col-xs-12 col-sm-3 sidebar">
		<legend>Search</legend>
		<form name="form" method="POST" action="#" onSubmit="return false;">
			<label>Target:</label>
			<select name="action" class="form-control">
				<option value="lyrics">Lyrics</option>
				<option value="artwork">Artworks</option>
				<option value="search">Database</option>
			</select><br>
			<label>Lang:</label>
			<select name="lang" class="form-control">
				<option value="en">International</option>
				<option value="zh">Mandarin</option>
				<option value="jp">Japanese/Korean</option>
			</select><br>
			<label>Title:</label>
			<input type="text" name="title" class="form-control" value=""><br>
			<label>Artist:</label>
			<input type="text" name="artist" class="form-control" value=""><br>
			<label>Album:</label>
			<input type="text" name="album" class="form-control" value=""><br>
			<label></label>
			<button id="submit" class="btn btn-primary">Search</button><br>
		</form>
	</div>
	<div class="col-xs-12 col-sm-9">
		<legend>Result</legend>
		<textarea name="lrc" id="lrc" class="form-control" style="height:350px;"></textarea>
		<p class="help-block" id="console"></p>
	</div>
</div>

<footer>
	<p>iLyricsCloud <?php echo file_get_contents("./VERSION"); ?> | <a href="https://github.com/ethanliu/iLyricsCloud">github</a></p>
</footer>

<script src="//code.jquery.com/jquery-2.2.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


<script>
$(function() {
	$("#submit").click(function() {
		$("#console").text('Searching...');
		var title = $("input[name='title']").val();
		var artist = $("input[name='artist']").val();
		var album = $("input[name='album']").val();
		
		if ((title + artist + album) === '') {
			return false;
		}
		
		var url = "q.php?";
		url += "&action=" + $("select[name='action'] option:selected").val();
		url += "&lang=" + $("select[name='lang'] option:selected").val();
		url += "&title=" + title;
		url += "&artist=" + artist;
		url += "&album=" + album;
		$.post(url, function(data) {
			var error = data.error;
			if (data['result'] && data['result'].length) {
				var lyrics = data['result'][0].lyrics;
				$("#lrc").val(lyrics);
				if (lyrics === '') {
					error += 'Not found.';
				}
			}
			else {
				var link = data['url'];
				$("#lrc").val(link);
				if (link === '') {
					error += 'Not found.';
				}
			}
			$("#console").text(error);
		}, 'json');
	});
})
</script>

</body>
</html>