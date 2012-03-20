<table>
<tr>
	<th></th>
	<th>ID</th>
	<th>Time</th>
	<th>Serial</th>
	<th>Category</th>
	<th>News</th>
</tr>
<?php
	$fullview = false;
	for ($i=0, $loop = count($result); $i < $loop; $i++):
?>
<tr>
	<td width="10%">
		<a href="?q=news&action=edit&id=<?php echo $result[$i]['id']; ?>" class="btn">Edit</a>
	</td>
	<td width="10%"><?php echo $result[$i]['id']; ?></td>
	<td width="15%" nowrap><?php echo _time($result[$i]['created']); ?></td>
	<td width="10%" nowrap><?php echo $result[$i]['created']; ?></td>
	<td width="10%" nowrap><?php echo $result[$i]['category']; ?></td>
	<td><?php echo $result[$i]['news']; ?></td>
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
		&nbsp;&nbsp;
		<a href="?q=news&action=edit&id=" class="btn">Add</a>
	</div>
</div>
