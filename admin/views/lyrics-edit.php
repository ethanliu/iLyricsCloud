<div class="container">

	<form name="form" method="POST" action="index.php">
	<input type="hidden" name="q" value="lyrics">
	<input type="hidden" name="action" value="update">
	<input type="hidden" name="id" value="<?php echo $result['id']; ?>">
	<input type="hidden" name="search" value="<?php echo htmlspecialchars($search);?>">
	<input type="hidden" name="page" value="<?php echo @intval($_REQUEST['page']);?>">

	<fieldset>
		<legend>Edit lyrics #<?php echo $result['id']; ?></legend>
	</fieldset>

	<div class="control-group">
		<label class="control-label">Source</label>
		<select name="lang" class="form-control">
				<option value=""></option>
				<?php foreach ($plugins['lyrics'] as $source): ?>
				<option value="<?php echo htmlspecialchars($source); ?>" <?php if ($source === $result['lang']): ?>selected<?php endif; ?>><?php echo $source; ?></option>
				<?php endforeach; ?>
		</select>
	</div>

	<div class="control-group">
		<label class="control-label">Title</label>
		<input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($result['title']); ?>">
	</div>

	<div class="control-group">
		<label class="control-label">Artist</label>
		<input type="text" name="artist" class="form-control" value="<?php echo htmlspecialchars($result['artist']); ?>">
	</div>

	<div class="control-group">
		<label class="control-label">Album</label>
		<input type="text" name="album" class="form-control" value="<?php echo htmlspecialchars($result['album']); ?>">
	</div>

	<div class="control-group">
		<label class="control-label">Lyrics</label>
		<textarea name="lyrics" rows=15 cols=10 class="form-control"><?php echo $result['lyrics']; ?></textarea>
	</div>

	<div class="control-group">
		<label style="width:auto;"><input type="checkbox" id="x-delete" name="delete" value="1"> Delete forever</label>
	</div>

	<div class="form-actions control-group">
		<input type="submit" class="btn btn-primary" value="Save">
	</div>


	</form>

</div>

