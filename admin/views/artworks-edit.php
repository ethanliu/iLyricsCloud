<?php
if (empty($result)) {
	echo "No record.";
	exit;
}
?>
<form name="form" method="POST" action="index.php">
<input type="hidden" name="q" value="artworks">
<input type="hidden" name="action" value="update">
<input type="hidden" name="id" value="<?php echo $result['id']; ?>">

<div class="clearfix">
	<label></label>
	<div class="input">
		<div class="artwork"><img src="<?php echo $result['url']; ?>" height="200"></div>
	</div>
</div>

<div class="clearfix">
	<label>Artist</label>
	<div class="input">
		<input type="text" name="artist" class="span6" value="<?php echo htmlspecialchars($result['artist']); ?>">
	</div>
</div>

<div class="clearfix">
	<label>Album</label>
	<div class="input">
		<input type="text" name="album" class="span6" value="<?php echo htmlspecialchars($result['album']); ?>">
	</div>
</div>

<div class="clearfix">
	<label>URL</label>
	<div class="input">
		<input type="text" name="url" class="span10" value="<?php echo htmlspecialchars($result['url']); ?>">
	</div>
</div>

<div class="clearfix">
	<label>Delete</label>
	<div class="input">
		<label style="width:auto;"><input type="checkbox" id="x-delete" name="delete" value="1"> Delete forever</label>
	</div>
</div>

<div class="actions">
	<input type="submit" class="btn primary" value="Save">
</div>

</form>
