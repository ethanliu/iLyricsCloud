<div class="container">

	<h2>Dashboard</h2>
	<p>
		<?php echo $totalLyrics?> Lyrics.<br>
		<?php echo $totalArtworks?> Artworks.<br>
	</p>

	<?php if (!empty($upgrade)): ?>

	<h3>Upgrade database</h3>
	<hr>

	<?php if ($upgrade == '2.1.0'): ?>
		<p>Update lyrics lang "en" to "metrolyrics", "zh" to "mojim" and "jp" to "utamap".</p>
		<a href="?q=upgrade&action=<?php echo $upgrade; ?>" class="btn btn-info">Upgrade <?php echo $upgrade; ?></a>
	<?php endif?>

	<?php endif?>
</div>