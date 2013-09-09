<table class="table">
<tr>
	<th class="span1"></th>
	<th class="span1">ID</th>
	<th class="span1">Time</th>
	<th class="span2">Artist</th>
	<th class="span2">Album</th>
	<th class="span3">URL</th>
	<th class="span2">Artwork</th>
</tr>
<?php
	$fullview = false;
	for ($i=0, $loop = count($result); $i < $loop; $i++):
?>
<tr>
	<td>
		<a href="?q=artworks&action=edit&id=<?php echo $result[$i]['id']; ?>" class="btn btn-small">Edit</a>
	</td>
	<td><?php echo $result[$i]['id']; ?></td>
	<td nowrap><?php echo _time($result[$i]['created']); ?></td>
	<td><?php echo $result[$i]['artist']; ?></td>
	<td><?php echo $result[$i]['album']; ?></td>
	<td><?php echo $result[$i]['url']; ?></td>
	<td><div class="artwork span1"><img src="<?php echo $result[$i]['url']; ?>"></div></td>
</tr>
<?php endfor; ?>
</table>

<div class="actions">
	<div class="pager" align="right" style="float:right;width:300px;">
		<form name="pager" method="GET" action="./" style="margin:0;">
		<input type="hidden" name="q" value="artworks">
		<input type="text" name="page" class="span1" value="<?php echo $page; ?>"> / <?php echo $pages;?> Pages. <?php echo $total;?> Records.
		</form>
	</div>
	<div align="left">
		<?php if ($pages > 1 && $page != 1): ?><a href="?q=<?php echo $module; ?>&page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-small">Prev</a><?php endif; ?>
		<?php if ($pages > 1 && $page != $pages): ?><a href="?q=<?php echo $module; ?>&page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-small">Next</a><?php endif; ?>
	</div>
</div>
