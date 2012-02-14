<?php
if (empty($result)) {
	echo "No record.";
	exit;
}
?>
<table>
<tr>
	<th></th>
	<th>ID</th>
	<th>Time</th>
	<th>Artist</th>
	<th>Album</th>
	<th>URL</th>
	<th>Artwork</th>
</tr>
<?php
	$fullview = false;
	for ($i=0, $loop = count($result); $i < $loop; $i++):
?>
<tr>
	<td>
		<a href="?q=artworks&action=edit&id=<?php echo $result[$i]['id']; ?>" class="btn">Edit</a>
	</td>
	<td><?php echo $result[$i]['id']; ?></td>
	<td nowrap><?php echo _time($result[$i]['created']); ?></td>
	<td><?php echo $result[$i]['artist']; ?></td>
	<td><?php echo $result[$i]['album']; ?></td>
	<td><?php echo $result[$i]['url']; ?></td>
	<td><div class="artwork"><img src="<?php echo $result[$i]['url']; ?>" height="80"></div></td>
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
		<a href="?q=artworks&page=<?php echo $page-1; ?>" class="btn">Prev</a>
		<a href="?q=artworks&page=<?php echo $page+1; ?>" class="btn">Next</a>
	</div>
</div>
