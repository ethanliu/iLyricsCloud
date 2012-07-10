<?php
if (empty($result)) {
	echo "No record.";
	exit;
}
?>
<form name="form" method="POST" action="index.php">
<input type="hidden" name="q" value="lyrics">
<input type="hidden" name="action" value="update">
<input type="hidden" name="id" value="<?php echo $result['id']; ?>">
<input type="hidden" name="search" value="<?php echo htmlspecialchars($search);?>">
<input type="hidden" name="page" value="<?php echo @intval($_REQUEST['page']);?>">

<div class="clearfix">
	<label>Lang</label>
	<div class="input">
		<select name="lang" class="span2">
			<option value="en" <?php if ($result['lang'] == 'en') { echo 'selected'; } ?>>en</option>
			<option value="zh" <?php if ($result['lang'] == 'zh') { echo 'selected'; } ?>>zh</option>
			<option value="jp" <?php if ($result['lang'] == 'jp') { echo 'selected'; } ?>>jp</option>
		</select>
	</div>
</div>

<div class="clearfix">
	<label>Title</label>
	<div class="input">
		<input type="text" name="title" class="span6" value="<?php echo htmlspecialchars($result['title']); ?>">
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
	<label>Lyrics</label>
	<div class="input">
		<textarea name="lyrics" rows=15 cols=10 class="span10"><?php echo $result['lyrics']; ?></textarea>
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
