<form name="form" method="POST" action="index.php">
<input type="hidden" name="q" value="news">
<input type="hidden" name="action" value="update">
<input type="hidden" name="id" value="<?php echo $result['id']; ?>">

<div class="clearfix">
	<label>Category</label>
	<div class="input">
		<select name="category" class="span2">
			<option value="widget" <?php if ($result['category'] == 'widget' || $result['category'] == '') { echo 'selected'; } ?>>Widget</option>
			<option value="app" <?php if ($result['category'] == 'app') { echo 'selected'; } ?>>App</option>
		</select>
	</div>
</div>

<div class="clearfix">
	<label>Time</label>
	<div class="input">
		<input type="text" name="created" class="span3" value="<?php echo date('Y-m-d H:i:s'); ?>">
	</div>
</div>

<div class="clearfix">
	<label>News</label>
	<div class="input">
		<textarea name="news" rows=15 cols=10 class="span10"><?php echo $result['news']; ?></textarea>
	</div>
</div>

<div class="actions">
	<input type="submit" class="btn primary" value="Save">
</div>

</form>
