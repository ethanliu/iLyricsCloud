<div class="container-fluid">
	<div class="row-fluid">
		<div class="span2">


		</div>
		<div class="span10">

			<form name="form" method="POST" action="index.php">
			<input type="hidden" name="q" value="lyrics">
			<input type="hidden" name="action" value="update">
			<input type="hidden" name="id" value="<?php echo $result['id']; ?>">
			<input type="hidden" name="search" value="<?php echo htmlspecialchars($search);?>">
			<input type="hidden" name="page" value="<?php echo @intval($_REQUEST['page']);?>">

			<legend>Edit lyrics #<?php echo $result['id']; ?></legend>
			<fieldset>

				<div class="control-group">
					<label class="control-label">Lang</label>
					<div class="controls">
						<select name="lang" class="span2">
							<option value="en" <?php if ($result['lang'] == 'en') { echo 'selected'; } ?>>en</option>
							<option value="zh" <?php if ($result['lang'] == 'zh') { echo 'selected'; } ?>>zh</option>
							<option value="jp" <?php if ($result['lang'] == 'jp') { echo 'selected'; } ?>>jp</option>
						</select>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Title</label>
					<div class="controls">
						<input type="text" name="title" class="span6" value="<?php echo htmlspecialchars($result['title']); ?>">
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
					<label class="control-label">Lyrics</label>
					<div class="controls">
						<textarea name="lyrics" rows=15 cols=10 class="span10"><?php echo $result['lyrics']; ?></textarea>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<label style="width:auto;"><input type="checkbox" id="x-delete" name="delete" value="1"> Delete forever</label>
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

