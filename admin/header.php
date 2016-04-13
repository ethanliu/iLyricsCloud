<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>iLyrics Cloud Admin</title>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="css/master.css" />
</head>
<body>

<div class="navigation">
	<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar">
				<span class="sr-only">Toggle Navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" id="nav-toggle" href="https://github.com/ethanliu/iLyricsCloud"><span style="color:#aaa !important;">iLyrics</span><span style="color:#eee !important;">Cloud</span></a>
		</div>
		<div class="collapse navbar-collapse" id="top-navbar">
				<?php if (isset($_SESSION['login_id']) && $_SESSION['login_id']):?>
				<ul id="primary_navigation" class="nav navbar-nav">
					<li><a href="./?q=artworks">Artworks</a></li>
					<li><a href="./?q=lyrics">Lyrics</a></li>
					<li><a href="../" target="_blank">Search</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
				</ul>
				<?php endif;?>
		</div>
	</div>
	</nav>
</div>


<div class="container">
	<div id="content" class="content">
