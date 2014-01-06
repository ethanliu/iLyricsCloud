<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>iLyrics Cloud Admin</title>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap.min.css" type="text/css" media="all">
	<style>
	body {
		padding:40px;
	}
	</style>
</head>
<body>

<body>

<div class="row-fluid">
	<div class="span3 sidebar">
		<legend>Search</legend>
		<form name="form" method="POST" action="#" onSubmit="return false;">
			<label>Action:</label>
			<select name="action" class="span12">
				<option value="lyrics">fetch lyrics</option>
				<option value="artwork">fetch artwork</option>
				<option value="search">search from database</option>
			</select><br>
			<label>Lang:</label>
			<select name="lang" class="span12">
				<option value="en">International</option>
				<option value="zh">Mandarin</option>
				<option value="jp">Japanese/Korean</option>
			</select><br>
			<label>Title:</label>
			<input type="text" name="title" class="span12" value=""><br>
			<label>Artist:</label>
			<input type="text" name="artist" class="span12" value=""><br>
			<label>Album:</label>
			<input type="text" name="album" class="span12" value=""><br>
			<label></label>
			<button id="submit" class="btn btn-primary">Search</button><br>
		</form>
	</div>
	<div class="span9">
		<legend>Result</legend>
		<textarea name="lrc" id="lrc" class="span12" style="height:350px;"></textarea>

	</div>
</div>

<p class="pagination-centered" style="padding-top:40px;">iLyricsCloud | <a href="https://github.com/ethanliu/iLyricsCloud">github</a></p>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/bootstrap.min.js"></script>

<script>
$(function() {
	$("#submit").click(function() {
		var url = "q.php?";
		url += "&action=" + $("select[name='action'] option:selected").val();
		url += "&lang=" + $("select[name='lang'] option:selected").val();
		url += "&title=" + $("input[name='title']").val();
		url += "&artist=" + $("input[name='artist']").val();
		url += "&album=" + $("input[name='album']").val();
		$.post(url, function(data) {
			if (data['result'] && data['result'].length) {
				var lyrics = data['result'][0].lyrics;
				$("#lrc").val(lyrics);
			}
			else {
				$("#lrc").val(data['url']);
			}
		}, 'json');
	});
})
</script>

</body>
</html>