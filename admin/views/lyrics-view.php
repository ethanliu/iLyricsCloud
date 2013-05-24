<table class="table">
<tr>
	<th class="span1"></th>
	<th class="span1">ID</th>
	<th class="span1">Time</th>
	<th class="span1">Lang</th>
	<th class="span2">Song</th>
	<th class="span6">Lyrics</th>
</tr>
<?php
	$fullview = true;
	for ($i=0, $loop = count($result); $i < $loop; $i++):
		$lyrics = ($fullview) ? nl2br($result[$i]['lyrics']) : $result[$i]['lyrics'];
?>
<tr>
	<td><a href="?q=<?php echo $module; ?>&action=edit&id=<?php echo $result[$i]['id']; ?>&page=<?php echo $page; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-small">Edit</a></td>
	<td><?php echo $result[$i]['id']; ?></td>
	<td nowrap><?php echo _time($result[$i]['created']); ?></td>
	<td><?php echo $result[$i]['lang']; ?></td>
	<td>
		<p><?php echo $result[$i]['title']; ?></p>
		<p><?php echo $result[$i]['artist']; ?></p>
		<p><?php echo $result[$i]['album']; ?></p>
	</td>
	<td><?php echo mb_substr($lyrics, 0, 100); ?></td>
</tr>
<?php endfor; ?>
</table>

<div class="pager">
	<div class="page">
		<form name="pager" method="GET" action="./" style="margin:0;">
		<input type="hidden" name="q" value="lyrics">
		<input type="text" name="page" class="span1" value="<?php echo $page; ?>"> / <?php echo $pages;?> Pages. <?php echo $total;?> Records.
		</form>
	</div>
	<div class="nav">
		<?php if ($pages > 1 && $page != 1): ?><a href="?q=<?php echo $module; ?>&page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-small">Prev</a><?php endif; ?>
		<?php if ($pages > 1 && $page != $pages): ?><a href="?q=<?php echo $module; ?>&page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-small">Next</a><?php endif; ?>
	</div>
</div>
