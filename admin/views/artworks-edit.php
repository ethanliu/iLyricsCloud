<div class="container">

	<form name="form" method="POST" action="index.php">
	<input type="hidden" name="q" value="artworks">
	<input type="hidden" name="action" value="update">
	<input type="hidden" name="id" value="<?php echo $result['id']; ?>">

	<fieldset>
		<legend>Edit artworks #<?php echo $result['id']; ?></legend>
	</fieldset>

	<div class="control-group">
		<label class="control-label">Artist</label>
		<input type="text" name="artist" class="form-control" value="<?php echo htmlspecialchars($result['artist']); ?>">
	</div>

	<div class="control-group">
		<label class="control-label">Album</label>
		<input type="text" name="album" class="form-control" value="<?php echo htmlspecialchars($result['album']); ?>">
	</div>

	<div class="control-group">
		<label class="control-label">URL</label>
		<input type="text" name="url" class="form-control" value="<?php echo htmlspecialchars($result['url']); ?>">
	</div>

	<div class="control-group">
		<label><input type="checkbox" id="x-delete" name="delete" value="1"> Delete forever</label>
	</div>

	<div class="form-actions control-group">
		<input type="submit" class="btn btn-primary" value="Save">
	</div>

	</form>

	<div class="control-group">
		<label class="control-label"></label>
		<div class="artwork"><img src="<?php echo $result['url']; ?>"></div>
	</div>

</div>

