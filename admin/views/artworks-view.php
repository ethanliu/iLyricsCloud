<table class="table table-striped">
<tr>
	<th class="col-xs-1"></th>
	<th class="col-xs-2">Artist</th>
	<th class="col-xs-2">Album</th>
	<th class="col-xs-5">URL</th>
	<th class="col-xs-2">Artwork</th>
</tr>
<?php
	$fullview = false;
	for ($i=0, $loop = count($result); $i < $loop; $i++):
?>
<tr>
	<td>
		<a class="btn btn-sm btn-default" href="?q=artworks&action=edit&id=<?php echo $result[$i]['id']; ?>" class="btn btn-small">Edit</a>
	</td>
	<td><?php echo $result[$i]['artist']; ?></td>
	<td><?php echo $result[$i]['album']; ?></td>
	<td><?php echo $result[$i]['url']; ?></td>
	<td><a href="<?php echo $result[$i]['url']; ?>" target="_blank"><img src="<?php echo $result[$i]['url']; ?>" class="img-responsive" width="100"></a></td>
</tr>
<?php endfor; ?>
</table>

<div id="pagination">
<?php
$pager = new Pager($pages, $page);
echo $pager->paginate();
?>
</div>
