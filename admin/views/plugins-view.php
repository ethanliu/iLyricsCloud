<table class="table plugin-table table-striped">
<thead>
<tr>
	<th class="span1">ID</th>
	<th class="span1">Enabled</th>
	<th class="span7">Plugin</th>
	<th class="span2">Time</th>
</tr>
</thead>
<tbody class="sorted_table">
<?php
	$fullview = false;
	for ($i=0, $loop = count($result); $i < $loop; $i++):
?>
<tr>
	<td><?php echo $result[$i]['id']; ?></td>
	<td><input type="checkbox" class="plugin" name="enabled" value="<?php echo $result[$i]['path']; ?>" <?php if ($result[$i]['enabled']): ?>checked<?php endif; ?>></td>
	<td class="plugin"><?php echo $result[$i]['path']; ?></td>
	<td nowrap><?php echo _time($result[$i]['created']); ?></td>
</tr>
<?php endfor; ?>
</tbody>
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
