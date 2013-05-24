<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>iLyrics Cloud Admin</title>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.2.2/css/bootstrap.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="css/master.css" />
</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a href="./" class="brand"><span style="color:#aaa !important;">iLyrics</span><span style="color:#eee !important;">Cloud</span></a>
			<div class="nav-collapse collapse">
				<?php if (isset($_SESSION['login_id']) && $_SESSION['login_id']):?>
				<ul id="primary_navigation" class="nav">
					<li><a href="./?q=artworks">Artworks</a></li>
					<li><a href="./?q=lyrics">Lyrics</a></li>
					<li><a href="./?q=plugins">Plugins</a></li>
					<li><a href="../index.html" target="_blank">Search</a></li>
				</ul>
				<form class="navbar-search pull-right" action="">
					<input type="text" class="search-query span2" placeholder="Search" name="search" value="">
				</form>
			<?php endif;?>
			</div>
		</div>
	</div>
</div>

<div class="container">
	<div id="content" class="content">
