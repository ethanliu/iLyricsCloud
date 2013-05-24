<div class="container-fluid">
	<div class="row-fluid">
		<div class="span2">


		</div>
		<div class="span10">

			<form name="form" method="POST" action="index.php">
			<input type="hidden" name="q" value="artworks">
			<input type="hidden" name="action" value="update">
			<input type="hidden" name="id" value="<?php echo $result['id']; ?>">

			<legend>Edit artworks #<?php echo $result['id']; ?></legend>
			<fieldset>

				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<div class="artwork"><img src="<?php echo $result['url']; ?>" height="200"></div>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Artist</label>
					<div class="controls">
						<input type="text" name="artist" class="span6" value="<?php echo htmlspecialchars($result['artist']); ?>">
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Album</label>
					<div class="controls">
						<input type="text" name="album" class="span6" value="<?php echo htmlspecialchars($result['album']); ?>">
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">URL</label>
					<div class="controls">
						<input type="text" name="url" class="span10" value="<?php echo htmlspecialchars($result['url']); ?>">
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<label><input type="checkbox" id="x-delete" name="delete" value="1"> Delete forever</label>
					</div>
				</div>

				<div class="form-actions">
					<input type="submit" class="btn btn-primary" value="Save">
				</div>

			</fieldset>

			</form>

		</div>
	</div>
</div>

