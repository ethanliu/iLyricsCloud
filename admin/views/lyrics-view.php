<table class="table table-striped">
<tr>
	<th class="col-xs-1"></th>
	<th class="col-xs-1">Source</th>
	<th class="col-xs-4">Song</th>
	<th class="col-xs-6">Lyrics</th>
</tr>
<?php
	$fullview = true;
	for ($i=0, $loop = count($result); $i < $loop; $i++):
		$lyrics = ($fullview) ? nl2br($result[$i]['lyrics']) : $result[$i]['lyrics'];
?>
<tr>
	<td><a class="btn btn-sm btn-default" href="?q=<?php echo $module; ?>&action=edit&id=<?php echo $result[$i]['id']; ?>&page=<?php echo $page; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-small">Edit</a></td>
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

<div id="pagination">
<?php
$pager = new Pager($pages, $page);
echo $pager->paginate();
?>
</div>
